<?php
class ParcialEvaluacionSeeder {
    /**
     * Ejecuta el seeder para poblar la base de datos.
     * 
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli) {
        // 1. Obtener los periodos académicos del año lectivo más reciente
        $queryPeriodos = "
            SELECT id, orden, fecha_inicio, fecha_fin 
            FROM periodos_academicos 
            WHERE periodo_lectivo_id = (SELECT id FROM periodos_lectivos ORDER BY id DESC LIMIT 1)
            AND deleted_at IS NULL
            ORDER BY orden ASC
        ";
        $resultPeriodos = $mysqli->query($queryPeriodos);

        if (!$resultPeriodos || $resultPeriodos->num_rows === 0) {
            throw new Exception("Error: No se encontraron registros en 'periodos_academicos'. Ejecuta su seeder primero.");
        }

        // 2. Sentencia preparada con INSERT IGNORE para evitar duplicados
        $queryInsert = "
            INSERT IGNORE INTO parciales_evaluacion 
            (periodo_academico_id, nombre, orden, peso_nota, fecha_inicio, fecha_fin, fecha_cierre_notas) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $mysqli->prepare($queryInsert);
        if (!$stmt) {
            throw new Exception("Error en la preparación: " . $mysqli->error);
        }

        // 3. Recorrer cada periodo académico (Trimestres) para generar sus parciales
        while ($periodo = $resultPeriodos->fetch_assoc()) {
            $periodoId = (int)$periodo['id'];
            $fechaInicio = $periodo['fecha_inicio'];
            $fechaFin = $periodo['fecha_fin'];

            // Calculamos subfechas estimadas dinámicamente dividiendo el rango del trimestre
            $fechasCalculadas = $this->calcularFechasParciales($fechaInicio, $fechaFin);

            // Estructura de parciales bajo el modelo clásico (2 parciales formativos + 1 evaluación final)
            $parciales = [
                [
                    'nombre' => 'Parcial 1',
                    'orden' => 1,
                    'peso' => 35.00,
                    'inicio' => $fechasCalculadas['p1_inicio'],
                    'fin' => $fechasCalculadas['p1_fin'],
                    'cierre' => $fechasCalculadas['p1_cierre']
                ],
                [
                    'nombre' => 'Parcial 2',
                    'orden' => 2,
                    'peso' => 35.00,
                    'inicio' => $fechasCalculadas['p2_inicio'],
                    'fin' => $fechasCalculadas['p2_fin'],
                    'cierre' => $fechasCalculadas['p2_cierre']
                ],
                [
                    'nombre' => 'Evaluación Trimestral',
                    'orden' => 3,
                    'peso' => 30.00,
                    'inicio' => $fechasCalculadas['ev_inicio'],
                    'fin' => $fechasCalculadas['ev_fin'],
                    'cierre' => $fechasCalculadas['ev_cierre']
                ]
            ];

            // 4. Insertar los parciales de este periodo académico
            foreach ($parciales as $parcial) {
                $stmt->bind_param(
                    'isddsss',
                    $periodoId,
                    $parcial['nombre'],
                    $parcial['orden'],
                    $parcial['peso'],
                    $parcial['inicio'],
                    $parcial['fin'],
                    $parcial['cierre']
                );
                $stmt->execute();
            }
        }

        $stmt->close();
    }

    /**
     * Helper para dividir las fechas de un periodo en rangos lógicos para los parciales.
     */
    private function calcularFechasParciales($inicioStr, $finStr) {
        $inicio = new DateTime($inicioStr);
        $fin = new DateTime($finStr);
        $intervalo = $inicio->diff($fin);
        $diasTotales = $intervalo->days;

        // Dividimos el tiempo de forma equitativa (Aprox. 42% P1, 42% P2, 16% Evaluación Final)
        $diasP1 = (int)($diasTotales * 0.42);
        $diasP2 = (int)($diasTotales * 0.42);

        // P1
        $p1_inicio = clone $inicio;
        $p1_fin = (clone $inicio)->modify("+$diasP1 days");
        $p1_cierre = (clone $p1_fin)->modify("+5 days"); // 5 días de gracia para pasar notas

        // P2
        $p2_inicio = (clone $p1_fin)->modify("+1 day");
        $p2_fin = (clone $p2_inicio)->modify("+$diasP2 days");
        $p2_cierre = (clone $p2_fin)->modify("+5 days");

        // Evaluación Final
        $ev_inicio = (clone $p2_fin)->modify("+1 day");
        $ev_fin = clone $fin;
        $ev_cierre = (clone $ev_fin)->modify("+3 days");

        return [
            'p1_inicio' => $p1_inicio->format('Y-m-format'),
            'p1_fin'    => $p1_fin->format('Y-m-d'),
            'p1_cierre' => $p1_cierre->format('Y-m-d 23:59:59'),
            
            'p2_inicio' => $p2_inicio->format('Y-m-d'),
            'p2_fin'    => $p2_fin->format('Y-m-d'),
            'p2_cierre' => $p2_cierre->format('Y-m-d 23:59:59'),
            
            'ev_inicio' => $ev_inicio->format('Y-m-d'),
            'ev_fin'    => $ev_fin->format('Y-m-d'),
            'ev_cierre' => $ev_cierre->format('Y-m-d 18:00:00')
        ];
    }
}
