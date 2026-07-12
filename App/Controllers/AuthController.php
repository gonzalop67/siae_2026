<?php

namespace App\Controllers;

use Core\Encrypter;

use App\Models\Admin\Role;
use App\Models\Admin\Menu;
use App\Models\Admin\Usuario;
use App\Models\Admin\UsuarioRol;

class AuthController extends Controller
{
    protected Role $roleModel;
    protected Menu $menuModel;
    protected Usuario $usuarioModel;
    protected UsuarioRol $usuarioRolModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->roleModel = new Role;
        $this->menuModel = new Menu;
        $this->usuarioModel = new Usuario;
        $this->usuarioRolModel = new UsuarioRol;
    }

    // Show login form
    public function showLoginForm()
    {
        $roles = $this->roleModel->orderBy('nombre')->get();
        return $this->view('auth.login', compact('roles'));
    }

    public function login()
    {
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
            $usuarioRole = $this->usuarioRolModel
                ->where('usuario_id', $id_usuario)
                ->where('rol_id', $id_role)
                ->first();
            if (!empty($usuarioRole)) {
                // ASEGÚRATE DE QUE session_start() se ejecutó antes
                if (session_status() === PHP_SESSION_NONE) session_start();
                session_regenerate_id(true);

                $_SESSION['authenticated'] = true;
                $_SESSION['us_avatar'] = $usuario['avatar'];

                // 1. Consultar los menús planos autorizados mediante tu ORM
                $menusPlanos = $this->menuModel
                    ->select('menus.*')
                    ->join('permisos', 'menus.permiso_slug', '=', 'permisos.slug')
                    ->join('roles_permisos', 'permisos.id', '=', 'roles_permisos.permiso_id')
                    ->where('roles_permisos.rol_id', $id_role)
                    ->orderBy('menus.padre_id', 'ASC')
                    ->orderBy('menus.orden', 'ASC')
                    ->get();

                // 2. Procesar el árbol jerárquico y guardarlo en la sesión
                $_SESSION['menu_dinamico'] = $this->menuModel->construirMenuDinamico($menusPlanos);

                // Consultar el role asociado
                $role = $this->roleModel->where('id', $id_role)->first();
                return json_encode([
                    'error' => false,
                    'slug' => $role['slug'],
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

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = []; // Forma más segura de limpiar que session_unset()
        session_destroy();

        // Borrar la cookie de sesión del navegador (esto evita el "limbo" al reingresar)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        redireccionar('/');
        exit();
    }
}
