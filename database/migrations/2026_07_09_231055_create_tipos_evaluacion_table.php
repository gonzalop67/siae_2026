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
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            periodo_lectivo_id INT(11) NOT NULL, 
            macro_categoria ENUM('formativa', 'sumativa') NOT NULL, -- 70% o 30%
            
            -- 💡 NUEVA COLUMNA: Identifica a qué parcial corresponde la actividad formativa.
            -- Las actividades sumativas (exámenes finales) usualmente aplican para el periodo completo ('ninguno').
            `parcial` ENUM('parcial_1', 'parcial_2', 'ninguno') NOT NULL DEFAULT 'ninguno',
            
            nombre VARCHAR(100) NOT NULL, -- Ej: Actividades Individuales, Actividades Grupales
            ponderacion_macro DECIMAL(5,2) NOT NULL, -- 70.00 o 30.00
            descripcion TEXT NULL,
            
            CONSTRAINT fk_tipos_eval_periodo FOREIGN KEY (periodo_lectivo_id) REFERENCES periodos_lectivos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            
            -- 💡 CORRECCIÓN CRÍTICA: Se añade 'parcial' a la llave única para permitir registrar 
            -- Actividades Individuales tanto en el Parcial 1 como en el Parcial 2.
            UNIQUE KEY unique_periodo_macro_parcial_nombre (periodo_lectivo_id, macro_categoria, `parcial`, nombre)
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
