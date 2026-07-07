<?php

use App\Models\Model;

class CreateMatriculasTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS matriculas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            aula_periodo_id INT NOT NULL,
            alumno_id INT NOT NULL,
            fecha_matricula DATE NOT NULL,
            numero_matricula VARCHAR(30) NOT NULL, -- Ej: MAT-2026-005
            estado_matricula ENUM('Aspirante', 'Matriculado', 'Retirado', 'Anulado') NOT NULL DEFAULT 'Matriculado',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (aula_periodo_id) REFERENCES aulas_periodo(id) ON DELETE RESTRICT,
            FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE RESTRICT,
            UNIQUE KEY unique_alumno_periodo_lectivo (alumno_id, aula_periodo_id) -- Un alumno no puede matricularse dos veces el mismo año
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS matriculas;";
        $this->connection->query($sql);
    }
}
