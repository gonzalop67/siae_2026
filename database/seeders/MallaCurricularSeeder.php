<?php

class MallaCurricularSeeder
{
    /**
     * Ejecuta el seeder para poblar la malla curricular histórica.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // 1. Obtener el periodo lectivo más reciente
        $resPeriodo = $mysqli->query("SELECT id FROM periodos_lectivos ORDER BY id DESC LIMIT 1");
        $periodo = $resPeriodo->fetch_assoc();
        $periodoLectivoId = $periodo ? (int)$periodo['id'] : 1;

        // 2. Definición de la Malla de Básica Superior (Subnivel ID: 4)
        // Se aplica en lote a 8°, 9° y 10° a través del subnivel con sus horas oficiales
        $mallaSubnivelSuperior = array(
            array('asignatura_id' => 1, 'horas' => 6), // Matemáticas (6 horas semanales)
            array('asignatura_id' => 2, 'horas' => 6), // Lengua y Literatura (6 horas)
            array('asignatura_id' => 3, 'horas' => 4), // Ciencias Naturales (4 horas)
            array('asignatura_id' => 4, 'horas' => 4), // Estudios Sociales (4 horas)
            array('asignatura_id' => 5, 'horas' => 5), // Inglés (5 horas)
            array('asignatura_id' => 6, 'horas' => 5)  // Educación Física (5 horas)
        );

        // 3. Definición de la Malla de Tercer Año de Bachillerato (Curso ID: 6)
        // Malla especializada individual para la salida de la educación obligatoria
        $mallaTercerBachillerato = array(
            array('asignatura_id' => 1, 'horas' => 4), // Matemáticas (Se reduce a 4 horas en 3° BGU)
            array('asignatura_id' => 2, 'horas' => 4), // Lengua y Literatura (4 horas)
            array('asignatura_id' => 5, 'horas' => 3), // Inglés (3 horas)
            array('asignatura_id' => 6, 'horas' => 2)  // Educación Física (2 horas)
        );

        // 4. Sentencia preparada estricta
        $stmt = $mysqli->prepare("INSERT IGNORE INTO malla_curricular 
            (periodo_lectivo_id, subnivel_id, curso_id, asignatura_id, horas_semanales) 
            VALUES (?, ?, ?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Error preparando MallaCurricularSeeder: " . $mysqli->error);
        }

        // 5. EJECUCIÓN LOTE A: Básica Superior (subnivel_id = 4, curso_id = NULL)
        $subnivelSuperiorId = 4;
        $cursoNull = null;
        
        foreach ($mallaSubnivelSuperior as $item) {
            $stmt->bind_param(
                'iiiis', 
                $periodoLectivoId,
                $subnivelSuperiorId,
                $cursoNull,
                $item['asignatura_id'],
                $item['horas']
            );
            $stmt->execute();
        }

        // 6. EJECUCIÓN LOTE B: Tercer Año de Bachillerato (subnivel_id = NULL, curso_id = 6)
        $subnivelNull = null;
        $cursoTercerBguId = 6; // Verifica si en tu tabla 'cursos' el ID de 3ro de Bachillerato es el 6
        
        foreach ($mallaTercerBachillerato as $item) {
            $stmt->bind_param(
                'iiiis',
                $periodoLectivoId,
                $subnivelNull,
                $cursoTercerBguId,
                $item['asignatura_id'],
                $item['horas']
            );
            $stmt->execute();
        }

        $stmt->close();
        echo "Malla curricular optimizada e híbrida del MINEDUC poblada con éxito.\n";
    }
}
