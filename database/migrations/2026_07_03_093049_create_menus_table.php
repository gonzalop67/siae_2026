<?php

use App\Models\Model;

class CreateMenusTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS menus (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(50) NOT NULL,
                url VARCHAR(100) NOT NULL,
                icono VARCHAR(50) NULL DEFAULT NULL,
                permiso_slug VARCHAR(50) NULL, -- Se alinea con tu campo 'slug'
                padre_id INT NULL,
                orden INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP NULL, -- También añadimos Soft Delete aquí
                FOREIGN KEY (permiso_slug) REFERENCES permisos(slug) ON DELETE SET NULL ON UPDATE CASCADE,
                FOREIGN KEY (padre_id) REFERENCES menus(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS menus;";
        $this->connection->query($sql);
    }
}
