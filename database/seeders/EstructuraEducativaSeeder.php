<?php

class EstructuraEducativaSeeder
{
    /**
     * Ejecuta el seeder para poblar la estructura educativa oficial de Ecuador.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // ==========================================
        // 1. INSERCIÓN DE NIVELES
        // ==========================================
        $niveles = ['Educación Inicial', 'Educación General Básica (EGB)', 'Bachillerato'];
        $stmtNivel = $mysqli->prepare("INSERT IGNORE INTO niveles_educativos (id, nombre) VALUES (?, ?)");
        
        foreach ($niveles as $index => $nombre) {
            $id = $index + 1;
            $stmtNivel->bind_param('is', $id, $nombre);
            $stmtNivel->execute();
        }
        $stmtNivel->close();

        // ==========================================
        // 2. INSERCIÓN DE SUBNIVELES (Mapeados a sus niveles)
        // ==========================================
        $subniveles = [
            ['id' => 1, 'nivel_id' => 2, 'nombre' => 'Preparatoria', 'orden' => 1],  // 1° EGB
            ['id' => 2, 'nivel_id' => 2, 'nombre' => 'Elemental', 'orden' => 2],     // 2°, 3°, 4° EGB
            ['id' => 3, 'nivel_id' => 2, 'nombre' => 'Media', 'orden' => 3],         // 5°, 6°, 7° EGB
            ['id' => 4, 'nivel_id' => 2, 'nombre' => 'Superior', 'orden' => 4],      // 8°, 9°, 10° EGB
            ['id' => 5, 'nivel_id' => 3, 'nombre' => 'Bachillerato General Unificado', 'orden' => 1] // 1°, 2°, 3° BGU
        ];

        $stmtSub = $mysqli->prepare("INSERT IGNORE INTO subniveles_educativos (id, nivel_id, nombre, orden) VALUES (?, ?, ?, ?)");
        foreach ($subniveles as $sub) {
            $stmtSub->bind_param('iisi', $sub['id'], $sub['nivel_id'], $sub['nombre'], $sub['orden']);
            $stmtSub->execute();
        }
        $stmtSub->close();

        // ==========================================
        // 3. INSERCIÓN DE CURSOS (Grados específicos enlazados a subniveles)
        // ==========================================
        $cursos = [
            // Básica Superior (subnivel_id: 4)
            ['subnivel_id' => 4, 'nombre' => 'Octavo Año de EGB'],
            ['subnivel_id' => 4, 'nombre' => 'Noveno Año de EGB'],
            ['subnivel_id' => 4, 'nombre' => 'Décimo Año de EGB'],
            
            // Bachillerato (subnivel_id: 5)
            ['subnivel_id' => 5, 'nombre' => 'Primer Año de Bachillerato'],
            ['subnivel_id' => 5, 'nombre' => 'Segundo Año de Bachillerato'],
            ['subnivel_id' => 5, 'nombre' => 'Tercer Año de Bachillerato']
        ];

        $stmtCurso = $mysqli->prepare("INSERT IGNORE INTO cursos (subnivel_id, nombre, seccion) VALUES (?, ?, 'Matutina')");
        foreach ($cursos as $curso) {
            $stmtCurso->bind_param('is', $curso['subnivel_id'], $curso['nombre']);
            $stmtCurso->execute();
        }
        $stmtCurso->close();
    }
}
