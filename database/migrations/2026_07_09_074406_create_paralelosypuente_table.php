<?php

use App\Models\Model;

class CreateParalelosYPuenteTable extends Model
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        // 1. Catálogo Maestro de Paralelos Estáticos
        $sqlCat = "CREATE TABLE IF NOT EXISTS paralelos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(5) NOT NULL UNIQUE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        
        $this->connection->query($sqlCat);

        // 2. Tabla Puente con Atributos de Contexto (Tutor y Periodo)
        $sqlPuente = "CREATE TABLE IF NOT EXISTS curso_paralelo_periodo (
            id INT AUTO_INCREMENT PRIMARY KEY,
            periodo_lectivo_id INT NOT NULL,
            curso_id INT NOT NULL,
            paralelo_id INT NOT NULL,
            docente_id INT NULL,
            cupo_maximo TINYINT NOT NULL DEFAULT 40,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
            
            CONSTRAINT fk_cpp_periodo FOREIGN KEY (periodo_lectivo_id) REFERENCES periodos_lectivos(id) ON DELETE RESTRICT,
            CONSTRAINT fk_cpp_curso FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE RESTRICT,
            CONSTRAINT fk_cpp_paralelo FOREIGN KEY (paralelo_id) REFERENCES paralelos(id) ON DELETE RESTRICT,
            CONSTRAINT fk_cpp_tutor FOREIGN KEY (docente_id) REFERENCES usuarios(id) ON DELETE SET NULL,
            
            UNIQUE KEY idx_unico_pivote (periodo_lectivo_id, curso_id, paralelo_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        $this->connection->query($sqlPuente);
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        $this->connection->query("DROP TABLE IF EXISTS curso_paralelo_periodo;");
        $this->connection->query("DROP TABLE IF EXISTS paralelos;");
    }
}
