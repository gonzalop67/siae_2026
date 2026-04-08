<?php
class BaseModel {
    protected $db;

    public function __construct() {
        // Se conecta automáticamente al instanciar cualquier modelo hijo
        $database = new Database();
        $this->db = $database->getConnection();
    }
}
