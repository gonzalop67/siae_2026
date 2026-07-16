<?php

use App\Models\Model;

class CreateAsignaturasTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // =========================================================================
        // 1. TABLA: asignaturas (Catálogo Maestro General)
        // Almacena el nombre base de la materia. Es independiente del año escolar.
        // =========================================================================
        $sql = "CREATE TABLE IF NOT EXISTS asignaturas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            area_id INT(11) NOT NULL,
            nombre VARCHAR(100) NOT NULL, -- Ej: Matemáticas, Lengua y Literatura, Física
            codigo VARCHAR(20) NOT NULL,  -- Ej: MAT-01, LEN-01
            estado TINYINT(1) DEFAULT 1,  -- 1: Activa, 0: Inactiva
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            UNIQUE KEY unique_codigo_asignatura (codigo),
            -- Clave foránea con alias explícito y cascada de actualización
            CONSTRAINT fk_asignaturas_area FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE RESTRICT ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS asignaturas;";
        $this->connection->query($sql);
    }
}
