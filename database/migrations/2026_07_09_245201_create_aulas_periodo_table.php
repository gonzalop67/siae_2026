<?php

use App\Models\Model;

class CreateAulasPeriodoTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        // 1. Limpieza preventiva y desactivación de restricciones para instalación limpia
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->connection->query("DROP TABLE IF EXISTS aulas_periodo;");

        // 2. Creación con compatibilidad estricta de longitudes (Mismo estándar de tu volcado)
        $sql = "CREATE TABLE aulas_periodo (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            periodo_lectivo_id INT(11) NOT NULL,
            curso_id INT(11) NOT NULL,
            paralelo_id INT(11) NOT NULL,
            jornada ENUM('Matutina', 'Vespertina', 'Nocturna') NOT NULL DEFAULT 'Matutina',
            cupo_maximo TINYINT(4) NOT NULL DEFAULT 40,
            
            -- Restricciones explícitas con alias únicos para evitar colisiones
            CONSTRAINT fk_aulas_periodo_lectivo FOREIGN KEY (periodo_lectivo_id) REFERENCES periodos_lectivos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_aulas_curso FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            CONSTRAINT fk_aulas_paralelo FOREIGN KEY (paralelo_id) REFERENCES paralelos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
            
            -- Clave única para evitar duplicar el mismo paralelo en el mismo curso, año y jornada
            UNIQUE KEY unique_aula_periodo_jornada (periodo_lectivo_id, curso_id, paralelo_id, jornada)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        if (!$this->connection->query($sql)) {
            $error = $this->connection->error;
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
            throw new \mysqli_sql_exception("Error crítico al estructurar la tabla aulas_periodo: " . $error);
        }

        // 3. Reactivar restricciones de forma segura
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
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
