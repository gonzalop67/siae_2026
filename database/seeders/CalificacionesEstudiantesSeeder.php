<?php

class CalificacionesEstudiantesSeeder {
    /**
     * Ejecuta el seeder para poblar la tabla calificaciones_estudiantes.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli) {
        $tabla = 'calificaciones_estudiantes';
        echo " -> Inicializando el registro transaccional de notas en [{$tabla}]...\n";

        // 1. Obtener todos los IDs de los alumnos activos
        $resAlumnos = $mysqli->query("SELECT id FROM alumnos WHERE estado = 1 AND deleted_at IS NULL");
        if (!$resAlumnos) {
            throw new Exception("Error al consultar alumnos: " . $mysqli->error);
        }
        
        $alumnoIds = [];
        while ($row = $resAlumnos->fetch_assoc()) {
            $alumnoIds[] = (int)$row['id'];
        }

        if (empty($alumnoIds)) {
            throw new Exception("Error: No se pueden registrar calificaciones si la tabla 'alumnos' está vacía.");
        }

        // 2. Obtener todos los insumos de evaluación vigentes
        $resInsumos = $mysqli->query("SELECT id FROM insumos_evaluacion WHERE deleted_at IS NULL");
        if (!$resInsumos) {
            throw new Exception("Error al consultar insumos_evaluacion: " . $mysqli->error);
        }

        $insumoIds = [];
        while ($row = $resInsumos->fetch_assoc()) {
            $insumoIds[] = (int)$row['id'];
        }

        if (empty($insumoIds)) {
            throw new Exception("Error: No se pueden registrar calificaciones si la tabla 'insumos_evaluacion' está vacía.");
        }

        // 3. Preparar la sentencia de inserción con INSERT IGNORE para respetar la UNIQUE KEY
        $queryInsert = "
            INSERT IGNORE INTO calificaciones_estudiantes (insumo_evaluacion_id, alumno_id, nota, observacion) 
            VALUES (?, ?, ?, ?)
        ";
        
        $stmt = $mysqli->prepare($queryInsert);
        if (!$stmt) {
            throw new Exception("Error al preparar CalificacionesEstudiantesSeeder: " . $mysqli->error);
        }

        // Catálogo corto de observaciones aleatorias para dar realismo a los datos
        $observacionesSimuladas = [
            NULL, NULL, NULL, NULL, NULL, NULL, NULL, // Mayor probabilidad de ser NULL (entrega normal)
            'Excelente trabajo presentado en clase.',
            'Entregó con retraso justificado.',
            'Faltó a la sesión, rindió la evaluación después.'
        ];

        $totalCalificaciones = 0;

        // 4. Doble bucle para asignar una nota a cada alumno por cada insumo existente
        foreach ($alumnoIds as $alumnoId) {
            foreach ($insumoIds as $insumoId) {
                // Genera una nota aleatoria entre 5.00 y 10.00 con dos decimales
                $notaAleatoria = rand(500, 1000) / 100;

                // Selección aleatoria de una observación
                $observacion = $observacionesSimuladas[array_rand($observacionesSimuladas)];

                // 🔥 CORRECCIÓN CRÍTICA: Se cambia el tipo de parámetro de 'iids' a 'idds'
                // insumo_evaluacion_id (i), alumno_id (i), nota (d), observacion (s)
                $stmt->bind_param(
                    'iids', 
                    $insumoId, 
                    $alumnoId, 
                    $notaAleatoria, 
                    $observacion
                );
                
                $stmt->execute();
                
                if ($mysqli->affected_rows > 0) {
                    $totalCalificaciones++;
                }
            }
        }

        $stmt->close();
        echo "\e[32m ✅ Calificaciones inicializadas con éxito. Se asentaron {$totalCalificaciones} nuevas notas en el sistema.\e[0m\n";
    }
}
