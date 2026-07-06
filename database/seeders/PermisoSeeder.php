<?php

class PermisoSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos de permisos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // 1. Nombre de la tabla física asociada a los permisos
        $tabla = 'permisos';

        // 2. Definición de los permisos base obligatorios del sistema
        $permisos = [
            // Permisos de Administración de Usuarios
            ['id' => 1, 'nombre' => 'Crear Usuario', 'slug' => 'crear-usuario', 'descripcion' => 'Puede insertar nuevos usuarios.'],
            ['id' => 2, 'nombre' => 'Actualizar Usuario', 'slug' => 'actualizar-usuario', 'descripcion' => 'Puede actualizar usuarios.'],
            ['id' => 3, 'nombre' => 'Eliminar Usuario', 'slug' => 'eliminar-usuario', 'descripcion' => 'Puede eliminar usuarios.'],
            ['id' => 4, 'nombre' => 'Listar Usuarios', 'slug' => 'listar-usuarios', 'descripcion' => 'Puede ver el listado de usuarios.'],
            ['id' => 5, 'nombre' => 'Crear Rol', 'slug' => 'crear-rol', 'descripcion' => 'Puede insertar nuevos roles.'],
            ['id' => 6, 'nombre' => 'Actualizar Rol', 'slug' => 'actualizar-rol', 'descripcion' => 'Puede actualizar roles.'],
            ['id' => 7, 'nombre' => 'Eliminar Rol', 'slug' => 'eliminar-rol', 'descripcion' => 'Puede eliminar roles.'],
            ['id' => 8, 'nombre' => 'Listar Roles', 'slug' => 'listar-roles', 'descripcion' => 'Puede ver el listado de roles.'],
            ['id' => 9, 'nombre' => 'Crear Permiso', 'slug' => 'crear-permiso', 'descripcion' => 'Puede crear nuevos permisos.'],
            ['id' => 10, 'nombre' => 'Actualizar Permiso', 'slug' => 'actualizar-permiso', 'descripcion' => 'Puede actualizar permisos.'],
            ['id' => 11, 'nombre' => 'Eliminar Permiso', 'slug' => 'eliminar-permiso', 'descripcion' => 'Puede eliminar permisos.'],
            ['id' => 12, 'nombre' => 'Listar Permisos', 'slug' => 'listar-permisos', 'descripcion' => 'Puede ver el listado de permisos.'],
            ['id' => 13, 'nombre' => 'Ver Módulos del Administrador', 'slug' => 'ver-modulos-del-administrador', 'descripcion' => 'Sólo el rol con este permiso tendrá acceso a este menú.'],
            ['id' => 14, 'nombre' => 'Listar Menús', 'slug' => 'listar-menus', 'descripcion' => 'Puede ver el listado de menús.'],
            ['id' => 15, 'nombre' => 'Admin Dashboard', 'slug' => 'admin-dashboard', 'descripcion' => 'Permite ver el dashboard del panel del Administrador.'],
        ];

        echo "    -> Insertando permisos base en la tabla [{$tabla}]...\n";

        // Preparación de consultas para validar la existencia previa e insertar limpiamente
        $sqlCheck  = "SELECT id FROM `{$tabla}` WHERE slug = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (nombre, slug, descripcion) VALUES (?, ?, ?)";

        $stmtCheck  = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        if (!$stmtCheck || !$stmtInsert) {
            throw new \Exception("Error al preparar PermisoSeeder: " . $mysqli->error);
        }

        $insertados = 0;

        foreach ($permisos as $permiso) {
            // Validamos si el permiso ya existe mediante su slug único
            $stmtCheck->bind_param('s', $permiso['slug']);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo "       [-] El permiso '{$permiso['nombre']}' ya existe. Omitido.\n";
                continue;
            }

            // Si el permiso no está registrado, se ejecuta la inserción masiva
            $stmtInsert->bind_param('sss', $permiso['nombre'], $permiso['slug'], $permiso['descripcion']);
            $stmtInsert->execute();
            $insertados++;
        }

        $stmtCheck->close();
        $stmtInsert->close();

        if ($insertados > 0) {
            echo "\e[32m    ✅ Permisos insertados correctamente ({$insertados} nuevos).\e[0m\n";
        } else {
            echo "\e[33m    ℹ️ Todos los permisos ya se encontraban registrados.\e[0m\n";
        }
    }
}
