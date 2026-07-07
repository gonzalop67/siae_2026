<?php

class CursoSeeder
{
    public function run(mysqli $mysqli): void
    {
        $tabla = 'cursos';
        $cursos = [
            ['nombre' => 'Octavo Año de EGB',           'seccion' => 'Matutina'],
            ['nombre' => 'Noveno Año de EGB',           'seccion' => 'Matutina'],
            ['nombre' => 'Décimo Año de EGB',           'seccion' => 'Matutina'],
            ['nombre' => 'Primer Año de Bachillerato',  'seccion' => 'Matutina'],
            ['nombre' => 'Segundo Año de Bachillerato', 'seccion' => 'Matutina'],
            ['nombre' => 'Tercer Año de Bachillerato',  'seccion' => 'Matutina']
        ];

        echo "    -> Insertando niveles educativos en [{$tabla}]...\n";

        $sqlCheck = "SELECT id FROM `{$tabla}` WHERE nombre = ? AND seccion = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (nombre, seccion) VALUES (?, ?)";

        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        foreach ($cursos as $c) {
            $stmtCheck->bind_param('ss', $c['nombre'], $c['seccion']);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                continue;
            }

            $stmtInsert->bind_param('ss', $c['nombre'], $c['seccion']);
            $stmtInsert->execute();
        }

        $stmtCheck->close();
        $stmtInsert->close();
        echo "\e[32m    ✅ Cursos base inicializados correctamente.\e[0m\n";
    }
}
