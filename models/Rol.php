<?php
class Rol extends BaseModel
{
    // $this->db ya está disponible gracias al padre

    public function obtenerRoles()
    {
        $sql = "SELECT * FROM roles ORDER BY nombre_rol ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }
}