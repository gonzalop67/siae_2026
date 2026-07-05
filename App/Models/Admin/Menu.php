<?php

namespace App\Models\Admin;

use App\Models\Model;

class Menu extends Model
{
    protected string $table = 'menus';
    protected string $primaryKey = 'id';

    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'nombre',
        'url',
        'icono',
        'permiso_slug',
        'padre_id',
        'orden'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = true;

    public function getMenus(int $id_usuario)
    {
        // Consulta optimizada para consolidar múltiples roles del usuario y menús públicos
        $sql = "SELECT DISTINCT m.* FROM `menus` m
        INNER JOIN `permisos` p ON m.permiso_slug = p.slug
        INNER JOIN `roles_permisos` rp ON p.id = rp.permiso_id
        INNER JOIN `usuario_rol` ur ON rp.rol_id = ur.rol_id
        WHERE ur.usuario_id = ? 
          AND m.deleted_at IS NULL 
          AND p.deleted_at IS NULL

        UNION -- Unifica los menús que son públicos para todos los usuarios

        SELECT m.* FROM `menus` m 
        WHERE m.permiso_slug IS NULL 
          AND m.deleted_at IS NULL

        ORDER BY padre_id ASC, orden ASC";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $menuTree = [];
        $submenus = [];

        // 1. SEPARAR PADRES E HIJOS (Adaptado a tus nuevas columnas 'padre_id' e 'id')
        foreach ($rows as $row) {
            // En la base de datos, los padres directos se definen como NULL o 0
            if ($row['padre_id'] === null || (int)$row['padre_id'] === 0) {
                $row['submenu'] = [];
                $menuTree[$row['id']] = $row; // Mapeado a tu llave primaria 'id'
            } else {
                $submenus[] = $row;
            }
        }

        // 2. ASOCIACIÓN DE SUBMENÚS (Mantiene tu lógica recursiva adaptada a tus columnas)
        foreach ($submenus as $sub) {
            $padreId = $sub['padre_id']; // Adaptado a 'padre_id'

            if (isset($menuTree[$padreId])) {
                $menuTree[$padreId]['submenu'][] = $sub;
            } else {
                // Si es un submenú profundo (nieto), buscamos a su padre de manera recursiva
                $asignado = false;
                foreach ($menuTree as &$padreRaiz) {
                    if ($this->insertarEnHijo($padreRaiz, $sub)) {
                        $asignado = true;
                        break;
                    }
                }
                // Si el padre no existe en el perfil, se muestra en la raíz
                if (!$asignado) {
                    $sub['submenu'] = [];
                    $menuTree[$sub['id']] = $sub; // Adaptado a 'id'
                }
            }
        }

        return array_values($menuTree);
    }

    // Función auxiliar interna para soportar árboles de más de 2 niveles (Nietos)
    private function insertarEnHijo(&$padre, $sub)
    {
        // Cambiado: id_menu => id | mnu_padre => padre_id
        if (isset($padre['id']) && $padre['id'] == $sub['padre_id']) {
            $sub['submenu'] = [];
            $padre['submenu'][] = $sub;
            return true;
        }

        if (!empty($padre['submenu'])) {
            foreach ($padre['submenu'] as &$hijo) {
                if ($this->insertarEnHijo($hijo, $sub)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function actualizarOrdenYPadre(int $idMenu, int $padreId, int $orden)
    {
        // Ejemplo con PDO clásico:
        $sql = "UPDATE menus 
            SET padre_id = ?, orden = ? 
            WHERE id = ?";

        $stmt = $this->connection->prepare($sql);

        $stmt->bind_param('iii', $padreId, $orden, $idMenu);
        $stmt->execute();
        $stmt->close();
    }
}
