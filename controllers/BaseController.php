<?php
class BaseController {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Verificar si el usuario está logueado
    public function middlewareAuth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
    }

    // Verificar si tiene un permiso específico
    public function middlewarePermiso($permiso) {
        if (!isset($_SESSION['permisos']) || !in_array($permiso, $_SESSION['permisos'])) {
            // Si es una petición AJAX, devolvemos error 403
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(["error" => "No tienes permiso para realizar esta acción"]);
                exit();
            }
            // Si es carga normal, redirigir a una página de error
            header("Location: /error/403");
            exit();
        }
    }

    public function view($view, $data = [])
    {
        if (is_array($data)) {
            extract($data);
        }

        $filename = "views/" . $view . ".php";
        if (file_exists($filename)) {
            require $filename;
        }
    }

    // Método para cargar layout fácilmente
    public function layout($nombreVista, $data = []) {
        extract($data); // Convierte ['usuarios' => $res] en $usuarios
        require_once "views/layout/header.php";
        require_once "views/layout/sidebar.php";
        require_once "views/$nombreVista.php";
        require_once "views/layout/footer.php";
    }

    public function model($model)
    {
        $filename = "models/" . ucfirst($model) . ".php";

        if (file_exists($filename)) {
            require $filename;

            return new $model;
        }

        return false;
    }
}
