<?php

class MatriculaSeeder {
    public function run(mysqli $mysqli): void {
        // 1. Nombre exacto de tu tabla en la base de datos (Plural)
        $tabla = 'matriculas';
        echo " -> Sentando y distribuyendo alumnos de forma equitativa en sus paralelos [{$tabla}]...\n";

        // Extraemos todos los alumnos activos y TODAS las aulas abiertas del periodo
        $resAlumnos = $mysqli->query("SELECT id FROM alumnos WHERE estado = 1 ORDER BY id ASC");
        $resAulas = $mysqli->query("SELECT id FROM aulas_periodo ORDER BY id ASC");

        if (!$resAlumnos || !$resAulas || $resAlumnos->num_rows === 0 || $resAulas->num_rows === 0) {
            echo "\e[33m ℹ️ No existen alumnos activos o aulas abiertas para matricular. Omitido.\e[0m\n";
            return;
        }

        // Almacenamos los IDs de todas las aulas en un array para poder iterar sobre ellas circularmente
        $aulasIds = [];
        while ($aulaRow = $resAulas->fetch_assoc()) {
            $aulasIds[] = (int)$aulaRow['id'];
        }
        $totalAulas = count($aulasIds);

        // 2. Definición de consultas con nombres exactos de tablas
        $sqlCheck = "SELECT id FROM `{$tabla}` WHERE aula_periodo_id = ? AND alumno_id = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (aula_periodo_id, alumno_id, fecha_matricula, numero_matricula, estado_matricula) VALUES (?, ?, ?, ?, 'Matriculado')";
        
        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        // Escudo de detección de errores de MySQL
        if (!$stmtCheck) {
            throw new \Exception("Error preparando consulta CHECK en MatriculaSeeder: " . $mysqli->error);
        }
        if (!$stmtInsert) {
            throw new \Exception("Error preparando consulta INSERT en MatriculaSeeder: " . $mysqli->error);
        }

        $fechaActual = date('Y-m-d');
        $index = 1;
        $keyAula = 0; // Índice de control para rotar aulas

        while ($alumno = $resAlumnos->fetch_assoc()) {
            $alumnoId = (int)$alumno['id'];
            
            // Asignación circular: reparte los 30 alumnos equitativamente entre las aulas cargadas
            $aulaPeriodoId = $aulasIds[$keyAula % $totalAulas];

            // Comprobación de duplicados
            $stmtCheck->bind_param('ii', $aulaPeriodoId, $alumnoId);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                $keyAula++; // Avanzamos a la siguiente aula para la siguiente vuelta del bucle
                continue; // Ya está matriculado este año, omitimos
            }

            // Generamos registro único de matrícula
            $numMatricula = "REG-2026-" . str_pad($index, 4, "0", STR_PAD_LEFT);

            // Inserción limpia
            $stmtInsert->bind_param('iiss', $aulaPeriodoId, $alumnoId, $fechaActual, $numMatricula);
            $stmtInsert->execute();
            
            $index++;
            $keyAula++; // Cambiamos el aula asignada para el siguiente estudiante de la lista
        }

        $stmtCheck->close();
        $stmtInsert->close();
        echo "\e[32m ✅ Alumnos distribuidos y matriculados correctamente en las ($totalAulas) aulas del periodo.\e[0m\n";
    }
}
