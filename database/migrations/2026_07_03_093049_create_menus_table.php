<?php

use App\Models\Model;

class CreateMenusTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // 1. Limpieza preventiva y desactivación temporal de restricciones
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->connection->query("DROP TABLE IF EXISTS menus;");

        // 2. Creación estructural homogénea con INT(11) y longitud de VARCHAR alineada
        $sql = "CREATE TABLE menus (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(50) NOT NULL,
            url VARCHAR(100) NOT NULL,
            icono VARCHAR(50) NULL DEFAULT NULL,
            
            -- 💡 ALINEACIÓN: Asegurar que mida lo mismo que en la tabla 'permisos' (usualmente 50 o 100)
            permiso_slug VARCHAR(100) NULL DEFAULT NULL, 
            
            padre_id INT(11) NULL DEFAULT NULL,
            orden INT(11) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            
            -- Restricciones de Claves Foráneas con alias únicos
            CONSTRAINT fk_menus_permiso FOREIGN KEY (permiso_slug) REFERENCES permisos(slug) ON DELETE SET NULL ON UPDATE CASCADE,
            CONSTRAINT fk_menus_padre FOREIGN KEY (padre_id) REFERENCES menus(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        if (!$this->connection->query($sql)) {
            $error = $this->connection->error;
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
            throw new \mysqli_sql_exception("Error crítico al estructurar la tabla menus: " . $error);
        }

        // 3. Reactivación de restricciones
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
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
