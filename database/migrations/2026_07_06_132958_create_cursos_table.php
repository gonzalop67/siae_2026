<?php

use App\Models\Model;

class CreateCursosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS cursos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL, -- Ej: Primer Año de Bachillerato, Octavo de Básica
            seccion ENUM('Matutina', 'Vespertina', 'Nocturna') NOT NULL DEFAULT 'Matutina'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
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
