<?php

class TipoDocumentoSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // Listado de documentos estándar
        $documentos = [
            'Cédula de Identidad',
            'Pasaporte',
            'Carnet de refugiado',
            'Cédula colombiana',
            'Cédula venezolana'
        ];

        // Preparar la consulta para inserción masiva eficiente
        $stmt = $mysqli->prepare("INSERT IGNORE INTO `tipo_documento` (`descripcion`) VALUES (?)");

        if (!$stmt) {
            echo "\e[31m  ❌ Error al preparar TipoDocumentoSeeder: \e[0m" . $mysqli->error . "\n";
            return;
        }

        $count = 0;
        foreach ($documentos as $descripcion) {
            $stmt->bind_param('s', $descripcion);
            $stmt->execute();
            $count++;
        }

        $stmt->close();
        echo "     \e[32m✔ Se registraron {$count} tipos de documentos con éxito.\e[0m\n";
    }
}

