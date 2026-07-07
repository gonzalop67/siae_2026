<?php

use App\Models\Model;

class CreateMallaCurricularTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // =========================================================================
        // 3. TABLA CRÍTICA: malla_curricular (La unión por Periodo Lectivo)
        // Vincula qué asignaturas se dictan en qué cursos y en qué año escolar específico.
        // =========================================================================
        $sql = "CREATE TABLE IF NOT EXISTS malla_curricular (
            id INT AUTO_INCREMENT PRIMARY KEY,
            periodo_lectivo_id INT NOT NULL, -- Garantiza la inmutabilidad histórica
            curso_id INT NOT NULL,
            asignatura_id INT NOT NULL,
            horas_semanales TINYINT NOT NULL DEFAULT 4, -- Carga horaria del periodo
            FOREIGN KEY (periodo_lectivo_id) REFERENCES periodos_lectivos(id) ON DELETE RESTRICT,
            FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE RESTRICT,
            FOREIGN KEY (asignatura_id) REFERENCES asignaturas(id) ON DELETE RESTRICT,
            UNIQUE KEY unique_malla_periodo (periodo_lectivo_id, curso_id, asignatura_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS malla_curricular;";
        $this->connection->query($sql);
    }
}
