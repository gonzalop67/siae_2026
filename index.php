<?php
// index.php
session_start();

// Carga automática de configuraciones y clases base
require_once 'config/config.php';
require_once 'config/Database.php';

spl_autoload_register(function ($clase) {
    if (file_exists("models/$clase.php")) {
        require_once "models/$clase.php";
    } elseif (file_exists("controllers/$clase.php")) {
        require_once "controllers/$clase.php";
    }
});

// index.php - El "Enrutador" (Router)
$url = isset($_GET['url']) ? $_GET['url'] : 'inicio';
$url = explode('/', rtrim($url, '/'));

// Ejemplo: si entran a /usuarios/editar/5
// $url[0] es el controlador (usuarios)
// $url[1] es el método (editar)
// $url[2] es el parámetro (5)

$controladorNombre = ucfirst($url[0]) . 'Controller'; // UsuariosController
$metodo = isset($url[1]) ? $url[1] : 'index';

// Lógica de carga del controlador
if (file_exists("controllers/$controladorNombre.php")) {
    require_once "controllers/$controladorNombre.php";
    $controller = new $controladorNombre();
    
    // Llamar al método pasando parámetros si existen
    if (method_exists($controller, $metodo)) {
        $parametro = isset($url[2]) ? $url[2] : null;
        $controller->{$metodo}($parametro);
    } else {
        echo "Error 404: Acción no encontrada.";
    }
} else {
    echo "Error 404: Controlador no encontrado.";
}
