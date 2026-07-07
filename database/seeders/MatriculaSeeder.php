<?php

class MatriculaSeeder
{
    public function run(mysqli $mysqli): void
    {
        // 1. Nombre exacto de tu tabla en la base de datos (Plural)
        $tabla = 'matriculas';
        echo "    -> Sentando y matriculando alumnos en sus respectivos paralelos [{$tabla}]...\n";

        // Extraemos alumnos y la primera aula abierta del periodo
        $resAlumnos = $mysqli->query("SELECT id FROM alumnos WHERE estado = 1");
        $resAula = $mysqli->query("SELECT id FROM aulas_periodo LIMIT 1");

        if (!$resAlumnos || !$resAula || $resAlumnos->num_rows === 0 || $resAula->num_rows === 0) {
            echo "\e[33m    ℹ️ No existen alumnos activos o aulas del periodo abiertos para matricular. Omitido.\e[0m\n";
            return;
        }

        $aula = $resAula->fetch_assoc();
        $aulaPeriodoId = (int)$aula['id'];

        // 2. Definición de consultas con nombres exactos de tablas
        $sqlCheck = "SELECT id FROM `{$tabla}` WHERE aula_periodo_id = ? AND alumno_id = ?";
        
        // CORRECCIÓN DE COLUMNAS: Se asegura mapeo exacto con tu migración física
        $sqlInsert = "INSERT INTO `{$tabla}` (aula_periodo_id, alumno_id, fecha_matricula, numero_matricula, estado_matricula) VALUES (?, ?, ?, ?, 'Matriculado')";

        $stmtCheck  = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        // 🔥 ESCUDO DE DETECCIÓN: Si el prepare falla, te dirá el error exacto de MySQL en lugar de crashear
        if (!$stmtCheck) {
            throw new \Exception("Error preparando consulta CHECK en MatriculaSeeder: " . $mysqli->error);
        }
        if (!$stmtInsert) {
            throw new \Exception("Error preparando consulta INSERT en MatriculaSeeder: " . $mysqli->error);
        }

        $fechaActual = date('Y-m-d');
        $index = 1;

        while ($alumno = $resAlumnos->fetch_assoc()) {
            $alumnoId = (int)$alumno['id'];

            // Comprobación de duplicados
            $stmtCheck->bind_param('ii', $aulaPeriodoId, $alumnoId);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                continue; // Ya está matriculado este año, omitimos
            }

            // Generamos registro único de matrícula
            $numMatricula = "REG-2026-" . str_pad($index, 4, "0", STR_PAD_LEFT);

            // Inserción limpia
            $stmtInsert->bind_param('iiss', $aulaPeriodoId, $alumnoId, $fechaActual, $numMatricula);
            $stmtInsert->execute();
            $index++;
        }

        $stmtCheck->close();
        $stmtInsert->close();
        echo "\e[32m    ✅ Alumnos distribuidos y matriculados en sus aulas correspondientes.\e[0m\n";
    }
}
