<?php 
class TipoEvaluacionSeeder {
    /**
     * Ejecuta el seeder para poblar la base de datos.
     * 
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli) {
        // 1. Obtener el ID del periodo lectivo más reciente
        $resultPeriodo = $mysqli->query("SELECT id FROM periodos_lectivos ORDER BY id DESC LIMIT 1");
        $periodo = $resultPeriodo->fetch_assoc();
        
        if (!$periodo) {
            throw new Exception("Error: No se encontró ningún registro en la tabla 'periodos_lectivos'. Ejecuta su seeder primero.");
        }
        $periodoLectivoId = (int)$periodo['id'];

        // 2. NUEVO: Obtener todos los parciales asociados a los periodos académicos de este año lectivo
        $queryParciales = "
            SELECT p.id 
            FROM parciales_evaluacion p
            INNER JOIN periodos_academicos a ON p.periodo_academico_id = a.id
            WHERE a.periodo_lectivo_id = ? AND p.deleted_at IS NULL
        ";
        $stmtParciales = $mysqli->prepare($queryParciales);
        $stmtParciales->bind_param('i', $periodoLectivoId);
        $stmtParciales->execute();
        $resParciales = $stmtParciales->get_result();
        
        $parcialesIds = [];
        while ($row = $resParciales->fetch_assoc()) {
            $parcialesIds[] = (int)$row['id'];
        }
        $stmtParciales->close();

        if (empty($parcialesIds)) {
            throw new Exception("Error: No se encontraron parciales para este periodo lectivo. Ejecuta el seeder de 'periodos_academicos' y 'parciales_evaluacion' primero.");
        }

        // 3. Catálogo maestro bajo el modelo MINEDUC (Misma lógica, estructura limpia)
        $tiposEvaluacion = [
            // EJE FORMATIVO (70%)
            [
                'macro' => 'formativa',
                'nombre' => 'Actividades Individuales',
                'ponderacion' => 70.00,
                'desc' => 'Lecciones, pruebas, tareas escritas o trabajos prácticos realizados de forma autónoma.'
            ],
            [
                'macro' => 'formativa',
                'nombre' => 'Actividades Grupales',
                'ponderacion' => 70.00,
                'desc' => 'Proyectos en equipo, debates, exposiciones o talleres prácticos en clase.'
            ],
            // EJE SUMATIVO (30%)
            [
                'macro' => 'sumativa',
                'nombre' => 'Evaluación de Periodo Académico',
                'ponderacion' => 30.00,
                'desc' => 'Examen de base estructurada que mide los logros de aprendizaje del trimestre/quimestre.'
            ],
            [
                'macro' => 'sumativa',
                'nombre' => 'Proyecto Interdisciplinar',
                'ponderacion' => 30.00,
                'desc' => 'Evidencia final de la aplicación integrada de saberes de múltiples asignaturas.'
            ]
        ];

        // 4. Sentencia preparada con la nueva columna 'parcial_evaluacion_id'
        $queryInsert = "
            INSERT IGNORE INTO tipos_evaluacion 
            (periodo_lectivo_id, parcial_evaluacion_id, macro_categoria, nombre, ponderacion_macro, descripcion) 
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $stmtInsert = $mysqli->prepare($queryInsert);
        
        if (!$stmtInsert) {
            throw new Exception("Error en la preparación de la consulta: " . $mysqli->error);
        }

        // 5. Recorrer cada parcial de la base de datos e insertar sus tipos de evaluación correspondientes
        foreach ($parcialesIds as $parcialId) {
            foreach ($tiposEvaluacion as $tipo) {
                $stmtInsert->bind_param(
                    'iissds',
                    $periodoLectivoId,
                    $parcialId,        // Se vincula dinámicamente al ID del parcial real
                    $tipo['macro'],
                    $tipo['nombre'],
                    $tipo['ponderacion'],
                    $tipo['desc']
                );
                $stmtInsert->execute();
            }
        }

        $stmtInsert->close();
    }
}
