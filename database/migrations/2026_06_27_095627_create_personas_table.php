<?php

use App\Models\Model;

class CreatePersonasTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS personas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            `tipo_documento_id` INT NOT NULL DEFAULT 1,
            `nacionalidad_id` INT NOT NULL DEFAULT 1,
            `dni` VARCHAR(20) NOT NULL UNIQUE,
            `primer_nombre` VARCHAR(32) NOT NULL,
            `segundo_nombre` VARCHAR(32) DEFAULT NULL,
            `primer_apellido` VARCHAR(32) NOT NULL,
            `segundo_apellido` VARCHAR(32) DEFAULT NULL,
            `nombre_corto` VARCHAR(64) DEFAULT NULL,    -- ◄ ¡ASEGÚRATE DE QUE ESTÉ AQUÍ!
            `nombre_completo` VARCHAR(128) DEFAULT NULL, -- ◄ ¡ASEGÚRATE DE QUE ESTÉ AQUÍ!
            `titulo` VARCHAR(16) DEFAULT NULL,
            `descripcion_titulo` VARCHAR(96) DEFAULT NULL,
            `genero` ENUM('Femenino', 'Masculino', 'Otro') NOT NULL,
            `fecha_nacimiento` DATE DEFAULT NULL,
            `email` VARCHAR(100) DEFAULT NULL UNIQUE,
            `telefono` VARCHAR(32) DEFAULT NULL,
            `direccion` VARCHAR(255) DEFAULT NULL,
            `sector` VARCHAR(64) DEFAULT NULL,
            `estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipo_documento`(`id`),
            FOREIGN KEY (`nacionalidad_id`) REFERENCES `nacionalidades`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS personas;";
        $this->connection->query($sql);
    }
}
