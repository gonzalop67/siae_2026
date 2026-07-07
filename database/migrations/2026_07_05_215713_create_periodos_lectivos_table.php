<?php

use App\Models\Model;

class CreatePeriodosLectivosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // =========================================================================
        // 1. TABLA: periodos_lectivos
        // Maneja los años escolares globales (Ej: 2025-2026, 2026-2027)
        // =========================================================================
        $sql = "CREATE TABLE IF NOT EXISTS periodos_lectivos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            -- Agrega tus columnas aquí
            nombre VARCHAR(50) NOT NULL, -- Ej: Ciclo Sierra 2025-2026
            fecha_inicio DATE NOT NULL,
            fecha_fin DATE NOT NULL,
            estado TINYINT(1) DEFAULT 1, -- 1: Activo, 0: Inactivo
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
        $sql = "DROP TABLE IF EXISTS periodos_lectivos;";
        $this->connection->query($sql);
    }
}
