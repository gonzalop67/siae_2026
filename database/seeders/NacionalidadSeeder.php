<?php

class NacionalidadSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        $nacionalidades = [
            'Ecuatoriana',
            'Colombiana',
            'Venezolana',
            'Haitiana',
            'Peruana'
        ];

        // Preparar la consulta
        $stmt = $mysqli->prepare("INSERT IGNORE INTO `nacionalidades` (`nombre`) VALUES (?)");

        if (!$stmt) {
            echo "\e[31m  ❌ Error al preparar NacionalidadSeeder: \e[0m" . $mysqli->error . "\n";
            return;
        }

        $count = 0;
        foreach ($nacionalidades as $nombre) {
            $stmt->bind_param('s', $nombre);
            $stmt->execute();
            $count++;
        }

        $stmt->close();
        echo "     \e[32m✔ Se registraron {$count} nacionalidades con éxito.\e[0m\n";
    }
}

