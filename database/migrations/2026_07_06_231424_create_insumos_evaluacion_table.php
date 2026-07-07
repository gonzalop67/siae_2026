<?php

use App\Models\Model;

class CreateInsumosEvaluacionTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // =========================================================================
        // 4. TABLA: insumos_evaluacion
        // Cabecera de las actividades o tareas creadas por el docente en el aula
        // =========================================================================
        $sql = "CREATE TABLE IF NOT EXISTS insumos_evaluacion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            -- Agrega tus columnas aquí
            periodo_academico_id INT NOT NULL,
            tipo_evaluacion_id INT NOT NULL,
            malla_curricular_id INT NOT NULL, -- Enlace con tu tabla física de malla_curricular
            titulo VARCHAR(150) NOT NULL, -- Ej: Maqueta de Célula, Prueba de Álgebra
            fecha_actividad DATE NOT NULL,
            descripcion TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            FOREIGN KEY (periodo_academico_id) REFERENCES periodos_academicos(id) ON DELETE RESTRICT,
            FOREIGN KEY (tipo_evaluacion_id) REFERENCES tipos_evaluacion(id) ON DELETE RESTRICT,
            FOREIGN KEY (malla_curricular_id) REFERENCES malla_curricular(id) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS insumos_evaluacion;";
        $this->connection->query($sql);
    }
}
