<?php

use App\Models\Model;

class CreateSubnivelesEducativosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // 1. Limpieza preventiva y desactivación temporal de restricciones
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->connection->query("DROP TABLE IF EXISTS subniveles_educativos;");

        // 2. Creación estructural homogénea INT(11)
        $sql = "CREATE TABLE IF NOT EXISTS subniveles_educativos (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            nivel_id INT(11) NOT NULL,
            nombre VARCHAR(100) NOT NULL, 
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            
            -- Clave foránea explícita con alias único para el motor de base de datos
            CONSTRAINT fk_subniveles_nivel FOREIGN KEY (nivel_id) REFERENCES niveles_educativos(id) ON DELETE RESTRICT ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        if (!$this->connection->query($sql)) {
            $error = $this->connection->error;
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
            throw new \mysqli_sql_exception("Error crítico al estructurar subniveles_educativos: " . $error);
        }

        // 3. Reactivación de restricciones
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS subniveles_educativos;";
        $this->connection->query($sql);
    }
}
