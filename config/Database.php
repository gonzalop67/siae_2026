<?php
class Database {
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Configuramos PDO con soporte para caracteres UTF-8
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NOMBRE, 
                DB_USUARIO, 
                DB_PASSWORD,
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
            );
            // Habilitar el manejo de errores por excepciones
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
