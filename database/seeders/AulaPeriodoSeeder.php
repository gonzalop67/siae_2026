<?php

class AulaPeriodoSeeder
{
    public function run(mysqli $mysqli): void
    {
        $tabla = 'aulas_periodo';
        echo "    -> Generando aperturas de paralelos físicos en [{$tabla}]...\n";

        $periodoLectivoId = 1;
        $jornada = 'Matutina';
        $cupoMax = 40;

        // Recuperar los cursos base y los paralelos maestros ("A", "B", etc.)
        $resCursos = $mysqli->query("SELECT id FROM cursos");
        $resParalelos = $mysqli->query("SELECT id FROM paralelos");

        if ($resCursos->num_rows === 0 || $resParalelos->num_rows === 0) {
            throw new \Exception("Error: Los cursos o paralelos maestros no se encuentran registrados.");
        }

        $sqlCheck = "SELECT id FROM `{$tabla}` WHERE periodo_lectivo_id = ? AND curso_id = ? AND paralelo_id = ? AND jornada = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (periodo_lectivo_id, curso_id, paralelo_id, jornada, cupo_maximo) VALUES (?, ?, ?, ?, ?)";

        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        while ($curso = $resCursos->fetch_assoc()) {
            // Reiniciamos el cursor de paralelos para cada curso
            $resParalelos->data_seek(0); 
            
            // Abrimos los paralelos "A" y "B" de forma automática para cada nivel escolar
            while ($paralelo = $resParalelos->fetch_assoc()) {
                if ((int)$paralelo['id'] > 2) continue; // Solo abrimos paralelos A y B para este ejemplo inicial

                $stmtCheck->bind_param('iiis', $periodoLectivoId, $curso['id'], $paralelo['id'], $jornada);
                $stmtCheck->execute();
                $stmtCheck->store_result();

                if ($stmtCheck->num_rows > 0) {
                    continue;
                }

                $stmtInsert->bind_param('iiiis', $periodoLectivoId, $curso['id'], $paralelo['id'], $jornada, $cupoMax);
                $stmtInsert->execute();
            }
        }

        $stmtCheck->close();
        $stmtInsert->close();
        echo "\e[32m    ✅ Secciones (Cursos y Paralelos anuales) inicializadas.\e[0m\n";
    }
}
