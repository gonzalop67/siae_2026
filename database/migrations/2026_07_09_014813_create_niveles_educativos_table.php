<?php

use App\Models\Model;

class CreateNivelesEducativosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // 1. Limpieza preventiva
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->connection->query("DROP TABLE IF EXISTS niveles_educativos;");
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");

        // 2. Creación con la coma corregida y compatibilidad INT(11)
        $sql = "CREATE TABLE IF NOT EXISTS niveles_educativos (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL, -- 💡 CORRECCIÓN: Se añadió la coma faltante aquí
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        if (!$this->connection->query($sql)) {
            throw new \mysqli_sql_exception("Error crítico al estructurar niveles_educativos: " . $this->connection->error);
        }
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS niveles_educativos;";
        $this->connection->query($sql);
    }
}
