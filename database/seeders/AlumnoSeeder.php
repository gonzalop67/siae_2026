<?php

class AlumnoSeeder
{
    public function run(mysqli $mysqli): void
    {
        $tabla = 'alumnos';
        echo "    -> Sincronizando perfiles de estudiantes en [{$tabla}]...\n";

        // NOTA: Para no duplicar datos ni romper llaves, buscamos personas en tu BD 
        // cuyo ID de persona NO sea el administrador de tu AdminUserSeeder (persona_id = 1)
        $resPersonas = $mysqli->query("SELECT id FROM personas WHERE id > 1 LIMIT 10");

        if ($resPersonas->num_rows === 0) {
            echo "\e[33m    ℹ️ No hay personas adicionales cargadas para convertirlas en alumnos. Omitido.\e[0m\n";
            return;
        }

        $sqlCheck = "SELECT id FROM `{$tabla}` WHERE persona_id = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (persona_id, codigo_matricula, tipo_sangre, observaciones) VALUES (?, ?, ?, ?)";

        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        $index = 1;
        while ($persona = $resPersonas->fetch_assoc()) {
            $personaId = (int)$persona['id'];
            
            $stmtCheck->bind_param('i', $personaId);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                continue;
            }

            // Generamos un código único secuencial alineado a SIAE 2026
            $codigoMatricula = "MAT-2026-" . str_pad($index, 4, "0", STR_PAD_LEFT);
            $tipoSangre = "O+";
            $obs = "Alumno base registrado automáticamente por el sistema de semillas.";

            $stmtInsert->bind_param('isss', $personaId, $codigoMatricula, $tipoSangre, $obs);
            $stmtInsert->execute();
            $index++;
        }

        $stmtCheck->close();
        $stmtInsert->close();
        echo "\e[32m    ✅ Registro de Alumnos base completado con éxito.\e[0m\n";
    }
}
