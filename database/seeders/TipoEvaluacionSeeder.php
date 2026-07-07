<?php

use Core\Faker;

class TipoEvaluacionSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // 1. Obtener el ID del periodo lectivo más reciente
        $result = $mysqli->query("SELECT id FROM periodos_lectivos ORDER BY id DESC LIMIT 1");
        $periodo = $result->fetch_assoc();

        if (!$periodo) {
            throw new Exception("Error: No se encontró ningún registro en la tabla 'periodos_lectivos'. Ejecuta su seeder primero.");
        }

        $periodoLectivoId = (int)$periodo['id'];

        // 2. Definición del catálogo maestro bajo el modelo MINEDUC
        $tiposEvaluacion = [
            // EJE FORMATIVO (70%)
            [
                'macro' => 'formativa',
                'nombre' => 'Actividades Individuales',
                'ponderacion' => 70.00,
                'desc' => 'Lecciones, pruebas, tareas escritas o trabajos prácticos realizados de forma autónoma.'
            ],
            [
                'macro' => 'formativa',
                'nombre' => 'Actividades Grupales',
                'ponderacion' => 70.00,
                'desc' => 'Proyectos en equipo, debates, exposiciones o talleres prácticos en clase.'
            ],
            // EJE SUMATIVO (30%)
            [
                'macro' => 'sumativa',
                'nombre' => 'Evaluación de Periodo Académico',
                'ponderacion' => 30.00,
                'desc' => 'Examen de base estructurada que mide los logros de aprendizaje del trimestre/quimestre.'
            ],
            [
                'macro' => 'sumativa',
                'nombre' => 'Proyecto Interdisciplinar',
                'ponderacion' => 30.00,
                'desc' => 'Evidencia final de la aplicación integrada de saberes de múltiples asignaturas.'
            ]
        ];

        // 3. Sentencia preparada con INSERT IGNORE para respetar la restricción UNIQUE KEY
        $stmt = $mysqli->prepare("INSERT IGNORE INTO tipos_evaluacion 
            (periodo_lectivo_id, macro_categoria, nombre, ponderacion_macro, descripcion) 
            VALUES (?, ?, ?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $mysqli->error);
        }

        // 4. Recorrer e insertar los registros de forma segura
        foreach ($tiposEvaluacion as $tipo) {
            $stmt->bind_param(
                'issds', 
                $periodoLectivoId, 
                $tipo['macro'], 
                $tipo['nombre'], 
                $tipo['ponderacion'], 
                $tipo['desc']
            );
            $stmt->execute();
        }

        $stmt->close();
    }
}
