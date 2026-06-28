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
                    require_once $file;
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
