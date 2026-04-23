<?php

class AuthController extends BaseController
{

    private $rolModelo;
    private $usuarioModelo;
    private $institucionModelo;

    public function __construct()
    {
        parent::__construct();
        $this->rolModelo = $this->model('rol');
        $this->usuarioModelo = $this->model('usuario');
        $this->institucionModelo = $this->model('institucion');
    }

    // Muestra la vista de login de AdminLTE 4
    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /dashboard");
            exit();
        }

        $roles = $this->rolModelo->obtenerRoles();
        $institucion = $this->institucionModelo->obtenerInstitucion(1);

        $data = [
            'tituloPagina' => '| Login',
            'roles' => $roles,
            'institucion' => $institucion
        ];

        $this->view('auth/login', $data);
    }

    // Muestra la vista de register de AdminLTE4
    public function showViewRegister()
    {
        $institucion = $this->institucionModelo->obtenerInstitucion(1);

        $data = [
            'tituloPagina' => '| Register',
            'institucion' => $institucion
        ];

        $this->view('auth/register', $data);
    }

    // Procesa la petición AJAX del formulario
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Collect data POST
            $username = $_POST["usuario"];
            $password = $_POST["clave"];
            $id_perfil = $_POST["perfil"];

            $usuario = $this->usuarioModelo->buscarPorUsername($username);

            // echo json_encode($usuario);

            if ($usuario && password_verify($password, $usuario['password'])) {
                /*session_start();
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['nombre']  = $usuario['nombre'];

                // Cargamos los permisos del usuario usando sus múltiples roles
                $_SESSION['permisos'] = $this->usuarioModelo->obtenerPermisos($usuario['id']);*/

                echo json_encode(['status' => 'success', 'message' => 'Bienvenido']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Credenciales inválidas']);
            }
            exit();
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /login");
    }
}
