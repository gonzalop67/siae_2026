<?php
class Usuario extends BaseModel
{

    // $this->db ya está disponible gracias al padre
    /*public function obtenerConRoles()
    {
        $query = "SELECT u.id, u.nombre, u.email, 
                  GROUP_CONCAT(r.nombre_rol SEPARATOR ', ') AS roles_asignados
                  FROM usuarios u
                  LEFT JOIN usuario_roles ur ON u.id = ur.usuario_id
                  LEFT JOIN roles r ON ur.rol_id = r.id
                  GROUP BY u.id";
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }*/

    public function registrar($nombre, $email, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $hash]);

        $lastId = $this->db->lastInsertId();

        $stmt = $this->db->prepare("INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (?, ?)");
        return $stmt->execute([$lastId, 1]);
    }

    public function buscarPorUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPermisos($usuario_id)
    {
        $sql = "SELECT DISTINCT p.nombre_permiso 
            FROM permisos p
            INNER JOIN rol_permisos rp ON p.id = rp.permission_id
            INNER JOIN usuario_roles ur ON rp.role_id = ur.rol_id
            WHERE ur.usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
