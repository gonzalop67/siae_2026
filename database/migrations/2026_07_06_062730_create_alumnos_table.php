<?php

use App\Models\Model;

class CreateAlumnosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // =========================================================================
        // TABLA: alumnos
        // Almacena los datos específicos del rol estudiantil vinculados a una persona.
        // =========================================================================
        $sql = "CREATE TABLE IF NOT EXISTS alumnos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            -- Agrega tus columnas aquí
            persona_id INT NOT NULL, -- Relación uno a uno con el catálogo maestro de personas
            -- Código institucional único (Ej: MAT-2026-0001) para búsquedas y reportes rápidos
            codigo_matricula VARCHAR(20) NOT NULL,
            -- Información médica/seguridad obligatoria para instituciones educativas
            tipo_sangre VARCHAR(5) NULL, -- Ej: O+, A-
            alergias TEXT NULL,
            discapacidad TINYINT(1) DEFAULT 0, -- 1: Sí, 0: No
            porcentaje_discapacidad DECIMAL(5,2) NULL, -- Ej: 35.50
            carnet_conadis VARCHAR(30) NULL,
            -- Otra información
            observaciones TEXT NULL,
            estado TINYINT(1) DEFAULT 1, -- 1: Activo, 0: Retirado/Inactivo
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            -- Restricciones de integridad y llaves foráneas
            UNIQUE KEY unique_persona_alumno (persona_id), -- Una persona solo puede ser un alumno activo
            UNIQUE KEY unique_codigo_matricula (codigo_matricula), -- No puede haber códigos duplicados
            FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS alumnos;";
        $this->connection->query($sql);
    }
}
