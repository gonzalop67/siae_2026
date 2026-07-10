<?php

use App\Models\Model;

class CreateMallaCurricularTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // 1. Desactivar llaves foráneas temporalmente para asegurar una creación limpia
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->connection->query("DROP TABLE IF EXISTS malla_curricular;");

        // 2. Creación con tipos de datos e INT(11) idénticos a tu phpMyAdmin SQL Dump
        $sql = "CREATE TABLE malla_curricular (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            periodo_lectivo_id INT(11) NOT NULL,
            subnivel_id INT(11) NULL DEFAULT NULL, 
            curso_id INT(11) NULL DEFAULT NULL,    
            asignatura_id INT(11) NOT NULL,
            horas_semanales TINYINT(4) NOT NULL DEFAULT 4,
            
            CONSTRAINT fk_malla_periodo FOREIGN KEY (periodo_lectivo_id) REFERENCES periodos_lectivos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_malla_subnivel FOREIGN KEY (subnivel_id) REFERENCES subniveles_educativos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_malla_curso FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_malla_asignatura FOREIGN KEY (asignatura_id) REFERENCES asignaturas(id) ON DELETE RESTRICT ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        if (!$this->connection->query($sql)) {
            $error = $this->connection->error;
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
            throw new \mysqli_sql_exception("Error crítico al estructurar la tabla malla_curricular: " . $error);
        }

        // Reactivar el chequeo de restricciones
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");

        // 3. Creación de índices únicos para evitar duplicados
        $this->connection->query("CREATE UNIQUE INDEX idx_malla_subnivel_materia ON malla_curricular (periodo_lectivo_id, subnivel_id, asignatura_id);");
        $this->connection->query("CREATE UNIQUE INDEX idx_malla_curso_materia ON malla_curricular (periodo_lectivo_id, curso_id, asignatura_id);");
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
