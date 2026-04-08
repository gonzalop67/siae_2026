<?php
require_once 'models/Usuario.php';
require_once 'config/Database.php';

class AuthController extends BaseController {

    private $institucionModelo;

    public function __construct()
    {
        parent::__construct();
        $this->institucionModelo = $this->model('institucion');
    }

    // Muestra la vista de login de AdminLTE 4
    public function index() {
        if (isset($_SESSION['user_id'])) {
            header("Location: /dashboard");
            exit();
        }

        $institucion = $this->institucionModelo->obtenerInstitucion(1);

        $data = [
            'tituloPagina' => '| Login',
            'institucion' => $institucion
        ];

        $this->view('auth/login', $data);
    }

    // Procesa la petición AJAX del formulario
    /*public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = (new Database())->getConnection();
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new Usuario($db);
            $usuario = $userModel->buscarPorEmail($email);

            if ($usuario && password_verify($password, $usuario['password'])) {
                session_start();
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['nombre']  = $usuario['nombre'];
                
                // Cargamos los permisos del usuario usando sus múltiples roles
                $_SESSION['permisos'] = $userModel->obtenerPermisos($usuario['id']);

                echo json_encode(['status' => 'success', 'message' => 'Bienvenido']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Credenciales inválidas']);
            }
            exit();
        }
    }*/

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /login");
    }
}
