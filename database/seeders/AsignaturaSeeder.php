<?php

class AsignaturaSeeder {
    /**
     * Ejecuta el seeder para poblar la base de datos con las asignaturas oficiales.
     * 
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli) {
        // 1. Nombre de la tabla física asociada a las asignaturas
        $tabla = 'asignaturas';

        // 2. Definición de asignaturas agrupadas por el nombre exacto de su área matriz
        $asignaturas = [
            // Lengua y Literatura
            ['area' => 'Lengua y Literatura', 'nombre' => 'Lengua y Literatura', 'codigo' => 'LYL-01'],

            // Matemática
            ['area' => 'Matemática', 'nombre' => 'Matemática', 'codigo' => 'MAT-01'],

            // Ciencias Naturales
            ['area' => 'Ciencias Naturales', 'nombre' => 'Ciencias Naturales', 'codigo' => 'CCN-01'],
            ['area' => 'Ciencias Naturales', 'nombre' => 'Biología', 'codigo' => 'BIO-01'],
            ['area' => 'Ciencias Naturales', 'nombre' => 'Física', 'codigo' => 'FIS-01'],
            ['area' => 'Ciencias Naturales', 'nombre' => 'Química', 'codigo' => 'QUI-01'],

            // Ciencias Sociales
            ['area' => 'Ciencias Sociales', 'nombre' => 'Estudios Sociales', 'codigo' => 'ESS-01'],
            ['area' => 'Ciencias Sociales', 'nombre' => 'Historia', 'codigo' => 'HIS-01'],
            ['area' => 'Ciencias Sociales', 'nombre' => 'Filosofía', 'codigo' => 'FIL-01'],
            ['area' => 'Ciencias Sociales', 'nombre' => 'Educación para la Ciudadanía', 'codigo' => 'EPC-01'],

            // Lengua Extranjera
            ['area' => 'Lengua Extranjera', 'nombre' => 'Inglés', 'codigo' => 'ING-01'],

            // Educación Cultural y Artística
            ['area' => 'Educación Cultural y Artística', 'nombre' => 'Educación Cultural y Artística (ECA)', 'codigo' => 'ECA-01'],

            // Educación Física
            ['area' => 'Educación Física', 'nombre' => 'Educación Física', 'codigo' => 'EDF-01'],

            // Módulo Interdisciplinar
            ['area' => 'Módulo Interdisciplinar', 'nombre' => 'Emprendimiento y Gestión', 'codigo' => 'EYG-01'],

            // Formación Técnica
            ['area' => 'Formación Técnica', 'nombre' => 'Módulos Formativos de la Figura Profesional', 'codigo' => 'MFT-01'],

            // Acompañamiento Integral y Transversales
            ['area' => 'Acompañamiento Integral y Transversales', 'nombre' => 'Cívica, Ética y Transparencia', 'codigo' => 'CET-01'],
            ['area' => 'Acompañamiento Integral y Transversales', 'nombre' => 'Animación a la Lectura', 'codigo' => 'ANL-01'],
            ['area' => 'Acompañamiento Integral y Transversales', 'nombre' => 'Orientación Vocacional', 'codigo' => 'ORV-01'],
            ['area' => 'Acompañamiento Integral y Transversales', 'nombre' => 'Formación en Centros de Trabajo (FCT)', 'codigo' => 'FCT-01'],
            ['area' => 'Acompañamiento Integral y Transversales', 'nombre' => 'Proyectos Sociocomunitarios', 'codigo' => 'PSC-01'],
        ];

        echo " -> Insertando asignaturas base en la tabla [{$tabla}]...\n";

        // Consultas preparadas para: buscar área, verificar duplicado de asignatura e insertar
        $sqlFindArea = "SELECT id FROM areas WHERE nombre = ?";
        $sqlCheck    = "SELECT id FROM `{$tabla}` WHERE codigo = ?";
        $sqlInsert   = "INSERT INTO `{$tabla}` (area_id, nombre, codigo, estado) VALUES (?, ?, ?, 1)";

        $stmtFindArea = $mysqli->prepare($sqlFindArea);
        $stmtCheck    = $mysqli->prepare($sqlCheck);
        $stmtInsert   = $mysqli->prepare($sqlInsert);

        if (!$stmtFindArea || !$stmtCheck || !$stmtInsert) {
            throw new \Exception("Error al preparar AsignaturaSeeder: " . $mysqli->error);
        }

        $insertados = 0;

        foreach ($asignaturas as $asig) {
            // 1. Validamos si la asignatura ya existe por su código único
            $stmtCheck->bind_param('s', $asig['codigo']);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo " [-] La asignatura con código '{$asig['codigo']}' ({$asig['nombre']}) ya existe. Omitida.\n";
                continue;
            }

            // 2. Buscamos el ID del área dinámicamente para no depender de IDs fijos
            $stmtFindArea->bind_param('s', $asig['area']);
            $stmtFindArea->execute();
            $resultArea = $stmtFindArea->get_result();

            if ($resultArea->num_rows === 0) {
                echo " [!] Error: El área '{$asig['area']}' no fue encontrada en la base de datos. Omitiendo '{$asig['nombre']}'.\n";
                continue;
            }

            $areaRow = $resultArea->fetch_assoc();
            $areaId = $areaRow['id'];

            // 3. Si pasa las validaciones, se ejecuta la inserción limpia
            $stmtInsert->bind_param('iss', $areaId, $asig['nombre'], $asig['codigo']);
            $stmtInsert->execute();
            $insertados++;
        }

        $stmtFindArea->close();
        $stmtCheck->close();
        $stmtInsert->close();

        if ($insertados > 0) {
            echo "\e[32m ✅ Asignaturas insertadas correctamente ({$insertados} nuevas).\e[0m\n";
        } else {
            echo "\e[33m ℹ️ Todas las asignaturas ya se encontraban registradas.\e[0m\n";
        }
    }
}
