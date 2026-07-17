<?php 
use App\Models\Model; 

class CreateCalificacionesEstudiantesTable extends Model { 
    /** 
     * Ejecuta la migración (Crear o modificar tablas). 
     */ 
    public function up(): void { 
        $sql = "CREATE TABLE IF NOT EXISTS calificaciones_estudiantes ( 
            id INT AUTO_INCREMENT PRIMARY KEY, 
            insumo_evaluacion_id INT NOT NULL, 
            alumno_id INT NOT NULL, -- CORREGIDO: De estudiante_id a alumno_id
            nota DECIMAL(4, 2) NOT NULL, 
            observacion VARCHAR(255) NULL, 
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
            deleted_at TIMESTAMP NULL DEFAULT NULL, 

            FOREIGN KEY (insumo_evaluacion_id) REFERENCES insumos_evaluacion(id) ON DELETE RESTRICT, 
            FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE RESTRICT, -- CORREGIDO: Apunta a alumnos
            
            UNIQUE KEY uq_insumo_alumno (insumo_evaluacion_id, alumno_id, deleted_at) -- CORREGIDO
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"; 
        
        $this->connection->query($sql); 
    } 

    /** 
     * Revierte la migración (Eliminar tablas). 
     */ 
    public function down(): void { 
        $sql = "DROP TABLE IF EXISTS calificaciones_estudiantes;"; 
        $this->connection->query($sql); 
    } 
}
