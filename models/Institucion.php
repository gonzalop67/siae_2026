<?php
class Institucion extends BaseModel
{
    // $this->db ya está disponible gracias al padre

    public function obtenerInstitucion($id)
    {
        $sql = "SELECT * FROM instituciones WHERE id = $id";
        return $this->db->query($sql)->fetch(PDO::FETCH_OBJ);
    }

    public function obtenerNombreInstitucion($id)
    {
        $sql = "SELECT nombre FROM instituciones WHERE id = $id";
        return $this->db->query($sql)->fetch(PDO::FETCH_OBJ)->nombre;
    }
}
