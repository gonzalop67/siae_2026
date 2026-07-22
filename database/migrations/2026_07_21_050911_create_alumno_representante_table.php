<?php

use App\Models\Model;

class CreateAlumnoRepresentanteTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS alumno_representante (
            alumno_id INT NOT NULL,
            representante_id INT NOT NULL,
            parentesco VARCHAR(50) NULL,
            es_principal TINYINT(1) DEFAULT 0,
            PRIMARY KEY (alumno_id, representante_id),
            FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE,
            FOREIGN KEY (representante_id) REFERENCES representantes(id) ON DELETE RESTRICT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS alumno_representante;";
        $this->connection->query($sql);
    }
}
