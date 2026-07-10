<?php

use App\Models\Model;

class CreateCursosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // 1. Limpieza preventiva y desactivación temporal de llaves foráneas
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->connection->query("DROP TABLE IF EXISTS cursos;");

        // 2. Creación con compatibilidad estricta INT(11)
        $sql = "CREATE TABLE IF NOT EXISTS cursos (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            subnivel_id INT(11) NOT NULL,
            nombre VARCHAR(100) NOT NULL, 
            seccion ENUM('Matutina', 'Vespertina', 'Nocturna') NOT NULL DEFAULT 'Matutina',
            
            -- Clave foránea con alias explícito y cascada de actualización
            CONSTRAINT fk_cursos_subnivel FOREIGN KEY (subnivel_id) REFERENCES subniveles_educativos(id) ON DELETE RESTRICT ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        if (!$this->connection->query($sql)) {
            $error = $this->connection->error;
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
            throw new \mysqli_sql_exception("Error crítico al estructurar la tabla cursos: " . $error);
        }

        // 3. Reactivar restricciones de forma segura
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS cursos;";
        $this->connection->query($sql);
    }
}
