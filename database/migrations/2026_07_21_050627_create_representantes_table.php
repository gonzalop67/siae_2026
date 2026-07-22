<?php

use App\Models\Model;

class CreateRepresentantesTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS representantes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            persona_id INT NOT NULL,
            ocupacion VARCHAR(100) NULL,
            lugar_trabajo VARCHAR(150) NULL,
            ingreso_mensual DECIMAL(10,2) NULL,
            estado TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            UNIQUE KEY unique_persona_representante (persona_id),
            FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE RESTRICT ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS representantes;";
        $this->connection->query($sql);
    }
}
