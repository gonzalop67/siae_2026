<?php 

namespace App\Controllers;

use Core\Encrypter;
use App\Models\Admin\Role;
use App\Models\Admin\Menu;
use App\Models\Admin\Usuario;
use App\Models\Admin\UsuarioRol;

class AuthController extends Controller {
    
    protected Role $roleModel;
    protected Menu $menuModel;
    protected Usuario $usuarioModel;
    protected UsuarioRol $usuarioRolModel;

    public function __construct() {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->roleModel = new Role;
        $this->menuModel = new Menu;
        $this->usuarioModel = new Usuario;
        $this->usuarioRolModel = new UsuarioRol;
    }

    // Show login form
    public function showLoginForm() {
        $roles = $this->roleModel->orderBy('nombre')->get();
        return $this->view('auth.login', compact('roles'));
    }

    public function login() {
        $username = $_POST['usuario'];
        $password = $_POST['clave'];
        $id_role = $_POST['role'];

        // Verify data login
        $clave = Encrypter::encrypt($password);
        $usuario = $this->usuarioModel
            ->where('username', $username)
            ->where('password', $clave)
            ->first();

        if (!empty($usuario)) {
            // Verificar si el perfil ingresado pertenece al usuario
            $id_usuario = $usuario['id'];
            $personaId  = $usuario['persona_id']; // Recuperamos el persona_id maestro

            $usuarioRole = $this->usuarioRolModel
                ->where('usuario_id', $id_usuario)
                ->where('rol_id', $id_role)
                ->first();

            if (!empty($usuarioRole)) {
                // ASEGÚRATE DE QUE session_start() se ejecutó antes
                if (session_status() === PHP_SESSION_NONE) session_start();
                session_regenerate_id(true);

                $_SESSION['authenticated'] = true;
                $_SESSION['us_avatar']     = $usuario['avatar'];
                $_SESSION['usuario_id']    = $id_usuario;
                $_SESSION['persona_id']    = $personaId;

                // Consultar el role asociado para identificar el contexto
                $role = $this->roleModel->where('id', $id_role)->first();
                $roleSlug = $role['slug'];

                // 🚀 RESOLUCIÓN DINÁMICA DE ENTIDAD ACADÉMICA (NUEVO)
                $_SESSION['rol_slug'] = $roleSlug;
                $_SESSION['rol_especifico_id'] = null;

                // Conexión nativa MySQLi compartida por tu framework
                $db = $this->usuarioModel->getConnection(); 

                if ($roleSlug === 'estudiante') {
                    $stmt = $db->prepare("SELECT id FROM alumnos WHERE persona_id = ? LIMIT 1");
                    $stmt->bind_param('i', $personaId);
                    $stmt->execute();
                    $res = $stmt->get_result()->fetch_assoc();
                    $_SESSION['rol_especifico_id'] = $res ? (int)$res['id'] : null;
                    $stmt->close();
                } elseif ($roleSlug === 'representante') {
                    $stmt = $db->prepare("SELECT id FROM representantes WHERE persona_id = ? LIMIT 1");
                    $stmt->bind_param('i', $personaId);
                    $stmt->execute();
                    $res = $stmt->get_result()->fetch_assoc();
                    $_SESSION['rol_especifico_id'] = $res ? (int)$res['id'] : null;
                    $stmt->close();
                }

                // 1. Consultar los menús planos autorizados mediante tu ORM
                $menusPlanos = $this->menuModel
                    ->select('menus.*')
                    ->join('permisos', 'menus.permiso_slug', '=', 'permisos.slug')
                    ->join('roles_permisos', 'permisos.id', '=', 'roles_permisos.permiso_id')
                    ->where('roles_permisos.rol_id', $id_role)
                    ->orderBy('menus.padre_id', 'ASC')
                    ->orderBy('menus.orden', 'ASC')
                    ->get();

                // 2. Processar el árbol jerárquico y guardarlo en la sesión
                $_SESSION['menu_dinamico'] = $this->menuModel->construirMenuDinamico($menusPlanos);

                return json_encode([
                    'error' => false,
                    'slug'  => $roleSlug,
                ]);

            } else {
                return json_encode([
                    'error' => true,
                    'errors' => [
                        'mensaje' => 'Usuario, contraseña o perfil incorrectos.'
                    ]
                ]);
            }
        } else {
            return json_encode([
                'error' => true,
                'errors' => [
                    'mensaje' => 'Usuario, contraseña o perfil incorrectos.'
                ]
            ]);
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = []; // Forma más segura de limpiar que session_unset()
        session_destroy(); // Borrar la cookie de sesión del navegador

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        redireccionar('/');
        exit();
    }
}
