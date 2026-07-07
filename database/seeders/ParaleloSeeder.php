<?php

class ParaleloSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos de paralelos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli): void
    {
        // 1. Nombre de la tabla física asociada a los paralelos
        $tabla = 'paralelos';

        // 2. Definición de los paralelos maestros iniciales de la institución
        $paralelos = [
            ['nombre' => 'A'],
            ['nombre' => 'B'],
            ['nombre' => 'C'],
            ['nombre' => 'D']
        ];

        echo "    -> Insertando catálogo maestro de paralelos en [{$tabla}]...\n";

        // Preparación de consultas para validar la existencia previa e insertar limpiamente
        $sqlCheck  = "SELECT id FROM `{$tabla}` WHERE nombre = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (nombre) VALUES (?)";

        $stmtCheck  = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        if (!$stmtCheck || !$stmtInsert) {
            throw new \Exception("Error al preparar ParaleloSeeder: " . $mysqli->error);
        }

        $insertados = 0;

        foreach ($paralelos as $p) {
            // Validamos si el paralelo ya existe por su nombre único
            $stmtCheck->bind_param('s', $p['nombre']);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo "       [-] El paralelo '{$p['nombre']}' ya existe. Omitido.\n";
                continue;
            }

            // Si el paralelo no está registrado, se ejecuta la inserción
            $stmtInsert->bind_param('s', $p['nombre']);
            $stmtInsert->execute();
            $insertados++;
        }

        $stmtCheck->close();
        $stmtInsert->close();

        if ($insertados > 0) {
            echo "\e[32m    ✅ Catálogo de paralelos inicializado correctamente ({$insertados} nuevos).\e[0m\n";
        } else {
            echo "\e[33m    ℹ️ Todos los paralelos maestros ya se encontraban registrados.\e[0m\n";
        }
    }
}
