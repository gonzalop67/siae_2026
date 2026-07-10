<?php

class InsumosEvaluacionSeeder
{
    /**
     * Ejecuta el seeder para poblar la tabla insumos_evaluacion.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // 1. Obtener el primer periodo académico disponible (ej: Trimestre 1)
        $resPeriodo = $mysqli->query("SELECT id FROM periodos_academicos ORDER BY id ASC LIMIT 1");
        $periodo = $resPeriodo->fetch_assoc();
        $periodoAcademicoId = $periodo ? (int)$periodo['id'] : 1;

        // 2. Obtener los IDs del catálogo maestro de tipos de evaluación (Formativas y Sumativas)
        // Buscamos las actividades creadas para el parcial 1 o general
        $resTipos = $mysqli->query("SELECT id, macro_categoria, parcial FROM tipos_evaluacion");
        $tipos = array();
        while ($row = $resTipos->fetch_assoc()) {
            $tipos[$row['macro_categoria']][] = (int)$row['id'];
        }

        // 3. Obtener todos los registros vigentes de tu tabla malla_curricular
        $resMalla = $mysqli->query("SELECT id FROM malla_curricular");
        $mallaIds = array();
        while ($row = $resMalla->fetch_assoc()) {
            $mallaIds[] = (int)$row['id'];
        }

        if (empty($mallaIds)) {
            throw new Exception("Error: No se puede poblar insumos si la tabla 'malla_curricular' está vacía.");
        }

        // 4. Preparar la sentencia de inserción estructurada para mysqli
        $stmt = $mysqli->prepare("INSERT IGNORE INTO insumos_evaluacion 
            (periodo_academico_id, tipo_evaluacion_id, malla_curricular_id, titulo, fecha_actividad, descripcion) 
            VALUES (?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Error preparando InsumosEvaluacionSeeder: " . $mysqli->error);
        }

        $fechaActividad = '2026-05-15';

        // 5. Recorrer cada materia de la malla y crearle sus insumos obligatorios del MINEDUC
        foreach ($mallaIds as $mallaId) {
            
            // A. INSUMOS FORMATIVOS (70% - Habitualmente se exigen mínimo 2 o 3 actividades por parcial)
            if (isset($tipos['formativa'])) {
                $idTipoFormativo = $tipos['formativa'][0]; // Primer tipo formativo (ej: Actividades Individuales)
                
                $deber1_titulo = "Deber 1: Investigación y Conceptualización";
                $deber1_desc   = "Actividad formativa autónoma obligatoria correspondiente al bloque inicial.";
                $stmt->bind_param('iiisss', $periodoAcademicoId, $idTipoFormativo, $mallaId, $deber1_titulo, $fechaActividad, $deber1_desc);
                $stmt->execute();

                $leccion_titulo = "Lección Escrita N°1";
                $leccion_desc   = "Evaluación continua de control de destrezas adquiridas en clase.";
                $stmt->bind_param('iiisss', $periodoAcademicoId, $idTipoFormativo, $mallaId, $leccion_titulo, $fechaActividad, $leccion_desc);
                $stmt->execute();
            }

            // B. INSUMOS SUMATIVOS (30% - Evaluación de cierre de periodo o proyecto de unidad)
            if (isset($tipos['sumativa'])) {
                $idTipoSumativo = $tipos['sumativa'][0]; // Primer tipo sumativo (ej: Examen Trimestral)
                
                $examen_titulo = "Evaluación de Base Estructurada T1";
                $examen_desc   = "Examen sumativo obligatorio que valida los logros de aprendizaje del trimestre.";
                $stmt->bind_param('iiisss', $periodoAcademicoId, $idTipoSumativo, $mallaId, $examen_titulo, $fechaActividad, $examen_desc);
                $stmt->execute();
            }
        }

        $stmt->close();
        echo "Insumos de evaluación inicializados con éxito para toda la malla curricular.\n";
    }
}
