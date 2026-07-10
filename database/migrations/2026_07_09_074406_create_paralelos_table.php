<?php

use App\Models\Model;

class CreateParalelosTable extends Model
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        // 1. Catálogo Maestro de Paralelos Estáticos
        $sqlCat = "CREATE TABLE IF NOT EXISTS paralelos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(5) NOT NULL UNIQUE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        
        $this->connection->query($sqlCat);
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        $this->connection->query("DROP TABLE IF EXISTS paralelos;");
    }
}
