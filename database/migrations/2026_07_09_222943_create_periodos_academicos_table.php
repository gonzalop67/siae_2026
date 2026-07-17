<?php

use App\Models\Model;

class CreatePeriodosAcademicosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // =========================================================================
        // 2. TABLA: periodos_academicos
        // Maneja las subdivisiones del año escolar (Trimestres o Bimestres)
        // =========================================================================
        $sql = "CREATE TABLE IF NOT EXISTS periodos_academicos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            -- Agrega tus columnas aquí
            periodo_lectivo_id INT NOT NULL,
            nombre VARCHAR(50) NOT NULL, -- Ej: Primer Trimestre, Segundo Trimestre
            tipo ENUM('trimestre', 'bimestre') NOT NULL DEFAULT 'trimestre',
            orden TINYINT NOT NULL, -- Control secuencial (1, 2, 3)
            fecha_inicio DATE NOT NULL,
            fecha_fin DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            FOREIGN KEY (periodo_lectivo_id) REFERENCES periodos_lectivos(id) ON DELETE RESTRICT,
            UNIQUE KEY unique_periodo_nombre (periodo_lectivo_id, nombre)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS periodos_academicos;";
        $this->connection->query($sql);
    }
}
