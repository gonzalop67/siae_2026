<?php

use App\Models\Model;

class CreateAreasTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // =========================================================================
        // 1. TABLA: areas (Catálogo Maestro General)
        // Almacena el nombre base del área de conocimiento. Es independiente del año escolar.
        // =========================================================================
        $sql = "CREATE TABLE IF NOT EXISTS areas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL, -- Ej: Matemáticas, Lengua y Literatura, Ciencias Naturales
            estado TINYINT(1) DEFAULT 1,  -- 1: Activa, 0: Inactiva
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS areas;";
        $this->connection->query($sql);
    }
}
