<?php

class AsignaturaSeeder
{
    public function run(mysqli $mysqli): void
    {
        $tabla = 'asignaturas';
        $materias = [
            ['nombre' => 'Matemáticas',          'codigo' => 'MAT-01'],
            ['nombre' => 'Lengua y Literatura',  'codigo' => 'LEN-01'],
            ['nombre' => 'Ciencias Naturales',   'codigo' => 'CNA-01'],
            ['nombre' => 'Estudios Sociales',    'codigo' => 'ESS-01'],
            ['nombre' => 'Inglés',               'codigo' => 'ING-01'],
            ['nombre' => 'Educación Física',     'codigo' => 'EFI-01']
        ];

        echo "    -> Insertando catálogo de asignaturas en [{$tabla}]...\n";

        $sqlCheck = "SELECT id FROM `{$tabla}` WHERE codigo = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (nombre, codigo) VALUES (?, ?)";

        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        foreach ($materias as $m) {
            $stmtCheck->bind_param('s', $m['codigo']);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                continue;
            }

            $stmtInsert->bind_param('ss', $m['nombre'], $m['codigo']);
            $stmtInsert->execute();
        }

        $stmtCheck->close();
        $stmtInsert->close();
        echo "\e[32m    ✅ Catálogo de asignaturas inicializado.\e[0m\n";
    }
}
