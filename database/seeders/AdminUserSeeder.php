<?php

use Core\Encrypter;

class AdminUserSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        $tablaPivot    = 'usuarios_roles';

        // 1. Buscamos el ID del rol usando $mysqli
        echo "    -> Buscando el ID del rol 'Administrador'...\n";
        $resultadoRol = $mysqli->query("SELECT id FROM roles WHERE nombre = 'Administrador' LIMIT 1");
        $rol = $resultadoRol->fetch_assoc();

        if (!$rol) {
            throw new \Exception("Error crítico: No se encontró el rol 'Administrador'. Asegúrate de que RolSeeder se ejecute antes en tu DatabaseSeeder.");
        }
        
        $rolId = (int)$rol['id'];

        // 2. Datos de tu usuario administrador real
        $username = "Administrador";
        $email = "gonzalop67@gmail.com";
        $password = Encrypter::encrypt('gP67M24e$+');
        $avatar = "992919889.png";
        $persona_id = 1;

        echo "    -> Insertando usuario administrador base...\n";

        // ✔ CORRECCIÓN: Se agregó la columna 'avatar' dentro del string del INSERT
        $stmt = $mysqli->prepare("INSERT IGNORE INTO usuarios (persona_id, username, email, password, avatar) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new \Exception("Error al preparar consulta de usuario: " . $mysqli->error);
        }
        
        // ✔ CORRECCIÓN: Se ajustó el bind a 'issss' (1 entero y 4 cadenas de texto)
        $stmt->bind_param('issss', $persona_id, $username, $email, $password, $avatar);
        $stmt->execute();
        
        // Obtener el ID numérico asignado por MySQL
        $usuarioId = $mysqli->insert_id;
        
        // 💡 NOTA IMPORTANTE: Si usas INSERT IGNORE y el usuario ya existía de antes, 
        // $mysqli->insert_id devolverá 0. Para que la tabla pivot no falle, rescatamos el ID existente:
        if ($usuarioId === 0) {
            $resUser = $mysqli->query("SELECT id FROM usuarios WHERE email = '{$email}' LIMIT 1");
            $userRow = $resUser->fetch_assoc();
            $usuarioId = (int)$userRow['id'];
        }
        
        $stmt->close();

        // 3. Vincular el usuario con su respectivo rol en la tabla pivotante
        echo "    -> Vinculando usuario ID [{$usuarioId}] con el rol Administrador ID [{$rolId}]...\n";
        
        $stmtPivot = $mysqli->prepare("INSERT IGNORE INTO `{$tablaPivot}` (usuario_id, rol_id) VALUES (?, ?)");
        if (!$stmtPivot) {
            throw new \Exception("Error al preparar consulta en tabla pivot: " . $mysqli->error);
        }
        $stmtPivot->bind_param('ii', $usuarioId, $rolId);
        $stmtPivot->execute();
        $stmtPivot->close();

        echo "\e[32m    ✅ Administrador y rol acoplados con éxito en el sistema.\e[0m\n";
    }
}
