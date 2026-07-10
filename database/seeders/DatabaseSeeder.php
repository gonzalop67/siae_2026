<?php

class DatabaseSeeder
{
    /**
     * Método central de ejecución (Estilo Laravel).
     */
    public function run(mysqli $mysqli, bool $refresh = false): void
    {
        if ($refresh) {
            echo "\e[34m  -> [Refresh] Vaciando tablas mediante TRUNCATE...\e[0m\n";

            // 1. Apagar temporalmente la revisión de llaves foráneas en MySQL
            $mysqli->query("SET FOREIGN_KEY_CHECKS = 0;");

            // 2. Limpiar y resetear las tablas de raíz (TRUNCATE hace ambas acciones)
            $mysqli->query("TRUNCATE TABLE `usuarios_roles`;");
            $mysqli->query("TRUNCATE TABLE `usuarios`;");
            $mysqli->query("TRUNCATE TABLE `personas`;");
            $mysqli->query("TRUNCATE TABLE `nacionalidades`;");
            $mysqli->query("TRUNCATE TABLE `tipo_documento`;");
            $mysqli->query("TRUNCATE TABLE `roles`;");

            // Tablas de control de accesos y menús
            $mysqli->query("TRUNCATE TABLE `menus`;");
            $mysqli->query("TRUNCATE TABLE `permisos`;");
            $mysqli->query("TRUNCATE TABLE `roles_permisos`;");

            // NÚCLEO ACADÉMICO: Añadidos todos los truncates correspondientes para limpiar el lote
            $mysqli->query("TRUNCATE TABLE `periodos_lectivos`;");
            $mysqli->query("TRUNCATE TABLE `periodos_academicos`;");
            $mysqli->query("TRUNCATE TABLE `tipos_evaluacion`;");
            $mysqli->query("TRUNCATE TABLE `cursos`;");
            $mysqli->query("TRUNCATE TABLE `asignaturas`;");
            $mysqli->query("TRUNCATE TABLE `malla_curricular`;");
            $mysqli->query("TRUNCATE TABLE `paralelos`;");
            $mysqli->query("TRUNCATE TABLE `aulas_periodo`;");
            $mysqli->query("TRUNCATE TABLE `alumnos`;");
            $mysqli->query("TRUNCATE TABLE `matriculas`;");
            $mysqli->query("TRUNCATE TABLE `insumos_evaluacion`;");
            $mysqli->query("TRUNCATE TABLE `calificaciones`;");

            // 3. Reactivar la seguridad de llaves foráneas de forma obligatoria
            $mysqli->query("SET FOREIGN_KEY_CHECKS = 1;");

            echo "  \e[32m  ✅ Ecosistema de tablas vaciado y reseteado a ID 1 con éxito.\e[0m\n\n";
        }

        // Ejecución secuencial de los seeders (Respetando la jerarquía de dependencias)
        $this->call($mysqli, [
            RolSeeder::class,
            TipoDocumentoSeeder::class,
            NacionalidadSeeder::class,
            PersonaAdminSeeder::class,
            AdminUserSeeder::class,
            PersonasDocenteSeeder::class, // Puebla personas de prueba antes de AlumnoSeeder

            // 🔥 CONFIGURACIÓN ACADÉMICA BASE CENTRALIZADA:
            EstructuraEducativaSeeder::class, // 1° Inserta Niveles, Subniveles y Cursos en un solo paso limpio
            AsignaturaSeeder::class,          // 2° Catálogo de materias troncales
            PeriodoLectivoSeeder::class,      // 3° Abre el año escolar y los bloques académicos
            TipoEvaluacionSeeder::class,      // 4° Configura las macros formativas/sumativas con parciales
            MallaCurricularSeeder::class,     // 5° Distribuye horas (Depende de subniveles y asignaturas)
            ParaleloMaestroSeeder::class,     // 6° Llena catálogo 'paralelos' y la tabla puente
            AulaPeriodoSeeder::class,         // 7° Habilita los salones físicos 
            AlumnoSeeder::class,              // 8° Vincula personas a la condición de estudiantes
            MatriculaSeeder::class,           // 9° Sienta formalmente a los alumnos en las aulas creadas

            // NÚCLEO SEGURIDAD Y MENÚS:
            PermisoSeeder::class,
            RolesPermisosSeeder::class,
            MenuSeeder::class,
        ]);
    }

    /**
     * Método auxiliar encargado de instanciar y ejecutar seeders secundarios.
     */
    protected function call(mysqli $mysqli, array $seeders): void
    {
        foreach ($seeders as $seederClass) {
            if (!class_exists($seederClass)) {
                $file = __DIR__ . '/' . $seederClass . '.php';
                if (file_exists($file)) {
                    require $file;
                } else {
                    echo "\e[31mError:\e[0m No se encontró el archivo para la clase [{$seederClass}]\n";
                    continue;
                }
            }

            echo "  -> Ejecutando Seeder: {$seederClass}...\n";
            $instance = new $seederClass();
            $instance->run($mysqli);
        }
    }
}
