<?php

class RolesPermisosSeeder
{
    /**
     * Ejecuta el seeder para poblar la tabla pivote de roles y permisos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // 1. Nombre de la tabla física intermedia
        $tabla = 'roles_permisos';

        echo "    -> Vinculando permisos base al rol [Administrador] en la tabla [{$tabla}]...\n";

        // 2. Extraer el ID del rol Administrador mediante su slug único
        $sqlRol = "SELECT id FROM roles WHERE slug = 'administrador' LIMIT 1";
        $resultRol = $mysqli->query($sqlRol);
        
        if (!$resultRol || $resultRol->num_rows === 0) {
            throw new \Exception("Error en RolesPermisosSeeder: No se encontró el rol 'administrador' en la base de datos.");
        }
        
        $rol = $resultRol->fetch_assoc();
        $rolId = (int)$rol['id'];

        // 3. Extraer TODOS los IDs de permisos disponibles actualmente
        // (Esto asegura que el administrador herede automáticamente todo lo sembrado en PermisoSeeder)
        $sqlPermisos = "SELECT id FROM permisos WHERE deleted_at IS NULL";
        $resultPermisos = $mysqli->query($sqlPermisos);

        if (!$resultPermisos || $resultPermisos->num_rows === 0) {
            echo "\e[33m    ℹ️ No hay permisos disponibles para vincular. Omitido.\e[0m\n";
            return;
        }

        $permisosIds = $resultPermisos->fetch_all(MYSQLI_ASSOC);

        // 4. Preparación de consultas para validar duplicados e insertar de forma limpia
        $sqlCheck  = "SELECT 1 FROM `{$tabla}` WHERE rol_id = ? AND permiso_id = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (rol_id, permiso_id) VALUES (?, ?)";

        $stmtCheck  = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        if (!$stmtCheck || !$stmtInsert) {
            throw new \Exception("Error al preparar RolesPermisosSeeder: " . $mysqli->error);
        }

        $vinculados = 0;

        foreach ($permisosIds as $permiso) {
            $permisoId = (int)$permiso['id'];

            // Validamos si la relación exacta ya existe en la tabla pivote
            $stmtCheck->bind_param('ii', $rolId, $permisoId);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                continue; // Si ya existe el enlace, lo omitimos silenciosamente
            }

            // Si no existe, ejecutamos la inserción limpia de la relación
            $stmtInsert->bind_param('ii', $rolId, $permisoId);
            $stmtInsert->execute();
            $vinculados++;
        }

        $stmtCheck->close();
        $stmtInsert->close();

        if ($vinculados > 0) {
            echo "\e[32m    ✅ Relaciones Rol-Permiso creadas correctamente ({$vinculados} nuevos enlaces).\e[0m\n";
        } else {
            echo "\e[33m    ℹ️ Todas las relaciones Rol-Permiso ya se encontraban registradas.\e[0m\n";
        }
    }
}
