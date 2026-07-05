<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use App\Models\Admin\Menu;
use App\Models\Admin\Role;
use App\Models\Admin\Permiso;

class MenuController extends Controller
{
    protected Menu $menuModel;
    protected Role $roleModel;
    protected Permiso $permisoModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->menuModel = new Menu;
        $this->roleModel = new Role;
        $this->permisoModel = new Permiso;
    }

    public function index()
    {
        $title = 'Listado de Menús';

        // 1. Cargar roles activos (manejando el borrado lógico manualmente en el string)
        $this->roleModel->where = "roles.deleted_at IS NULL";
        $roles = $this->roleModel->orderBy('nombre')->get();

        // 2. Cargar menús padres para el selector
        $this->menuModel->where = "menus.padre_id IS NULL AND menus.deleted_at IS NULL";
        $menus_principales = $this->menuModel->orderBy('nombre')->get();

        // 3. Cargar todos los permisos activos
        $this->permisoModel->where = "permisos.deleted_at IS NULL";
        $permisos_disponibles = $this->permisoModel->orderBy('nombre')->get();

        return $this->view('admin.menus.index', compact(
            'title',
            'roles',
            'menus_principales',
            'permisos_disponibles'
        ));
    }

    public function store()
    {
        $nombre       = trim($_POST['nombre'] ?? '');
        $url          = trim($_POST['url'] ?? '');
        $icono        = !empty($_POST['icono']) ? trim($_POST['icono']) : null;
        $padre_id     = !empty($_POST['padre_id']) ? (int)$_POST['padre_id'] : null;
        $permiso_slug = !empty($_POST['permiso_slug']) ? trim($_POST['permiso_slug']) : null;

        $errores = [];
        if (empty($nombre)) $errores['nombre'] = 'El campo texto / nombre es obligatorio.';
        if (empty($url))    $errores['url'] = 'El campo enlace / URL es obligatorio.';

        if (!empty($errores)) {
            header('HTTP/1.1 422 Unprocessable Entity');
            header('Content-Type: application/json');
            echo json_encode(['errors' => $errores]);
            exit;
        }

        $data = [
            'nombre'       => $nombre,
            'url'          => $url,
            'icono'        => $icono,
            'permiso_slug' => $permiso_slug,
            'padre_id'     => $padre_id,
            'orden'        => 0
        ];

        // Llamamos a tu método nativo 'create'
        $nuevoMenu = $this->menuModel->create($data);

        // Validamos que se haya retornado el registro correctamente desde la base de datos
        if ($nuevoMenu && isset($nuevoMenu['id'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'El menú se registró correctamente en el sistema.',
                // Usamos el ID devuelto directamente por tu método find() interno
                'nuevo_padre' => ($padre_id === null) ? ['id' => $nuevoMenu['id'], 'nombre' => $nuevoMenu['nombre']] : null
            ]);
            exit;
        }

        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'No se pudo completar el registro en la base de datos.']);
        exit;
    }

    /**
     * Endpoint para obtener el JSON de un menú específico (Carga del modal)
     */
    public function edit(int $id)
    {
        header('Content-Type: application/json');

        // Usamos el constructor nativo de tu modelo base (find maneja softdeletes)
        $menu = $this->menuModel->find($id);

        if ($menu) {
            echo json_encode($menu);
        } else {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Menú no encontrado.']);
        }
        exit;
    }

    public function update()
    {
        // Capturamos el ID del registro a actualizar (obligatorio)
        $id = !empty($_POST['id_update']) ? (int)$_POST['id_update'] : null;

        $nombre       = trim($_POST['nombre'] ?? '');
        $url          = trim($_POST['url'] ?? '');
        $icono        = !empty($_POST['icono']) ? trim($_POST['icono']) : null;
        $permiso_slug = !empty($_POST['permiso_slug']) ? trim($_POST['permiso_slug']) : null;

        $errores = [];

        // Validaciones obligatorias
        if (empty($id))     $errores['id_update'] = 'El identificador del menú es obligatorio.';
        if (empty($nombre)) $errores['nombre']    = 'El campo texto / nombre es obligatorio.';
        if (empty($url))    $errores['url']       = 'El campo enlace / URL es obligatorio.';

        if (!empty($errores)) {
            header('HTTP/1.1 422 Unprocessable Entity');
            header('Content-Type: application/json');
            echo json_encode(['errors' => $errores]);
            exit;
        }

        // Estructuramos la data excluyendo 'padre_id' y 'orden' según tu formulario HTML de edición
        $data = [
            'nombre'       => $nombre,
            'url'          => $url,
            'icono'        => $icono,
            'permiso_slug' => $permiso_slug
        ];

        // Ejecutamos la actualización en tu modelo nativo pasándole el ID y los datos
        $actualizado = $this->menuModel->update($id, $data);

        if ($actualizado) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'El menú se actualizó correctamente en el sistema.'
            ]);
            exit;
        }

        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode(['error' => 'No se pudieron guardar los cambios en la base de datos o no hubo modificaciones.']);
        exit;
    }

    public function delete(int $id)
    {
        if (empty($id)) {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Identificador de menú inválido o ausente.']);
            exit;
        }

        // Ejecuta la eliminación lógica inteligente (Soft Delete)
        $eliminado = $this->menuModel->delete($id);

        header('Content-Type: application/json');
        if ($eliminado) {
            echo json_encode([
                'success' => true,
                'message' => 'El menú ha sido eliminado correctamente del sistema.'
            ]);
            exit;
        }

        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'No se pudo completar la eliminación en la base de datos.']);
        exit;
    }

    public function papelera()
    {
        // Consulta SQL plana para extraer los registros con Soft Delete activo
        $sql = "SELECT id, nombre, url, icono, deleted_at 
            FROM menus 
            WHERE deleted_at IS NOT NULL 
            ORDER BY deleted_at DESC";

        // Ejecutamos la consulta preparada nativa sin parámetros de entrada
        $this->menuModel->query($sql, []);

        $rows = [];
        // Recuperamos de forma segura el objeto mysqli_result mediante tu método público
        $queryResult = $this->menuModel->getQueryResult();

        if ($queryResult instanceof \mysqli_result) {
            $rows = $queryResult->fetch_all(MYSQLI_ASSOC);
        }

        // Retornamos la colección en formato JSON para que el modal pinte la tabla
        header('Content-Type: application/json');
        echo json_encode($rows);
        exit;
    }

    public function restore(int $id)
    {
        if (empty($id)) {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Identificador de menú inválido o ausente.']);
            exit;
        }

        // Invoca al método restore del modelo base
        $restaurado = $this->menuModel->restore($id);

        header('Content-Type: application/json');
        if ($restaurado) {
            echo json_encode([
                'success' => true,
                'message' => 'El menú ha sido restaurado y activado correctamente.'
            ]);
            exit;
        }

        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'No se pudo completar la restauración en la base de datos.']);
        exit;
    }

    // Método para eliminación física definitiva (Botón Rojo)
    public function destroy(int $id)
    {
        header('Content-Type: application/json');

        try {
            // 1. Buscamos el menú en la base de datos incluyendo eliminados lógicamente
            $menu = $this->menuModel->withTrashed()->find($id);

            if (!$menu) {
                echo json_encode([
                    'success' => false,
                    'message' => 'El menú no existe en el sistema o ya fue purgado.'
                ]);
                exit;
            }

            // 2. Ejecutamos la eliminación física definitiva en la base de datos
            $resultado = $this->menuModel->forceDelete($id);

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'El menú ha sido eliminado permanentemente del sistema.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se pudo eliminar el registro de la base de datos.'
                ]);
            }
        } catch (\mysqli_sql_exception $e) {
            // CAPTURA EXITOSA: el catch atrapa el error 1451 (Claves foráneas) perfectamente
            if ($e->getCode() === 1451) {
                echo json_encode([
                    'success' => false,
                    'message' => 'El menú tiene submenús vinculados o dependencias en roles. Reasigne o borre esas dependencias antes de eliminarlo definitivamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Fallo en la consulta de base de datos: ' . $e->getMessage()
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Ocurrió un error inesperado: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public function get_menu_ajax()
    {
        $id_rol = isset($_POST['perfil_id']) ? (int)$_POST['perfil_id'] : 0;

        if ($id_rol === 0) {
            echo '<div id="nestable-placeholder"><div class="text-muted text-center py-4">Rol no válido.</div></div>';
            exit;
        }

        // SQL CORREGIDO: Se eliminó el comentario '--' que rompía la sintaxis en la ejecución de PHP
        $sql = "SELECT DISTINCT m.* FROM `menus` m
            INNER JOIN `permisos` p ON m.permiso_slug = p.slug
            INNER JOIN `roles_permisos` rp ON p.id = rp.permiso_id
            WHERE rp.rol_id = ? AND m.deleted_at IS NULL AND p.deleted_at IS NULL
            
            UNION
            
            SELECT m.* FROM `menus` m 
            WHERE m.permiso_slug IS NULL AND m.deleted_at IS NULL
            
            ORDER BY padre_id ASC, orden ASC";

        // Se ejecuta pasándole el parámetro único del id_rol
        $this->menuModel->query($sql, [$id_rol], 'i');

        $rows = [];
        // Usamos el nuevo método público en lugar de la propiedad protegida
        $queryResult = $this->menuModel->getQueryResult();

        if ($queryResult instanceof \mysqli_result) {
            $rows = $queryResult->fetch_all(MYSQLI_ASSOC);
        }

        if (empty($rows)) {
            echo '<div class="dd-empty text-muted text-center py-4">Este rol no tiene menús asignados.</div>';
            exit;
        }

        // 2. Construcción del Árbol Jerárquico
        $menuTree = [];
        $submenus = [];
        foreach ($rows as $row) {
            if ($row['padre_id'] === null || (int)$row['padre_id'] === 0) {
                $row['submenu'] = [];
                $menuTree[$row['id']] = $row;
            } else {
                $submenus[] = $row;
            }
        }

        foreach ($submenus as $sub) {
            $padreId = $sub['padre_id'];
            if (isset($menuTree[$padreId])) {
                $menuTree[$padreId]['submenu'][] = $sub;
            } else {
                $asignado = false;
                foreach ($menuTree as &$padreRaiz) {
                    if ($this->insertarEnHijo($padreRaiz, $sub)) {
                        $asignado = true;
                        break;
                    }
                }
                if (!$asignado) {
                    $sub['submenu'] = [];
                    $menuTree[$sub['id']] = $sub;
                }
            }
        }

        // 3. Renderizar y retornar el HTML directo
        echo $this->renderNestableTree(array_values($menuTree));
        exit;
    }

    /**
     * NUEVA FUNCIÓN AUXILIAR: Inserta submenús de forma recursiva en n-niveles (Hijos, nietos, etc.)
     */
    private function insertarEnHijo(&$padre, $sub)
    {
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

    private function renderNestableTree(array $menus)
    {
        if (empty($menus)) return '';

        $html = '<ol class="dd-list">';
        foreach ($menus as $menu) {
            $hasChildren = !empty($menu['submenu']);

            $html .= '<li class="dd-item dd3-item" data-id="' . $menu["id"] . '">';
            $html .= '  <div class="dd-handle dd3-handle"></div>';
            $html .= '  <div class="dd3-content menu_link">';

            // Icono dinámico consolidado
            $iconoHtml = !empty($menu['icono']) ? '<i class="' . htmlspecialchars($menu['icono']) . ' mr-2"></i> ' : '';

            // CORRECCIÓN CRÍTICA: Se eliminan data-toggle y data-target. El modal se abrirá estrictamente en el success de obtenerDatos()
            $html .= '    <a href="#" onclick="obtenerDatos(' . $menu["id"] . '); return false;">' . $iconoHtml . htmlspecialchars($menu["nombre"]) . '</a>';
            $html .= '    <a href="' . RUTA_URL . '/menus/delete/' . $menu["id"] . '" class="eliminar-menu float-right" title="Eliminar este menú"><i class="text-danger fas fa-trash-alt"></i></a>';
            $html .= '  </div>';

            // RECURSIÓN REAL: Sigue procesando infinitos niveles (Hijos, Nietos, etc.)
            if ($hasChildren) {
                $html .= $this->renderNestableTree($menu['submenu']);
            }

            $html .= '</li>';
        }
        $html .= '</ol>';

        return $html;
    }

    public function guardar_orden_ajax()
    {
        // Aseguramos que la respuesta siempre viaje como JSON hacia tu Swal.fire
        header('Content-Type: application/json');

        if (isset($_POST['estructura'])) {
            $estructura = json_decode($_POST['estructura'], true);

            if (!empty($estructura)) {
                try {
                    // CORRECCIÓN CLAVE: Iniciamos la actualización desde la raíz enviando NULL en lugar de 0
                    $this->actualizarPosicionesRecursivo($estructura, null);

                    echo json_encode([
                        'ok' => true,
                        'mensaje' => 'El orden de los menús se actualizó correctamente.'
                    ]);
                } catch (\mysqli_sql_exception $e) {
                    header('HTTP/1.1 500 Internal Server Error');
                    echo json_encode([
                        'ok' => false,
                        'mensaje' => 'Error de base de datos: ' . $e->getMessage()
                    ]);
                }
            } else {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode([
                    'ok' => false,
                    'mensaje' => 'La estructura enviada está vacía.'
                ]);
            }
            exit;
        }
    }

    /**
     * Función auxiliar recursiva para actualizar jerarquía y orden en la BD
     */
    private function actualizarPosicionesRecursivo(array $items, ?int $padreId)
    {
        foreach ($items as $indice => $item) {
            $idMenu = (int)$item['id'];
            $nuevoOrden = $indice + 1; // El orden inicia en 1

            // 1. Ejecutamos la consulta usando los nombres reales de tus columnas ('padre_id', 'orden', 'id')
            $sql = "UPDATE `menus` SET `padre_id` = ?, `orden` = ? WHERE `id` = ?";

            // Usamos el motor de consultas preparado de tu clase Model base ('iii' representa 3 enteros)
            // Nota: mysqli maneja de forma nativa los valores null pasados en el array de datos
            $this->menuModel->query($sql, [$padreId, $nuevoOrden, $idMenu], 'iii');

            // 2. Si este elemento tiene hijos (submenús), procesarlos recursivamente pasando el ID del padre actual
            if (isset($item['children']) && !empty($item['children'])) {
                $this->actualizarPosicionesRecursivo($item['children'], $idMenu);
            }
        }
    }
}
