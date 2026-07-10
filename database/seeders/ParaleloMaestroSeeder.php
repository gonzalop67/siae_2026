<?php

class ParaleloMaestroSeeder
{
    /**
     * Ejecuta el seeder para poblar el catálogo de paralelos y la tabla puente.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // =====================================================================
        // PASO 1: Poblar el Catálogo Maestro Estático (paralelos)
        // =====================================================================
        $letras = array('A', 'B', 'C', 'D');
        $stmtCat = $mysqli->prepare("INSERT IGNORE INTO paralelos (nombre) VALUES (?)");
        
        if (!$stmtCat) {
            throw new Exception("Error en catálogo paralelos: " . $mysqli->error);
        }

        foreach ($letras as $letra) {
            $stmtCat->bind_param('s', $letra);
            $stmtCat->execute();
        }
        $stmtCat->close();

        // =====================================================================
        // PASO 2: Poblado de la Tabla Puente (curso_paralelo_periodo)
        // =====================================================================
        // 1. Obtener el último periodo lectivo
        $resPeriodo = $mysqli->query("SELECT id FROM periodos_lectivos ORDER BY id DESC LIMIT 1");
        $periodo = $resPeriodo->fetch_assoc();
        $periodoId = $periodo ? (int)$periodo['id'] : 1;

        // 2. 🔥 SOLUCIÓN DEFINITIVA: IDs de las letras 'A' y 'B' generadas en el PASO 1
        $paralelosAsignar = array(1, 2); 

        // 3. Obtener todos los cursos configurados en tu sistema
        $resCursos = $mysqli->query("SELECT id FROM cursos");
        $cursosIds = array();
        while ($row = $resCursos->fetch_assoc()) {
            $cursosIds[] = (int)$row['id'];
        }

        // 4. 🔥 SOLUCIÓN DEFINITIVA: IDs de prueba para docentes tutores (asumiendo IDs del 1 al 5 en tu tabla usuarios)
        $tutores = array(1, 2, 3, 4, 5);

        // 5. Preparar inserción en la tabla puente con bind_param
        $stmtPuente = $mysqli->prepare("INSERT IGNORE INTO curso_paralelo_periodo 
            (periodo_lectivo_id, curso_id, paralelo_id, docente_id, cupo_maximo) 
            VALUES (?, ?, ?, ?, ?)");

        if (!$stmtPuente) {
            throw new Exception("Error en tabla puente paralelos: " . $mysqli->error);
        }

        $cupoMaximo = 40;
        $tutorIndex = 0;

        foreach ($cursosIds as $cursoId) {
            foreach ($paralelosAsignar as $paraleloId) {
                // Asignación circular y secuencial de docentes tutores
                $docenteId = $tutores[$tutorIndex % count($tutores)];
                $tutorIndex++;

                $stmtPuente->bind_param(
                    'iiiii', 
                    $periodoId, 
                    $cursoId, 
                    $paraleloId, 
                    $docenteId, 
                    $cupoMaximo
                );
                $stmtPuente->execute();
            }
        }

        $stmtPuente->close();
        echo "Catálogo de paralelos y asignación de tutores procesados con éxito.\n";
    }
}
