<?php

use App\Models\Model;

class CreateNacionalidadesTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS nacionalidades (
            id INT AUTO_INCREMENT PRIMARY KEY,
            -- Agrega tus columnas aquí
            `nombre` VARCHAR(50) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS nacionalidades;";
        $this->connection->query($sql);
    }
}
