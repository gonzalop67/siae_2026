<?php

use App\Models\Model;

class CreateAulasPeriodoTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS aulas_periodo (
            id INT AUTO_INCREMENT PRIMARY KEY,
            periodo_lectivo_id INT NOT NULL,
            curso_id INT NOT NULL,
            paralelo_id INT NOT NULL,
            jornada ENUM('Matutina', 'Vespertina', 'Nocturna') NOT NULL DEFAULT 'Matutina',
            cupo_maximo TINYINT NOT NULL DEFAULT 40,
            FOREIGN KEY (periodo_lectivo_id) REFERENCES periodos_lectivos(id) ON DELETE RESTRICT,
            FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE RESTRICT,
            FOREIGN KEY (paralelo_id) REFERENCES paralelos(id) ON DELETE RESTRICT,
            UNIQUE KEY unique_aula_periodo_jornada (periodo_lectivo_id, curso_id, paralelo_id, jornada)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS aulas_periodo;";
        $this->connection->query($sql);
    }
}
