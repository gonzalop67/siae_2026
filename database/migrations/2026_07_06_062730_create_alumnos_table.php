<?php 
use App\Models\Model; 

class CreateAlumnosTable extends Model { 
    /** 
     * Ejecuta la migración (Crear o modificar tablas). 
     */ 
    public function up(): void { 
        $sql = "CREATE TABLE IF NOT EXISTS alumnos ( 
            id INT AUTO_INCREMENT PRIMARY KEY, 
            persona_id INT NOT NULL, 
            codigo_matricula VARCHAR(20) NOT NULL, 
            tipo_sangre VARCHAR(5) NULL, 
            alergias TEXT NULL, 
            discapacidad TINYINT(1) DEFAULT 0, 
            porcentaje_discapacidad DECIMAL(5,2) NULL, 
            carnet_conadis VARCHAR(30) NULL, 
            observaciones TEXT NULL, 
            estado TINYINT(1) DEFAULT 1, 
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
            deleted_at TIMESTAMP NULL, 
            
            UNIQUE KEY unique_persona_alumno (persona_id), 
            UNIQUE KEY unique_codigo_matricula (codigo_matricula), 
            FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE RESTRICT 
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"; 
        
        $this->connection->query($sql); 
    } 

    /** 
     * Revierte la migración (Eliminar tablas). 
     */ 
    public function down(): void { 
        $sql = "DROP TABLE IF EXISTS alumnos;"; 
        $this->connection->query($sql); 
    } 
}
