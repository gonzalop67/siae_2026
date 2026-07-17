<?php

use App\Models\Model;

class CreateParcialesEvaluacionTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS parciales_evaluacion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            periodo_academico_id INT NOT NULL,
            nombre VARCHAR(50) NOT NULL, -- Ej: Parcial 1, Examen Quimestral, Bloque 1
            orden TINYINT NOT NULL, -- Control secuencial (1, 2, 3) dentro del periodo académico
            peso_nota DECIMAL(5, 2) NOT NULL DEFAULT 100.00, -- Porcentaje de valor sobre la nota final (ej: 20.00 para 20%)
            fecha_inicio DATE NOT NULL,
            fecha_fin DATE NOT NULL,
            fecha_cierre_notas DATETIME NOT NULL, -- Fecha límite para que los docentes ingresen calificaciones
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,

            FOREIGN KEY (periodo_academico_id) REFERENCES periodos_academicos(id) ON DELETE RESTRICT,
            UNIQUE KEY uq_periodo_academico_orden (periodo_academico_id, orden, deleted_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        if (!$this->connection->query($sql)) {
            throw new \mysqli_sql_exception("Error al estructurar parciales_evaluacion: " . $this->connection->error);
        }
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS parciales_evaluacion;";
        $this->connection->query($sql);
    }
}
