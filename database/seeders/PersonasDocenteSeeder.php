<?php

use Core\Faker;
use Core\Encrypter;

class PersonasDocenteSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos de forma acoplada.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        $tablaPivot = 'usuarios_roles';

        // 1. Buscar el ID del rol 'Docente' dinámicamente
        $resultadoRol = $mysqli->query("SELECT id FROM roles WHERE nombre = 'Docente' LIMIT 1");
        $rol = $resultadoRol->fetch_assoc();

        if (!$rol) {
            throw new \Exception("Error crítico: No se encontró el rol 'Docente'. Asegúrate de que RolSeeder se ejecute antes.");
        }
        $rolId = (int)$rol['id'];

        // 2. Preparar las sentencias SQL respetando tu esquema exacto
        $sqlPersona = "INSERT IGNORE INTO `personas` (
            `tipo_documento_id`, `nacionalidad_id`, `dni`, 
            `primer_apellido`, `segundo_apellido`, `primer_nombre`, `segundo_nombre`, 
            `nombre_corto`, `nombre_completo`, `genero`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $sqlUsuario = "INSERT IGNORE INTO `usuarios` (
            `persona_id`, `username`, `email`, `password`, `avatar`
        ) VALUES (?, ?, ?, ?, ?)";

        $sqlPivot = "INSERT IGNORE INTO `{$tablaPivot}` (`usuario_id`, `rol_id`) VALUES (?, ?)";

        $stmtPersona = $mysqli->prepare($sqlPersona);
        $stmtUsuario = $mysqli->prepare($sqlUsuario);
        $stmtPivot   = $mysqli->prepare($sqlPivot);

        if (!$stmtPersona || !$stmtUsuario || !$stmtPivot) {
            throw new \Exception("Error al preparar las consultas del ecosistema Docente: " . $mysqli->error);
        }

        // 3. Vincular parámetros por referencia (Fuera del bucle)
        $tipoDocId = $nacionalId = 0;
        $dni = $primerAp = $segundoAp = $primerName = $segundoName = $nombreCorto = $nombreCompl = $genero = '';
        $stmtPersona->bind_param(
            'iissssssss',
            $tipoDocId, $nacionalId, $dni, $primerAp, $segundoAp, 
            $primerName, $segundoName, $nombreCorto, $nombreCompl, $genero
        );

        $personaId = 0;
        $username = $email = $passwordEnc = $avatar = '';
        $stmtUsuario->bind_param('issss', $personaId, $username, $email, $passwordEnc, $avatar);

        $usuarioId = 0;
        $stmtPivot->bind_param('ii', $usuarioId, $rolId);

        // 4. Configuración del bucle
        $count = 0;
        $limite_docentes = 20;
        // Contraseña base encriptada con tu clase nativa Core\Encrypter
        $password_comun = Encrypter::encrypt('Docente2026.*');
        $avatar_defecto = 'default_avatar.png';

        // 5. Ejecución del bucle de 20 docentes
        for ($i = 0; $i < $limite_docentes; $i++) {
            
            // Generación de datos simulados dinámicos
            $genero_fake = Faker::genero();
            $nombre_completo = Faker::nombre($genero_fake);

            $nombre_array = array_filter(explode(' ', preg_replace('/\s+/', ' ', trim($nombre_completo))));
            $nombre_array = array_values($nombre_array);

            $primer_ap   = $nombre_array[0] ?? 'Apellido1';
            $segundo_ap  = $nombre_array[1] ?? '';
            $primer_nom  = $nombre_array[2] ?? 'Nombre1';
            $segundo_nom = $nombre_array[3] ?? '';

            $titulos = ['Lic.', 'MSc.', 'Dr.'];
            $titulo = $titulos[rand(0, 2)];
            $nombre_corto = trim($titulo . " " . $primer_nom . " " . $primer_ap);
            $cedula = Faker::cedula();

            // Asignación de variables para 'personas'
            $tipoDocId   = 1;
            $nacionalId  = 1;
            $dni         = $cedula;
            $primerAp    = $primer_ap;
            $segundoAp   = $segundo_ap;
            $primerName  = $primer_nom;
            $segundoName = $segundo_nom;
            $nombreCorto = $nombre_corto;
            $nombreCompl = $nombre_completo;
            $genero      = $genero_fake;

            $stmtPersona->execute();

            // Si se inserta o si ya existía debido al INSERT IGNORE
            if ($mysqli->affected_rows > 0) {
                $personaId = $mysqli->insert_id;
            } else {
                // Recuperar ID si la persona ya existía por DNI
                $resPers = $mysqli->query("SELECT id FROM personas WHERE dni = '{$cedula}' LIMIT 1");
                if ($rowP = $resPers->fetch_assoc()) {
                    $personaId = (int)$rowP['id'];
                } else {
                    continue; // Saltar iteración si ocurre un caso inesperado
                }
            }

            // Configurar variables para 'usuarios'
            $username    = strtolower($primer_nom . "." . $primer_ap . $personaId);
            $email       = $username . "@siae.edu.ec"; // Email institucional dinámico
            $passwordEnc = $password_comun;
            $avatar      = $avatar_defecto;

            $stmtUsuario->execute();

            // Recuperar el ID del usuario creado o existente (Misma lógica que usaste en Admin)
            if ($mysqli->affected_rows > 0) {
                $usuarioId = $mysqli->insert_id;
            } else {
                $resUser = $mysqli->query("SELECT id FROM usuarios WHERE email = '{$email}' LIMIT 1");
                if ($userRow = $resUser->fetch_assoc()) {
                    $usuarioId = (int)$userRow['id'];
                } else {
                    continue;
                }
            }

            // Vincular en la tabla pivotante
            $stmtPivot->execute();
            $count++;
        }

        $stmtPersona->close();
        $stmtUsuario->close();
        $stmtPivot->close();

        echo "     \e[32m✔ Se acoplaron con éxito $count Docentes en personas, usuarios y roles (Rol ID [{$rolId}]).\e[0m\n";
    }
}
