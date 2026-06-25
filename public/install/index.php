<?php
session_start();

$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host   = trim($_POST['host'] ?? 'localhost');
    $user   = trim($_POST['user'] ?? '');
    $pass   = trim($_POST['pass'] ?? '');
    $name   = trim($_POST['name'] ?? '');
    $app    = trim($_POST['app'] ?? 'SIAE 2025');

    // 1. Validar conexión a la Base de Datos antes de continuar
    mysqli_report(MYSQLI_REPORT_OFF);
    $mysqli = @new mysqli($host, $user, $pass);

    if ($mysqli->connect_error) {
        $error = "Error de conexión: " . $mysqli->connect_error;
    } else {
        // 2. Intentar crear la base de datos si no existe
        $mysqli->query("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;");
        
        if (!$mysqli->select_db($name)) {
            $error = "No se pudo seleccionar ni crear la base de datos: " . $mysqli->error;
        } else {
            // 3. Escribir el archivo config.php real en App/config/
            $stubPath = __DIR__ . '/template.stub';
            $configPath = dirname(dirname(__DIR__)) . '/App/config/config.php';

            if (!file_exists($stubPath)) {
                $error = "Falta el archivo de plantilla template.stub";
            } else {
                $template = file_get_contents($stubPath);
                $compiled = str_replace(
                    ['{{DB_HOST}}', '{{DB_USER}}', '{{DB_PASS}}', '{{DB_NAME}}', '{{APP_NAME}}'],
                    [$host, $user, $pass, $name, $app],
                    $template
                );

                // Asegurar que la carpeta config exista
                if (!is_dir(dirname($configPath))) {
                    mkdir(dirname($configPath), 0755, true);
                }

                if (!@file_put_contents($configPath, $compiled)) {
                    $error = "Error: No se tienen permisos de escritura para crear el archivo en App/config/config.php";
                } else {
                    // 4. EJECUTAR LAS MIGRACIONES AUTOMÁTICAMENTE
                    // Reutilizamos el comando que construimos para tu CLI ejecutándolo mediante PHP
                    $raizProyecto = dirname(dirname(__DIR__));
                    
                    // Ejecutamos tu script craft de manera interna simulando la consola
                    ob_start();
                    $argv = ['craft', 'migrate'];
                    require_once $raizProyecto . '/craft';
                    ob_get_clean();

                    $success = true;
                }
            }
        }
        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalador de Framework</title>
    <link rel="stylesheet" href="https://jsdelivr.net">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Asistente de Instalación</h5>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <h5>¡Instalación Exitosa!</h5>
                            <p>El archivo de configuración ha sido creado y las tablas de la base de datos se migraron correctamente.</p>
                            <hr>
                            <p class="text-danger small"><strong>¡Importante!:</strong> Por seguridad, elimina la carpeta <code>public/install/</code> de tu servidor.</p>
                            <a href="../" class="btn btn-success btn-block">Ir a la Aplicación</a>
                        </div>
                    <?php else: ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Nombre del Sistema</label>
                                <input type="text" name="app" class="form-control" value="SIAE 2025" required>
                            </div>
                            <h5 class="mt-4 text-secondary">Configuración de Base de Datos</h5>
                            <hr>
                            <div class="form-group">
                                <label>Servidor (Host)</label>
                                <input type="text" name="host" class="form-control" value="localhost" required>
                            </div>
                            <div class="form-group">
                                <label>Usuario</label>
                                <input type="text" name="user" class="form-control" placeholder="Ej: root" required>
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="pass" class="form-control" placeholder="Clave de base de datos">
                            </div>
                            <div class="form-group">
                                <label>Nombre de la Base de Datos</label>
                                <input type="text" name="name" class="form-control" placeholder="Ej: siae_db" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Iniciar Instalación</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
