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
        // Set de datos estructurado fijo para el administrador
        $personaAdmin = [
            [1, 1, '1709290207', 'Gonzalo', 'Nicolás', 'Peñaherrera', 'Escobar', 'Ing. Gonzalo Peñaherrera', 'Peñaherrera Escobar Gonzalo Nicolás', 'Masculino']
        ];

        // CORREGIDO: Se elimina la columna 'estado' y se dejan exactamente 10 columnas y 10 marcadores
        $stmt = $mysqli->prepare("INSERT IGNORE INTO `personas` (
            `tipo_documento_id`, `nacionalidad_id`, `dni`, 
            `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, 
            `nombre_corto`, `nombre_completo`, `genero`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            throw new \Exception("Error al preparar PersonaAdminSeeder: " . $mysqli->error);
        }

        // OPTIMIZACIÓN: Inicializar variables de referencia fuera del bucle
        $tipoDocId = $nacionalId = 0;
        $dni = $primerName = $segundoName = $primerAp = $segundoAp = $nombreCorto = $nombreCompl = $genero = '';

        // CORREGIDO: Vinculación única fuera del bucle con 10 tipos ('iissssssss') para 10 variables exactas
        $stmt->bind_param(
            'iissssssss', 
            $tipoDocId, 
            $nacionalId, 
            $dni, 
            $primerName, 
            $segundoName, 
            $primerAp, 
            $segundoAp, 
            $nombreCorto, 
            $nombreCompl, 
            $genero
        );

        $count = 0;
        foreach ($personaAdmin as $p) {
            // Asignación de valores por referencia en cada iteración
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

            if ($stmt->execute()) {
                if ($mysqli->affected_rows > 0) {
                    $count++;
                }
            }
        }

        $stmt->close();
        
        if ($count > 0) {
            echo "     \e[32m✔ Se registraron los datos personales del Administrador de forma exitosa.\e[0m\n";
        } else {
            echo "     \e[33m⚠ El Administrador ya se encontraba registrado (omitido por INSERT IGNORE).\e[0m\n";
        }
    }
}
