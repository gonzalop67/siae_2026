<?php

use App\Models\Model;

class CreateParalelosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS paralelos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(5) NOT NULL -- Ej: A, B, C, D
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS paralelos;";
        $this->connection->query($sql);
    }
}
