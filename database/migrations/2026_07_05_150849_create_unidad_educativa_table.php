<?php

use App\Models\Model;

class CreateUnidadEducativaTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS unidad_educativa (
            id INT AUTO_INCREMENT PRIMARY KEY,
            -- Agrega tus columnas aquí
            admin_id INT NULL,
            nombre VARCHAR(64),
            direccion VARCHAR(100),
            telefono VARCHAR(32),
            regimen VARCHAR(45),
            nombre_rector VARCHAR(45),
            genero_rector ENUM('Femenino', 'Masculino') DEFAULT 'Masculino',
            nombre_vicerrector VARCHAR(45) NULL,
            genero_vicerrector ENUM('Femenino', 'Masculino') DEFAULT 'Masculino',
            nombre_secretario VARCHAR(45) NULL,
            genero_secretario ENUM('Femenino', 'Masculino') DEFAULT 'Masculino',
            email VARCHAR(64) NULL,
            url VARCHAR(64) NULL,
            logo VARCHAR(64) NULL,
            amie VARCHAR(16) NULL,
            ciudad VARCHAR(64) NULL,
            copiar_y_pegar TINYINT(1) UNSIGNED DEFAULT 1,
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
        $sql = "DROP TABLE IF EXISTS unidad_educativa;";
        $this->connection->query($sql);
    }
}
