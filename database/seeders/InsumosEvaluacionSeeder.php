<?php 
class InsumosEvaluacionSeeder {
    /**
     * Ejecuta el seeder para poblar la tabla insumos_evaluacion.
     * 
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli) {
        // 1. CORRECCIÓN: Obtener el primer PARCIAL disponible (ej: Parcial 1) en lugar del trimestre completo
        $resParcial = $mysqli->query("SELECT id FROM parciales_evaluacion ORDER BY id ASC LIMIT 1");
        if (!$resParcial) {
            throw new Exception("Error al consultar parciales_evaluacion: " . $mysqli->error);
        }
        $parcial = $resParcial->fetch_assoc();
        if (!$parcial) {
            throw new Exception("Error: No se encontró ningún registro en 'parciales_evaluacion'. Ejecuta su seeder primero.");
        }
        $parcialEvaluacionId = (int)$parcial['id'];

        // 2. CORRECCIÓN: Quitamos la columna 'parcial' inexistente y usamos la nueva estructura
        $resTipos = $mysqli->query("SELECT id, macro_categoria FROM tipos_evaluacion WHERE parcial_evaluacion_id = $parcialEvaluacionId");
        if (!$resTipos) {
            throw new Exception("Error al consultar tipos_evaluacion: " . $mysqli->error);
        }
        
        $tipos = array();
        while ($row = $resTipos->fetch_assoc()) {
            $tipos[$row['macro_categoria']][] = (int)$row['id'];
        }

        if (empty($tipos)) {
            throw new Exception("Error: No se encontraron tipos de evaluación configurados para el parcial ID: " . $parcialEvaluacionId);
        }

        // 3. Obtener todos los registros vigentes de tu tabla malla_curricular
        $resMalla = $mysqli->query("SELECT id FROM malla_curricular");
        if (!$resMalla) {
            throw new Exception("Error al consultar malla_curricular: " . $mysqli->error);
        }
        
        $mallaIds = array();
        while ($row = $resMalla->fetch_assoc()) {
            $mallaIds[] = (int)$row['id'];
        }

        if (empty($mallaIds)) {
            throw new Exception("Error: No se puede poblar insumos si la tabla 'malla_curricular' está vacía.");
        }

        // 4. Preparar la sentencia. NOTA: Asegúrate de que tu tabla 'insumos_evaluacion' use parcial_evaluacion_id o adáptalo a tu columna exacta
        $queryInsert = "
            INSERT IGNORE INTO insumos_evaluacion 
            (parcial_evaluacion_id, tipo_evaluacion_id, malla_curricular_id, titulo, fecha_actividad, descripcion) 
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        
        $stmt = $mysqli->prepare($queryInsert);
        if (!$stmt) {
            throw new Exception("Error preparando InsumosEvaluacionSeeder: " . $mysqli->error);
        }

        $fechaActividad = '2026-05-15';

        // 5. Recorrer cada materia de la malla y crearle sus insumos obligatorios del MINEDUC
        foreach ($mallaIds as $mallaId) {
            
            // A. INSUMOS FORMATIVOS (70%)
            if (isset($tipos['formativa']) && !empty($tipos['formativa'])) {
                $idTipoFormativo = $tipos['formativa'][0]; // Primer tipo formativo (ej: Actividades Individuales)
                
                $deber1_titulo = "Deber 1: Investigación y Conceptualización";
                $deber1_desc = "Actividad formativa autónoma obligatoria correspondiente al bloque inicial.";
                $stmt->bind_param('iiisss', $parcialEvaluacionId, $idTipoFormativo, $mallaId, $deber1_titulo, $fechaActividad, $deber1_desc);
                $stmt->execute();

                $leccion_titulo = "Lección Escrita N°1";
                $leccion_desc = "Evaluación continua de control de destrezas adquiridas en clase.";
                $stmt->bind_param('iiisss', $parcialEvaluacionId, $idTipoFormativo, $mallaId, $leccion_titulo, $fechaActividad, $leccion_desc);
                $stmt->execute();
            }

            // B. INSUMOS SUMATIVOS (30%)
            if (isset($tipos['sumativa']) && !empty($tipos['sumativa'])) {
                $idTipoSumativo = $tipos['sumativa'][0]; // Primer tipo sumativo (ej: Examen Trimestral/Parcial)
                
                $examen_titulo = "Evaluación de Base Estructurada P1";
                $examen_desc = "Examen sumativo obligatorio que valida los logros de aprendizaje del parcial.";
                $stmt->bind_param('iiisss', $parcialEvaluacionId, $idTipoSumativo, $mallaId, $examen_titulo, $fechaActividad, $examen_desc);
                $stmt->execute();
            }
        }

        $stmt->close();
        echo "Insumos de evaluación inicializados con éxito para toda la malla curricular.\n";
    }
}
