<?php

class PersonaAdminSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // Set de datos estructurado
        $personaAdmin = [
            [1, 1, '1709290207', 'Gonzalo', 'Nicolás', 'Peñaherrera', 'Escobar', 'Ing. Gonzalo Peñaherrera', 'Peñaherrera Escobar Gonzalo Nicolás', 'M', 'gonzalop67@gmail.com']
        ];

        // Preparar la sentencia SQL respetando las columnas de tu esquema
        $stmt = $mysqli->prepare("INSERT IGNORE INTO `personas` (
            `tipo_documento_id`, `nacionalidad_id`, `dni`, 
            `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, 
            `nombre_corto`, `nombre_completo`, `genero`, `email`, `estado`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'activo')");

        if (!$stmt) {
            throw new \Exception("Error al preparar PersonaAdminSeeder: " . $mysqli->error);
        }

        $count = 0;
        foreach ($personaAdmin as $p) {
            $tipoDocId   = $p[0];
            $nacionalId  = $p[1];
            $dni         = $p[2];
            $primerName  = $p[3];
            $segundoName = $p[4];
            $primerAp    = $p[5];
            $segundoAp   = $p[6];
            $nombreCorto = $p[7];
            $nombreCompl = $p[8];
            $genero      = $p[9];
            $email       = $p[10];

            // ✔ VERIFICADO: 11 marcadores '?' enlazados con 'iisssssssss'
            $stmt->bind_param('iisssssssss', 
                $tipoDocId, $nacionalId, $dni, 
                $primerName, $segundoName, $primerAp, $segundoAp, 
                $nombreCorto, $nombreCompl, $genero, $email
            );
            
            $stmt->execute();
            $count++;
        }

        $stmt->close();
        echo "     \e[32m✔ Se registraron los datos personales del Administrador de forma exitosa.\e[0m\n";
    }
}
