<?php

class MenuSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // 1. Nombre de la tabla física asociada a los menús
        $tabla = 'menus';

        // 2. Definición del catálogo maestro de menús iniciales de SIAE 2026
        // Se definen los IDs fijos para poder armar la jerarquía de padre_id sin fallos
        $menus = [
            // MENÚS PRINCIPALES (padre_id = null)
            ['id' => 1, 'nombre' => 'Dashboard', 'url' => 'admin/dashboard', 'icono' => 'mdi mdi-airplay', 'permiso_slug' => 'admin-dashboard', 'padre_id' => null, 'orden' => 1],
            ['id' => 2, 'nombre' => 'Administración', 'url' => '#', 'icono' => 'mdi mdi-layers', 'permiso_slug' => 'ver-modulos-del-administrador', 'padre_id' => null, 'orden' => 2],
            ['id' => 3, 'nombre' => 'Académico', 'url' => '#', 'icono' => 'mdi mdi-school', 'permiso_slug' => 'ver-modulos-academico', 'padre_id' => null, 'orden' => 3],
            
            // SUBMENÚS DE ADMINISTRACIÓN (padre_id = 2)
            ['id' => 4, 'nombre' => 'Permisos', 'url' => 'permisos', 'icono' => null, 'permiso_slug' => 'listar-permisos', 'padre_id' => 2, 'orden' => 1],
            ['id' => 5, 'nombre' => 'Roles', 'url' => 'roles', 'icono' => null, 'permiso_slug' => 'listar-roles', 'padre_id' => 2,    'orden' => 2],
            ['id' => 6, 'nombre' => 'Usuarios', 'url' => 'usuarios', 'icono' => null, 'permiso_slug' => 'listar-usuarios', 'padre_id' => 2, 'orden' => 3],
            ['id' => 7, 'nombre' => 'Menús', 'url' => 'menus', 'icono' => null, 'permiso_slug' => 'listar-menus', 'padre_id' => 2, 'orden' => 4],

            //SUBMENÚS DE ACADÉMICO (padre_id = 3)
            ['id' => 8, 'nombre' => 'Niveles', 'url' => 'niveles', 'icono' => null, 'permiso_slug' => 'listar-niveles-academicos', 'padre_id' => 3, 'orden' => 1],
            ['id' => 9, 'nombre' => 'Subniveles', 'url' => 'subniveles', 'icono' => null, 'permiso_slug' => 'listar-subniveles-academicos', 'padre_id' => 3, 'orden' => 2],
            ['id' => 10, 'nombre' => 'Cursos', 'url' => 'cursos', 'icono' => null, 'permiso_slug' => 'listar-cursos', 'padre_id' => 3, 'orden' => 3],
            ['id' => 11, 'nombre' => 'Asignaturas', 'url' => 'asignaturas', 'icono' => null, 'permiso_slug' => 'listar-asignaturas', 'padre_id' => 3, 'orden' => 4]
        ];

        echo "    -> Insertando menús base en la tabla [{$tabla}]...\n";

        // Preparación de consultas para validar e insertar de forma segura
        $sqlCheck  = "SELECT id FROM `{$tabla}` WHERE id = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (id, nombre, url, icono, permiso_slug, padre_id, orden) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmtCheck  = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        if (!$stmtCheck || !$stmtInsert) {
            throw new \Exception("Error al preparar MenuSeeder: " . $mysqli->error);
        }

        $insertados = 0;

        foreach ($menus as $menu) {
            // Validamos si el menú ya existe por su ID maestro
            $stmtCheck->bind_param('i', $menu['id']);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo "       [-] El menú '{$menu['nombre']}' (ID: {$menu['id']}) ya existe. Omitido.\n";
                continue;
            }

            // Si no existe, se ejecuta la inserción limpia manejando los nulos correctamente
            $stmtInsert->bind_param(
                'isssssi', 
                $menu['id'], 
                $menu['nombre'], 
                $menu['url'], 
                $menu['icono'], 
                $menu['permiso_slug'], 
                $menu['padre_id'], 
                $menu['orden']
            );
            $stmtInsert->execute();
            $insertados++;
        }

        $stmtCheck->close();
        $stmtInsert->close();

        if ($insertados > 0) {
            echo "\e[32m    ✅ Menús insertados correctamente ({$insertados} nuevos).\e[0m\n";
        } else {
            echo "\e[33m    ℹ️ Todos los menús ya se encontraban registrados.\e[0m\n";
        }
    }
}
