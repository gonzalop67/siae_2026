<?php

use Core\Faker;
use Core\Encrypter;

class PersonasEstudianteSeeder
{
    /**
     * Ejecuta el seeder para poblar la base de datos del ecosistema Estudiantes y Representantes.
     *
     * @param mysqli $mysqli Conexión nativa a la base de datos.
     * @return void
     */
    public function run(mysqli $mysqli)
    {
        $tablaPivot = 'usuarios_roles';

        echo " -> Buscando IDs de roles dinámicamente...\n";

        // 1. Buscar los IDs de los roles 'Estudiante' y 'Representante' por su slug/nombre
        $resRolEst = $mysqli->query("SELECT id FROM roles WHERE slug = 'estudiante' LIMIT 1");
        $rolEst = $resRolEst->fetch_assoc();

        $resRolRep = $mysqli->query("SELECT id FROM roles WHERE slug = 'representante' LIMIT 1");
        $rolRep = $resRolRep->fetch_assoc();

        if (!$rolEst || !$rolRep) {
            throw new \Exception("Error crítico: No se encontraron los roles 'Estudiante' o 'Representante'. Ejecuta RolSeeder primero.");
        }

        $rolEstudianteId  = (int)$rolEst['id'];
        $rolRepresentanteId = (int)$rolRep['id'];

        // 2. Preparar las sentencias SQL respetando tu esquema exacto
        $sqlPersona = "INSERT IGNORE INTO `personas` ( `tipo_documento_id`, `nacionalidad_id`, `dni`, `primer_apellido`, `segundo_apellido`, `primer_nombre`, `segundo_nombre`, `nombre_corto`, `nombre_completo`, `genero` ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $sqlUsuario = "INSERT IGNORE INTO `usuarios` ( `persona_id`, `username`, `email`, `password`, `avatar` ) VALUES (?, ?, ?, ?, ?)";
        $sqlPivot   = "INSERT IGNORE INTO `{$tablaPivot}` (`usuario_id`, `rol_id`) VALUES (?, ?)";

        // Sentencias específicas del ecosistema académico
        $sqlAlumno  = "INSERT IGNORE INTO `alumnos` (`persona_id`, `codigo_matricula`, `tipo_sangre`, `observaciones`) VALUES (?, ?, 'O+', 'Alumno registrado por lote automatizado.')";
        $sqlRep     = "INSERT IGNORE INTO `representantes` (`persona_id`, `ocupacion`) VALUES (?, ?)";
        $sqlRel     = "INSERT IGNORE INTO `alumno_representante` (`alumno_id`, `representante_id`, `parentesco`, `es_principal`) VALUES (?, ?, ?, 1)";

        $stmtPersona = $mysqli->prepare($sqlPersona);
        $stmtUsuario = $mysqli->prepare($sqlUsuario);
        $stmtPivot   = $mysqli->prepare($sqlPivot);
        $stmtAlumno  = $mysqli->prepare($sqlAlumno);
        $stmtRep     = $mysqli->prepare($sqlRep);
        $stmtRel     = $mysqli->prepare($sqlRel);

        if (!$stmtPersona || !$stmtUsuario || !$stmtPivot || !$stmtAlumno || !$stmtRep || !$stmtRel) {
            throw new \Exception("Error al preparar las consultas del ecosistema Estudiantes: " . $mysqli->error);
        }

        // 3. Vincular parámetros por referencia (Fuera del bucle)
        $tipoDocId = $nacionalId = 0;
        $dni = $primerAp = $segundoAp = $primerName = $segundoName = $nombreCorto = $nombreCompl = $genero = '';
        $stmtPersona->bind_param('iissssssss', $tipoDocId, $nacionalId, $dni, $primerAp, $segundoAp, $primerName, $segundoName, $nombreCorto, $nombreCompl, $genero);

        $personaId = 0;
        $username = $email = $passwordEnc = $avatar = '';
        $stmtUsuario->bind_param('issss', $personaId, $username, $email, $passwordEnc, $avatar);

        $usuarioId = $rolIdActual = 0;
        $stmtPivot->bind_param('ii', $usuarioId, $rolIdActual);

        $aluPersonaId = 0;
        $codigoMatricula = '';
        $stmtAlumno->bind_param('is', $aluPersonaId, $codigoMatricula);

        $repPersonaId = 0;
        $ocupacion = '';
        $stmtRep->bind_param('is', $repPersonaId, $ocupacion);

        $alumnoIdTable = $representanteIdTable = 0;
        $parentesco = '';
        $stmtRel->bind_param('iis', $alumnoIdTable, $representanteIdTable, $parentesco);

        // 4. Configuración del bucle
        $limite_lote = 30;
        $password_estudiante = Encrypter::encrypt('Estudiante2026.*');
        $password_representante = Encrypter::encrypt('Representante2026.*');
        $avatar_defecto = 'default_avatar.png';
        $ocupaciones = ['Comerciante', 'Ingeniero', 'Abogado', 'Docente', 'Médico', 'Empleado Privado'];
        $parentescos = ['Padre', 'Madre', 'Tutor Legal'];

        echo " -> Generando lote de {$limite_lote} estudiantes y representantes...\n";

        for ($i = 0; $i < $limite_lote; $i++) {

            // ==========================================
            // A) CREACIÓN DE PERSONA: ALUMNO
            // ==========================================
            $genero_fake = Faker::genero();
            $nombre_completo = Faker::nombre($genero_fake);
            $nombre_array = array_values(array_filter(explode(' ', preg_replace('/\s+/', ' ', trim($nombre_completo)))));

            $primer_ap = $nombre_array[0] ?? 'Estudiante';
            $segundo_ap = $nombre_array[1] ?? '';
            $primer_nom = $nombre_array[2] ?? 'Nombre';
            $segundo_nom = $nombre_array[3] ?? '';
            $cedula_alu = Faker::cedula();

            $tipoDocId = 1;
            $nacionalId = 1;
            $dni = $cedula_alu;
            $primerAp = $primer_ap;
            $segundoAp = $segundo_ap;
            $primerName = $primer_nom;
            $segundoName = $segundo_nom;
            $nombreCorto = trim($primer_nom . " " . $primer_ap);
            $nombreCompl = $nombre_completo;
            $genero = $genero_fake;
            $stmtPersona->execute();

            $alumnoPersonaId = ($mysqli->affected_rows > 0) ? $mysqli->insert_id : $this->getPersonaIdByDni($mysqli, $cedula_alu);
            if (!$alumnoPersonaId) continue;

            // Registrar en tabla 'alumnos'
            $codigoMatricula = "MAT-2026-" . str_pad(($i + 1), 4, "0", STR_PAD_LEFT);
            $aluPersonaId = $alumnoPersonaId;
            $stmtAlumno->execute();

            $alumnoIdTable = ($mysqli->affected_rows > 0) ? $mysqli->insert_id : $this->getAlumnoIdByPersona($mysqli, $alumnoPersonaId);

            // Crear cuenta de Usuario para el Alumno
            $username = strtolower("alu." . $primer_nom . "." . $primer_ap . $alumnoIdTable);
            $email = $username . "@siae.edu.ec";
            $personaId = $alumnoPersonaId;
            $passwordEnc = $password_estudiante;
            $avatar = $avatar_defecto;
            $stmtUsuario->execute();

            $usuarioId = ($mysqli->affected_rows > 0) ? $mysqli->insert_id : $this->getUsuarioIdByEmail($mysqli, $email);

            // Asignar Rol Estudiante
            $rolIdActual = $rolEstudianteId;
            $stmtPivot->execute();

            // ==========================================
            // B) CREACIÓN DE PERSONA: REPRESENTANTE
            // ==========================================
            $genero_rep = Faker::genero();
            $nombre_comp_rep = Faker::nombre($genero_rep);
            $nombre_array_rep = array_values(array_filter(explode(' ', preg_replace('/\s+/', ' ', trim($nombre_comp_rep)))));

            $primer_ap_rep = $nombre_array_rep[0] ?? 'Representante';
            $segundo_ap_rep = $nombre_array_rep[1] ?? '';
            $primer_nom_rep = $nombre_array_rep[2] ?? 'Tutor';
            $segundo_nom_rep = $nombre_array_rep[3] ?? '';
            $cedula_rep = Faker::cedula();

            $dni = $cedula_rep;
            $primerAp = $primer_ap_rep;
            $segundoAp = $segundo_ap_rep;
            $primerName = $primer_nom_rep;
            $segundoName = $segundo_nom_rep;
            $nombreCorto = trim($primer_nom_rep . " " . $primer_ap_rep);
            $nombreCompl = $nombre_comp_rep;
            $genero = $genero_rep;
            $stmtPersona->execute();

            $repPersonaIdReal = ($mysqli->affected_rows > 0) ? $mysqli->insert_id : $this->getPersonaIdByDni($mysqli, $cedula_rep);
            if (!$repPersonaIdReal) continue;

            // Registrar en tabla 'representantes'
            $repPersonaId = $repPersonaIdReal;
            $ocupacion = $ocupaciones[array_rand($ocupaciones)];
            $stmtRep->execute();

            $representanteIdTable = ($mysqli->affected_rows > 0) ? $mysqli->insert_id : $this->getRepresentanteIdByPersona($mysqli, $repPersonaIdReal);

            // Crear cuenta de Usuario para el Representante
            $username = strtolower("rep." . $primer_nom_rep . "." . $primer_ap_rep . $representanteIdTable);
            $email = $username . "@mail.com";
            $personaId = $repPersonaIdReal;
            $passwordEnc = $password_representante;
            $stmtUsuario->execute();

            $usuarioId = ($mysqli->affected_rows > 0) ? $mysqli->insert_id : $this->getUsuarioIdByEmail($mysqli, $email);

            // Asignar Rol Representante
            $rolIdActual = $rolRepresentanteId;
            $stmtPivot->execute();

            // ==========================================
            // C) VÍNCULO INTERMEDIO (ALUMNO - REPRESENTANTE)
            // ==========================================
            $parentesco = $parentescos[array_rand($parentescos)];
            $stmtRel->execute();
        }

        // 5. Cerrar todas las sentencias preparadas
        $stmtPersona->close();
        $stmtUsuario->close();
        $stmtPivot->close();
        $stmtAlumno->close();
        $stmtRep->close();
        $stmtRel->close();

        echo " \e[32m✔ Se acopló con éxito el ecosistema escolar: {$limite_lote} Alumnos y {$limite_lote} Representantes vinculados con usuarios y roles.\e[0m\n";
    }


    // Métodos auxiliares para resolver IDs en caso de registros duplicados (INSERT IGNORE)
    private function getPersonaIdByDni(mysqli $mysqli, string $dni): int
    {
        $res = $mysqli->query("SELECT id FROM personas WHERE dni = '{$dni}' LIMIT 1");
        return ($row = $res->fetch_assoc()) ? (int)$row['id'] : 0;
    }

    private function getAlumnoIdByPersona(mysqli $mysqli, int $personaId): int
    {
        $res = $mysqli->query("SELECT id FROM alumnos WHERE persona_id = {$personaId} LIMIT 1");
        return ($row = $res->fetch_assoc()) ? (int)$row['id'] : 0;
    }

    private function getRepresentanteIdByPersona(mysqli $mysqli, int $personaId): int
    {
        $res = $mysqli->query("SELECT id FROM representantes WHERE persona_id = {$personaId} LIMIT 1");
        return ($row = $res->fetch_assoc()) ? (int)$row['id'] : 0;
    }

    private function getUsuarioIdByEmail(mysqli $mysqli, string $email): int
    {
        $res = $mysqli->query("SELECT id FROM usuarios WHERE email = '{$email}' LIMIT 1");
        return ($row = $res->fetch_assoc()) ? (int)$row['id'] : 0;
    }
}
