<?php

use App\Models\Model;

class CreatePermisosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // 1. Limpieza preventiva y desactivación temporal de restricciones
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->connection->query("DROP TABLE IF EXISTS permisos;");

        // 2. Creación estructural homogénea compatible con la tabla 'menus'
        $sql = "CREATE TABLE permisos (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(50) NOT NULL UNIQUE,
            
            -- 💡 ALINEACIÓN: Cambiado a VARCHAR(100) para acoplarse con la FK de la tabla menus
            slug VARCHAR(100) NOT NULL UNIQUE, 
            
            descripcion VARCHAR(100) NULL DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        if (!$this->connection->query($sql)) {
            $error = $this->connection->error;
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
            throw new \mysqli_sql_exception("Error crítico al estructurar la tabla permisos: " . $error);
        }

        // 3. Reactivación de restricciones
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS permisos;";
        $this->connection->query($sql);
    }
}
