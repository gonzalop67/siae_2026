<?php

use App\Models\Model;

class CreateTiposEvaluacionTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // =========================================================================
        // 3. TABLA: tipos_evaluacion (Catálogo Maestro)
        // Clasificación estricta del modelo MINEDUC: Formativa (70%) y Sumativa (30%)
        // =========================================================================
        $sql = "CREATE TABLE IF NOT EXISTS tipos_evaluacion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            periodo_lectivo_id INT NOT NULL, -- Enlace obligatorio al año escolar histórico
            macro_categoria ENUM('formativa', 'sumativa') NOT NULL, -- 70% o 30%
            nombre VARCHAR(100) NOT NULL, -- Ej: Aporte Individual, Proyecto Interdisciplinar, etc.
            ponderacion_macro DECIMAL(5,2) NOT NULL, -- 70.00 o 30.00
            descripcion TEXT NULL,
            FOREIGN KEY (periodo_lectivo_id) REFERENCES periodos_lectivos(id) ON DELETE RESTRICT,
            UNIQUE KEY unique_periodo_macro_nombre (periodo_lectivo_id, macro_categoria, nombre)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS tipos_evaluacion;";
        $this->connection->query($sql);
    }
}
