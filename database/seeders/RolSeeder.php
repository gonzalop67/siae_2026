<?php

class RolSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        // 1. Nombre de la tabla física asociada a los roles
        $tabla = 'roles';

        // 2. Definición de los roles iniciales de SIAE 2026
        $roles = [
            ['nombre' => 'Administrador', 'slug' => 'administrador', 'descripcion' => 'Acceso total a todos los módulos del sistema administrativo.'],
            ['nombre' => 'Autoridad',     'slug' => 'autoridad',     'descripcion' => 'Acceso a reportes de gestión educativa.'],
            ['nombre' => 'Coordinador',   'slug' => 'coordinador',   'descripcion' => 'Gestión y supervisión de ofertas educativas y asignaciones.'],
            ['nombre' => 'Docente',       'slug' => 'docente',       'descripcion' => 'Registro de calificaciones, asistencias y rúbricas.'],
            ['nombre' => 'Estudiante',    'slug' => 'estudiante',    'descripcion' => 'Consulta de historial académico y perfiles.'],
            ['nombre' => 'Secretaría',    'slug' => 'secretaria',    'descripcion' => 'Acceso a Matriculación y Reportes.'],
            ['nombre' => 'Tutor',         'slug' => 'tutor',         'descripcion' => 'Acceso a reportes de calificaciones y comportamiento.'],
        ];

        echo "    -> Insertando roles base en la tabla [{$tabla}]...\n";

        // Preparación de consultas para validar e insertar de forma segura
        $sqlCheck  = "SELECT id FROM `{$tabla}` WHERE slug = ?";
        $sqlInsert = "INSERT INTO `{$tabla}` (nombre, slug, descripcion) VALUES (?, ?, ?)";

        $stmtCheck  = $mysqli->prepare($sqlCheck);
        $stmtInsert = $mysqli->prepare($sqlInsert);

        if (!$stmtCheck || !$stmtInsert) {
            throw new \Exception("Error al preparar RolSeeder: " . $mysqli->error);
        }

        $insertados = 0;

        foreach ($roles as $rol) {
            // Validamos si el rol ya existe por su slug único
            $stmtCheck->bind_param('s', $rol['slug']);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo "       [-] El rol '{$rol['nombre']}' ya existe. Omitido.\n";
                continue;
            }

            // Si no existe, se ejecuta la inserción limpia
            $stmtInsert->bind_param('sss', $rol['nombre'], $rol['slug'], $rol['descripcion']);
            $stmtInsert->execute();
            $insertados++;
        }

        $stmtCheck->close();
        $stmtInsert->close();

        if ($insertados > 0) {
            echo "\e[32m    ✅ Roles insertados correctamente ({$insertados} nuevos).\e[0m\n";
        } else {
            echo "\e[33m    ℹ️ Todos los roles ya se encontraban registrados.\e[0m\n";
        }
    }
}
