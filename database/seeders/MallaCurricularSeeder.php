<?php

class MallaCurricularSeeder
{
    /**
     * Sembrador unificado de Malla Curricular e Insumos de Evaluación de forma atómica.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli): void
    {
        $tablaMalla = 'malla_curricular';
        $tablaInsumos = 'insumos_evaluacion';

        echo "    -> Estructurando asignaciones en [{$tablaMalla}] e Insumos en [{$tablaInsumos}]...\n";

        // 1. Obtener el ID del Primer Trimestre de forma segura
        $resBloque = $mysqli->query("SELECT id FROM periodos_academicos WHERE nombre = 'Primer Trimestre' LIMIT 1");
        if (!$resBloque || $resBloque->num_rows === 0) {
            throw new \Exception("Error en MallaCurricularSeeder: No se encontró el 'Primer Trimestre'.");
        }
        $bloqueRow = $resBloque->fetch_assoc();
        $bloqueId = (int)$bloqueRow['id'];

        // 2. Extraer los IDs de cursos y asignaturas cargados previamente
        $resCursos = $mysqli->query("SELECT id FROM cursos");
        $resAsig = $mysqli->query("SELECT id FROM asignaturas");

        if (!$resCursos || !$resAsig || $resCursos->num_rows === 0 || $resAsig->num_rows === 0) {
            throw new \Exception("Error: Cursos o Asignaturas vacías en la base de datos.");
        }

        $cursosIds = $resCursos->fetch_all(MYSQLI_ASSOC);
        $asigIds = $resAsig->fetch_all(MYSQLI_ASSOC);
        
        $periodoLectivoId = 1; 
        $horasSemanales = 4;

        // 3. Actividades base mapeadas a los tipos ID fijos del catálogo maestro (1, 2, 3, 4)
        $actividades = [
            ['tipo_id' => 1, 'titulo' => 'Deber 1: Investigación General', 'fecha' => '2025-09-15'],
            ['tipo_id' => 1, 'titulo' => 'Lección Escrita N°1',           'fecha' => '2025-10-05'],
            ['tipo_id' => 2, 'titulo' => 'Taller Práctico en Clase',     'fecha' => '2025-10-20'],
            ['tipo_id' => 2, 'titulo' => 'Exposición del Proyecto Aula', 'fecha' => '2025-11-12'],
            ['tipo_id' => 3, 'titulo' => 'Fase 1: Proyecto Interdisciplinar', 'fecha' => '2025-12-01'],
            ['tipo_id' => 4, 'titulo' => 'Examen Cuantitativo Trimestral', 'fecha' => '2025-12-15'],
        ];

        $descripcion = "Actividad autogenerada para la evaluación del Lote 1 de SIAE 2026.";

        // 4. Preparación de consultas preparadas esenciales
        $sqlCheckMalla  = "SELECT id FROM `{$tablaMalla}` WHERE periodo_lectivo_id = ? AND curso_id = ? AND asignatura_id = ?";
        $sqlInsertMalla = "INSERT INTO `{$tablaMalla}` (periodo_lectivo_id, curso_id, asignatura_id, horas_semanales) VALUES (?, ?, ?, ?)";
        
        $sqlCheckInsumo  = "SELECT id FROM `{$tablaInsumos}` WHERE periodo_academico_id = ? AND tipo_evaluacion_id = ? AND malla_curricular_id = ? AND titulo = ?";
        $sqlInsertInsumo = "INSERT INTO `{$tablaInsumos}` (periodo_academico_id, tipo_evaluacion_id, malla_curricular_id, titulo, fecha_actividad, descripcion) VALUES (?, ?, ?, ?, ?, ?)";

        $stmtCheckMalla  = $mysqli->prepare($sqlCheckMalla);
        $stmtInsertMalla = $mysqli->prepare($sqlInsertMalla);
        $stmtCheckInsumo  = $mysqli->prepare($sqlCheckInsumo);
        $stmtInsertInsumo = $mysqli->prepare($sqlInsertInsumo);

        if (!$stmtCheckMalla || !$stmtInsertMalla || !$stmtCheckInsumo || !$stmtInsertInsumo) {
            throw new \Exception("Error preparando las sentencias en MallaCurricularSeeder: " . $mysqli->error);
        }

        $mallasCreadas = 0;
        $insumosCreados = 0;

        // 5. Ciclo masivo atómico en caliente
        foreach ($cursosIds as $curso) {
            $cursoId = (int)$curso['id'];
            
            foreach ($asigIds as $asig) {
                $asignaturaId = (int)$asig['id'];
                
                // VALIDACIÓN DE LA MALLA
                $stmtCheckMalla->bind_param('iii', $periodoLectivoId, $cursoId, $asignaturaId);
                $stmtCheckMalla->execute();
                $stmtCheckMalla->store_result();

                $mallaId = 0;
                if ($stmtCheckMalla->num_rows > 0) {
                    $stmtCheckMalla->free_result();
                    
                    // CORRECCIÓN CRÍTICA: Extraemos el ID real mediante una consulta plana limpia libre de punteros bloqueados
                    $sqlId = "SELECT id FROM `{$tablaMalla}` WHERE periodo_lectivo_id = {$periodoLectivoId} AND curso_id = {$cursoId} AND asignatura_id = {$asignaturaId} LIMIT 1";
                    $resId = $mysqli->query($sqlId);
                    if ($resId) {
                        $rowId = $resId->fetch_assoc();
                        $mallaId = (int)$rowId['id'];
                        $resId->close();
                    }
                } else {
                    $stmtCheckMalla->free_result();
                    
                    // Inserción limpia si no existía
                    $stmtInsertMalla->bind_param('iiii', $periodoLectivoId, $cursoId, $asignaturaId, $horasSemanales);
                    $stmtInsertMalla->execute();
                    $mallaId = $mysqli->insert_id;
                    $mallasCreadas++;
                }

                // Si por algún motivo extremo el ID sigue en 0, no continuamos para evitar violar la FK
                if ($mallaId === 0) continue;

                // REGISTRO SEGURO DE LOS INSUMOS ACADÉMICOS
                foreach ($actividades as $act) {
                    $stmtCheckInsumo->bind_param('iiis', $bloqueId, $act['tipo_id'], $mallaId, $act['titulo']);
                    $stmtCheckInsumo->execute();
                    $stmtCheckInsumo->store_result();

                    if ($stmtCheckInsumo->num_rows > 0) {
                        $stmtCheckInsumo->free_result();
                        continue;
                    }
                    $stmtCheckInsumo->free_result();

                    $stmtInsertInsumo->bind_param('iiisss', $bloqueId, $act['tipo_id'], $mallaId, $act['titulo'], $act['fecha'], $descripcion);
                    $stmtInsertInsumo->execute();
                    $insumosCreados++;
                }
            }
        }

        $stmtCheckMalla->close();
        $stmtInsertMalla->close();
        $stmtCheckInsumo->close();
        $stmtInsertInsumo->close();

        // Forzamos el vaciado completo de la memoria hacia las tablas físicas del disco duro
        $mysqli->query("COMMIT;");

        echo "\e[32m       ✅ Malla curricular establecida ({$mallasCreadas} nuevas asignaciones).\e[0m\n";
        echo "\e[32m       ✅ Cuadrícula de calificaciones inicializada ({$insumosCreados} insumos base creados en la malla).\e[0m\n";
    }
}
