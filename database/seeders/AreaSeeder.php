<?php

class AreaSeeder {
    /**
     * Ejecuta el seeder para poblar la base de datos con las áreas del conocimiento.
     * 
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli) {
        // 1. Nombre de la tabla física asociada a las áreas
        $tabla = 'areas';

        // 2. Definición de las áreas iniciales del Ministerio de Educación de Ecuador
        $areas = [
            ['nombre' => 'Lengua y Literatura'],
            ['nombre' => 'Matemática'],
            ['nombre' => 'Ciencias Naturales'],
            ['nombre' => 'Ciencias Sociales'],
            ['nombre' => 'Lengua Extranjera'],
            ['nombre' => 'Educación Cultural y Artística'],
            ['nombre' => 'Educación Física'],
            ['nombre' => 'Módulo Interdisciplinar'],
            ['nombre' => 'Formación Técnica'],
            ['nombre' => 'Acompañamiento Integral y Transversales'],
        ];

        echo " -> Insertando áreas base en la tabla [{$tabla}]...\n";

        // Preparación de consultas para validar e insertar de forma segura
        $sqlCheck = "SELECT id FROM `{$tabla}` WHERE nombre = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (nombre, estado) VALUES (?, 1)";

        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        if (!$stmtCheck || !$stmtInsert) {
            throw new \Exception("Error al preparar AreaSeeder: " . $mysqli->error);
        }

        $insertados = 0;

        foreach ($areas as $area) {
            // Validamos si el área ya existe por su nombre único
            $stmtCheck->bind_param('s', $area['nombre']);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo " [-] El área '{$area['nombre']}' ya existe. Omitido.\n";
                continue;
            }

            // Si no existe, se ejecuta la inserción limpia
            $stmtInsert->bind_param('s', $area['nombre']);
            $stmtInsert->execute();
            $insertados++;
        }

        $stmtCheck->close();
        $stmtInsert->close();

        if ($insertados > 0) {
            echo "\e[32m ✅ Áreas insertadas correctamente ({$insertados} nuevas).\e[0m\n";
        } else {
            echo "\e[33m ℹ️ Todas las áreas ya se encontraban registradas.\e[0m\n";
        }
    }
}
