<?php

class PeriodoLectivoSeeder
{
    public function run(mysqli $mysqli)
    {
        $tablaPeriodos = 'periodos_lectivos';
        $tablaBloques  = 'periodos_academicos';

        echo "    -> Insertando periodos lectivos y bloques académicos en la base de datos...\n";

        // 1. Inserción del Periodo Lectivo Base
        $sqlCheckPeriodo = "SELECT id FROM `{$tablaPeriodos}` WHERE nombre = 'Ciclo Sierra 2025-2026' LIMIT 1";
        $res = $mysqli->query($sqlCheckPeriodo);

        if ($res && $res->num_rows === 0) {
            $stmtPeriodo = $mysqli->prepare("INSERT INTO `{$tablaPeriodos}` (nombre, fecha_inicio, fecha_fin) VALUES (?, ?, ?)");
            $nombreP = "Ciclo Sierra 2025-2026";
            $inicioP = "2025-09-01";
            $finP    = "2026-06-30";
            $stmtPeriodo->bind_param('sss', $nombreP, $inicioP, $finP);
            $stmtPeriodo->execute();
            
            $periodoId = $mysqli->insert_id;
            $stmtPeriodo->close();
            echo "\e[32m       [+] Periodo Lectivo 'Ciclo Sierra 2025-2026' registrado.\e[0m\n";
        } else {
            $row = $res->fetch_assoc();
            $periodoId = (int)$row['id'];
            echo "       [-] El Periodo Lectivo ya existe. Omitido.\n";
        }

        // 2. Inserción secuencial de los Bloques Académicos (Trimestres)
        $bloques = [
            ['nombre' => 'Primer Trimestre',  'orden' => 1, 'inicio' => '2025-09-01', 'fin' => '2025-12-22'],
            ['nombre' => 'Segundo Trimestre', 'orden' => 2, 'inicio' => '2026-01-03', 'fin' => '2026-03-20'],
            ['nombre' => 'Tercer Trimestre',  'orden' => 3, 'inicio' => '2026-04-01', 'fin' => '2026-06-30'],
        ];

        $stmtBloque = $mysqli->prepare("INSERT INTO `{$tablaBloques}` (periodo_lectivo_id, nombre, tipo, orden, fecha_inicio, fecha_fin) VALUES (?, ?, 'trimestre', ?, ?, ?)");
        
        if (!$stmtBloque) {
            throw new \Exception("Error al preparar PeriodoLectivoSeeder: " . $mysqli->error);
        }

        $bloquesCreados = 0;
        foreach ($bloques as $b) {
            // Validamos que el trimestre no esté cargado previamente
            $sqlCheckBloque = "SELECT id FROM `{$tablaBloques}` WHERE periodo_lectivo_id = ? AND orden = ?";
            $stCheck = $mysqli->prepare($sqlCheckBloque);
            $stCheck->bind_param('ii', $periodoId, $b['orden']);
            $stCheck->execute();
            $stCheck->store_result();

            if ($stCheck->num_rows > 0) {
                $stCheck->close();
                continue;
            }
            $stCheck->close();

            $stmtBloque->bind_param('isiss', $periodoId, $b['nombre'], $b['orden'], $b['inicio'], $b['fin']);
            $stmtBloque->execute();
            $bloquesCreados++;
        }
        $stmtBloque->close();

        if ($bloquesCreados > 0) {
            echo "\e[32m    ✅ Periodos académicos inicializados ({$bloquesCreados} nuevos trimestres).\e[0m\n";
        } else {
            echo "\e[33m    ℹ️ Todos los periodos académicos ya estaban registrados.\e[0m\n";
        }
    }
}
