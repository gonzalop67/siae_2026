<?php
class Usuario
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function obtenerConRoles()
    {
        $query = "SELECT u.id, u.nombre, u.email, 
                  GROUP_CONCAT(r.nombre_rol SEPARATOR ', ') AS roles_asignados
                  FROM usuarios u
                  LEFT JOIN usuario_roles ur ON u.id = ur.usuario_id
                  LEFT JOIN roles r ON ur.rol_id = r.id
                  GROUP BY u.id";
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($nombre, $email, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $email, $hash]);
    }
}
