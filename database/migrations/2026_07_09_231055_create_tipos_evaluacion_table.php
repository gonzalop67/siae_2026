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
        // TABLA: tipos_evaluacion (Catálogo Maestro Segmentado por Parciales)
        // Clasificación estricta del modelo MINEDUC: Formativa (70%) y Sumativa (30%)
        // =========================================================================
        $sql = "CREATE TABLE IF NOT EXISTS tipos_evaluacion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            periodo_lectivo_id INT NOT NULL,
            -- Vinculamos dinámicamente al parcial. NULL significa que aplica al periodo completo (como tu 'ninguno')
            parcial_evaluacion_id INT NULL, 
            macro_categoria ENUM('formativa', 'sumativa') NOT NULL, -- 70.00% o 30.00%
            nombre VARCHAR(100) NOT NULL, -- Ej: Actividades Individuales, Lecciones, Examen
            ponderacion_macro DECIMAL(5,2) NOT NULL, -- Ej: 70.00 o 30.00
            descripcion TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,

            -- Llaves foráneas
            CONSTRAINT fk_tipos_eval_periodo_lectivo FOREIGN KEY (periodo_lectivo_id) 
                REFERENCES periodos_lectivos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                
            CONSTRAINT fk_tipos_eval_parcial FOREIGN KEY (parcial_evaluacion_id) 
                REFERENCES parciales_evaluacion(id) ON DELETE RESTRICT ON UPDATE CASCADE,

            -- Llave única corregida para evitar duplicar el mismo insumo en el mismo parcial/año
            UNIQUE KEY uq_periodo_macro_parcial_nombre (periodo_lectivo_id, parcial_evaluacion_id, macro_categoria, nombre, deleted_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        if (!$this->connection->query($sql)) {
            throw new \mysqli_sql_exception("Error al estructurar tipos_evaluacion: " . $this->connection->error);
        }
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
