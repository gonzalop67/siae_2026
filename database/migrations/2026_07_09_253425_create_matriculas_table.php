<?php

use App\Models\Model;

class CreateMatriculasTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // 1. Desactivación temporal de llaves para instalación limpia
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->connection->query("DROP TABLE IF EXISTS matriculas;");

        // 2. Definición estricta de la tabla matriculas
        $sql = "CREATE TABLE matriculas (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            aula_periodo_id INT(11) NOT NULL,
            alumno_id INT(11) NOT NULL,
            fecha_matricula DATE NOT NULL,
            numero_matricula VARCHAR(30) NOT NULL, 
            estado_matricula ENUM('Aspirante', 'Matriculado', 'Retirado', 'Anulado') NOT NULL DEFAULT 'Matriculado',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            CONSTRAINT fk_matricula_aula FOREIGN KEY (aula_periodo_id) REFERENCES aulas_periodo(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_matricula_alumno FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            
            -- 💡 CORRECCIÓN MASTER: Evita que el mismo alumno se matricule más de una vez en la misma aula/periodo
            UNIQUE KEY unique_alumno_aula_periodo (alumno_id, aula_periodo_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        if (!$this->connection->query($sql)) {
            $error = $this->connection->error;
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
            throw new \mysqli_sql_exception("Error crítico al estructurar la tabla matriculas: " . $error);
        }

        // 3. Reactivación de restricciones
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
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
