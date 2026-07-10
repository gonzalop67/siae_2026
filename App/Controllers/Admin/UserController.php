<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

use Core\Encrypter;

use App\Models\Admin\Role;
use App\Models\Admin\Persona;
use App\Models\Admin\Usuario;
use App\Models\Admin\RolUsuario;
use App\Models\Admin\Nacionalidad;
use App\Models\Admin\TipoDocumento;

class UserController extends Controller
{
    protected Role $roleModel;
    protected Usuario $userModel;
    protected Persona $personaModel;
    protected RolUsuario $roleUserModel;
    protected Nacionalidad $nacionalidadModel;
    protected TipoDocumento $tipoDocumentoModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->roleModel = new Role;
        $this->userModel = new Usuario;
        $this->personaModel = new Persona;
        $this->roleUserModel = new RolUsuario;
        $this->nacionalidadModel = new Nacionalidad;
        $this->tipoDocumentoModel = new TipoDocumento;
    }

    public function index()
    {
        $title = "Lista de Usuarios";
        $search = isset($_GET['search']) ? trim($_GET['search']) : "";

        // Aseguramos limpiar cualquier residuo estructural previo del modelo
        $this->userModel->where = "";
        $this->userModel->values = [];

        // 1. Configuramos el select, el join relacional y el ordenamiento
        $query = $this->userModel
            ->select('usuarios.*', 'personas.nombre_completo')
            ->join('personas', 'usuarios.persona_id', '=', 'personas.id');

        // 2. Aplicamos la búsqueda usando paréntesis explícitos si el usuario escribe algo
        if ($search !== "") {
            $likeSearch = '%' . $search . '%';
            // Calificamos explícitamente las tablas para evitar errores de ambigüedad en el WHERE
            $query->where = "(personas.nombre_completo LIKE ? OR usuarios.username LIKE ?)";
            $query->values = [$likeSearch, $likeSearch];
        }

        // 3. Paginar los resultados obtenidos
        $usuarios = $query->orderBy('personas.nombre_completo', 'ASC')
            ->paginate(5);

        // 🔥 AGREGA ESTA LÍNEA DE PRUEBA:
        // show($usuarios);
        // die();

        return $this->view('admin.usuarios.index', compact('usuarios', 'title'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $title = "Nuevo Usuario";
        $roles = $this->roleModel->orderBy('pe_nombre')->get();
        $tipos_documentos = $this->tipoDocumentoModel->orderBy('id')->get();
        $nacionalidades = $this->nacionalidadModel->orderBy('id')->get();

        return $this->view('admin.usuarios.create', compact('title', 'roles', 'tipos_documentos', 'nacionalidades'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store()
    {
        // 1. CAPTURA EN CRUDO: Evitamos pérdidas de datos de los selects o inputs por filtros estrictos
        $input = $_POST;

        if (!$this->userModel->validate($input)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->userModel->errors
            ]);
        }

        // 2. Encriptación para contraseñas utilizando tu clase estática Encrypter
        $passwordHash = Encrypter::encrypt($input['password'] ?? '');

        // 3. Limpieza y normalización matricial de textos
        $primer_apellido  = preg_replace('/\s+/', ' ', trim($input['primer_apellido'] ?? ''));
        $segundo_apellido = preg_replace('/\s+/', ' ', trim($input['segundo_apellido'] ?? ''));
        $primer_nombre    = preg_replace('/\s+/', ' ', trim($input['primer_nombre'] ?? ''));
        $segundo_nombre   = preg_replace('/\s+/', ' ', trim($input['segundo_nombre'] ?? ''));
        $nombre_completo  = trim($primer_apellido . " " . $segundo_apellido . " " . $primer_nombre . " " . $segundo_nombre);

        // 4. PLANIFICACIÓN DE LA IMAGEN: Calculamos el nombre del archivo, pero NO lo subimos aún
        $imageName = 'default.png';
        $tieneImagen = (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK);

        if ($tieneImagen) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $imageName = 'user_' . uniqid() . '_' . time() . '.' . $ext;
        }

        // 5. Preparación del set de datos para la tabla personas (Sincronizado con tus nuevos selects)
        $datos_persona = [
            'tipo_documento_id'  => (!empty($input['tipo_documento'])) ? (int)$input['tipo_documento'] : null,
            'nacionalidad_id'    => (!empty($input['nacionalidad'])) ? (int)$input['nacionalidad'] : null,
            'dni'                => isset($input['dni']) ? trim($input['dni']) : null, // ◄ Captura pura del DNI
            'primer_nombre'      => $primer_nombre,
            'segundo_nombre'     => $segundo_nombre,
            'primer_apellido'    => $primer_apellido,
            'segundo_apellido'   => $segundo_apellido,
            'nombre_corto'       => trim($input['nombre_corto'] ?? ''),
            'nombre_completo'    => $nombre_completo,
            'titulo'             => trim($input['titulo'] ?? ''),
            'descripcion_titulo' => trim($input['titulo_descripcion'] ?? ''),
            'genero'             => trim($input['genero'] ?? ''),
        ];

        $roles = $input['roles'] ?? [];
        $rutaArchivoSubido = ''; // Guardará la ruta física si se llega a subir para control de rollback

        // 6. PERSISTENCIA CON MANEJO DE TRANSACCIONES NATIVAS REALES
        try {
            // 1. INICIAR TRANSACCIÓN SQL
            $this->userModel->beginTransaction();

            // Ejecutamos la creación en la base de datos de la persona
            $persona = $this->personaModel->create($datos_persona);

            // Captura del ID a través de tu método público
            $idPersona = $this->personaModel->getInsertId();
            if ($idPersona === 0 && is_array($persona)) {
                $idPersona = (int)($persona['id'] ?? 0);
            }

            if ($idPersona === 0) {
                throw new \Exception("Error al procesar el identificador único de nueva persona (ID devolvió 0).");
            }

            // Datos del nuevo Usuario vinculados a la persona recién creada
            $datos_usuario = [
                'persona_id' => $idPersona, // ◄ Vinculación relacional correcta
                'username'   => trim($input['username'] ?? ''),
                'email'      => trim($input['email'] ?? ''),
                'password'   => $passwordHash,
                'avatar'     => $imageName,
                'activo'     => $input['activo'] ?? '1'
            ];

            // Ejecutamos la creación en la base de datos
            $usuario = $this->userModel->create($datos_usuario);

            // Captura del ID a través de tu método público
            $idUsuario = $this->userModel->getInsertId();
            if ($idUsuario === 0 && is_array($usuario)) {
                $idUsuario = (int)($usuario['id'] ?? 0);
            }

            if ($idUsuario === 0) {
                throw new \Exception("Error al procesar el identificador único del nuevo usuario (ID devolvió 0).");
            }

            // 2. CARGA FÍSICA DE LA IMAGEN: Ahora que la BD aceptó todo, movemos el archivo
            if ($tieneImagen) {
                // __DIR__ está en App/Controllers/Admin (subimos 3 niveles a la raíz)
                $raizProyecto = dirname(__DIR__, 3);
                $directorioUploads = $raizProyecto . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';

                if (!is_dir($directorioUploads)) {
                    mkdir($directorioUploads, 0777, true);
                }

                $destino = $directorioUploads . DIRECTORY_SEPARATOR . $imageName;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destino)) {
                    $rutaArchivoSubido = $destino; // Almacenamos la ruta para control del catch
                } else {
                    throw new \Exception("No se pudo guardar físicamente la imagen en el servidor. Verifique rutas.");
                }
            }

            // 3. Sincronizar los roles (Bajo la misma transacción SQL)
            $this->roleUserModel->sync($idUsuario, $roles);

            // 4. CONFIRMAR CAMBIOS SI TODO SALIÓ BIEN
            $this->userModel->commit();

            return json_encode([
                'ok' => true,
                'mensaje' => 'Usuario procesado con éxito.'
            ]);
        } catch (\Throwable $e) {
            // 5. REVERTIR TRANSACCIÓN SQL EN CASO DE FALLAS
            $this->userModel->rollBack();

            // LIMPIEZA DE BASURA EN DISCO: Borramos el archivo físico para evitar imágenes huérfanas
            if (!empty($rutaArchivoSubido) && file_exists($rutaArchivoSubido)) {
                unlink($rutaArchivoSubido);
            }

            return json_encode([
                'ok' => false,
                'mensaje' => "Ocurrió un error inesperado: " . $e->getMessage()
            ]);
        }
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit(int $id)
    {
        $title = "Editar Usuario";
        $usuario = $this->userModel
            ->select('usuarios.id AS id_usuario', 
            'persona_id', 
            'username', 
            'email', 
            'password', 
            'activo', 
            'avatar', 
            'personas.*')
            ->join('personas', 'usuarios.persona_id', '=', 'personas.id')
            ->where('usuarios.id', $id)
            ->first();
        $password = Encrypter::decrypt($usuario['password'] ?? '');
        $usuario['password'] = $password;
        // show($usuario);
        // die();
        $roles = $this->roleModel->orderBy('nombre')->get();
        $userRoles = $this->userModel->getRoleIds($id);
        $tipos_documentos = $this->tipoDocumentoModel->orderBy('id')->get();
        $nacionalidades = $this->nacionalidadModel->orderBy('id')->get();

        return $this->view('admin.usuarios.edit', compact('title', 'usuario', 'userRoles', 'roles', 'tipos_documentos', 'nacionalidades'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update(int $id)
    {
        // 1. Entrada de datos pura (Confiamos en tu $_POST nativo)
        $input = $_POST;

        // =====================================================================
        // 🔥 RESCATE CRÍTICO DE ID DESDE EL FRONTEND
        // =====================================================================
        // Si tu Core de rutas no pasa el $id, lo leemos desde los inputs que envía crear.js
        // (Busca id_usuario o id según cómo se llame tu campo input hidden en el HTML)
        $id_actual_update = (int)($id ?? $input['id_usuario'] ?? $input['id'] ?? 0);

        if ($id_actual_update === 0) {
            return json_encode([
                'ok' => false,
                'mensaje' => 'Error crítico de scope: El controlador no pudo rescatar el ID del usuario.'
            ]);
        }
        // =====================================================================

        // 2. Validación lógica en el modelo (LE PASAMOS LA VARIABLE BLINDADA)
        if (!$this->userModel->validate($input, $id_actual_update)) {
            return json_encode([
                'ok' => false,
                'errors' => $this->userModel->errors
            ]);
        }

        // 3. Obtener el registro actual del usuario para saber su persona_id y avatar antiguo
        // Usamos tu método query y first para no romper el buffer
        $this->userModel->query("SELECT persona_id, avatar, password FROM usuarios WHERE id = ?", [$id], 'i');
        $usuario_actual = $this->userModel->first();

        if (!$usuario_actual) {
            return json_encode([
                'ok' => false,
                'mensaje' => 'El usuario que intenta actualizar no existe en el sistema.'
            ]);
        }

        $idPersona = (int)$usuario_actual['persona_id'];
        $avatar_antiguo = $usuario_actual['avatar'];

        // 4. Tratamiento condicional del Password (Si viene vacío, conserva el actual)
        $passwordHash = $usuario_actual['password'];
        if (!empty($input['password'])) {
            $passwordHash = Encrypter::encrypt($input['password']);
        }

        // 5. Limpieza y normalización matricial de textos
        $primer_apellido  = preg_replace('/\s+/', ' ', trim($input['primer_apellido'] ?? ''));
        $segundo_apellido = preg_replace('/\s+/', ' ', trim($input['segundo_apellido'] ?? ''));
        $primer_nombre    = preg_replace('/\s+/', ' ', trim($input['primer_nombre'] ?? ''));
        $segundo_nombre   = preg_replace('/\s+/', ' ', trim($input['segundo_nombre'] ?? ''));
        $nombre_completo  = trim($primer_apellido . " " . $segundo_apellido . " " . $primer_nombre . " " . $segundo_nombre);

        // 6. PLANIFICACIÓN DEL AVATAR NUEVO
        $imageName = $avatar_antiguo; // Por defecto mantiene la imagen que ya tenía
        $tieneImagenNueva = (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK);

        if ($tieneImagenNueva) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $imageName = 'user_' . uniqid() . '_' . time() . '.' . $ext;
        }

        // 7. Estructurar arreglos masivos de actualización ($fillable)
        $datos_persona = [
            'tipo_documento_id'  => (!empty($input['tipo_documento'])) ? (int)$input['tipo_documento'] : null,
            'nacionalidad_id'    => (!empty($input['nacionalidad'])) ? (int)$input['nacionalidad'] : null,
            'dni'                => isset($input['dni']) ? trim($input['dni']) : null,
            'primer_nombre'      => $primer_nombre,
            'segundo_nombre'     => $segundo_nombre,
            'primer_apellido'    => $primer_apellido,
            'segundo_apellido'   => $segundo_apellido,
            'nombre_corto'       => trim($input['nombre_corto'] ?? ''),
            'nombre_completo'    => $nombre_completo,
            'titulo'             => trim($input['titulo'] ?? ''),
            'descripcion_titulo' => trim($input['descripcion_titulo'] ?? $input['titulo_descripcion'] ?? ''),
            'genero'             => trim($input['genero'] ?? ''),
        ];

        $datos_usuario = [
            'username' => trim($input['username'] ?? ''),
            'email'    => trim($input['email'] ?? ''),
            'password' => $passwordHash,
            'avatar'   => $imageName,
            'activo'   => $input['activo'] ?? '1'
        ];

        $roles = $input['roles'] ?? [];
        $rutaArchivoNuevoSubido = '';

        // 8. CICLO DE PERSISTENCIA TRANSACCIONAL (UPDATE)
        try {
            // INICIAR TRANSACCIÓN SQL COMPARTIDA
            $this->userModel->beginTransaction();

            // A. Actualizar la tabla personas filtrando por su id_persona
            // (Asegúrate de que tu método update() del modelo reciba los datos y el ID)
            $this->personaModel->where("id = ?", $idPersona)->update($idPersona, $datos_persona);

            // B. Actualizar la tabla usuarios filtrando por el ID de la URL
            $this->userModel->where("id = ?", $id)->update($id, $datos_usuario);

            // C. CARGA FÍSICA DEL ARCHIVO NUEVO (Si el usuario subió otra foto)
            if ($tieneImagenNueva) {
                $raizProyecto = dirname(__DIR__, 3);
                $directorioUploads = $raizProyecto . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';
                
                if (!is_dir($directorioUploads)) {
                    mkdir($directorioUploads, 0777, true);
                }

                $destino = $directorioUploads . DIRECTORY_SEPARATOR . $imageName;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destino)) {
                    $rutaArchivoNuevoSubido = $destino;
                } else {
                    throw new \Exception("No se pudo guardar la nueva imagen en el servidor.");
                }
            }

            // D. Sincronizar los roles (Remueve los perfiles viejos y asienta los nuevos)
            $this->roleUserModel->sync($id, $roles);

            // E. CONFIRMAR TODOS LOS CAMBIOS EN LA BASE DE DATOS
            $this->userModel->commit();

            // F. LIMPIEZA POST-COMMIT: Si todo se guardó bien y había una foto nueva, borramos la vieja
            if ($tieneImagenNueva && !empty($avatar_antiguo) && $avatar_antiguo !== 'default.png') {
                $raizProyecto = dirname(__DIR__, 3);
                $rutaFotoVieja = $raizProyecto . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $avatar_antiguo;
                if (file_exists($rutaFotoVieja)) {
                    unlink($rutaFotoVieja);
                }
            }

            return json_encode([
                'ok' => true,
                'mensaje' => 'Usuario y datos personales actualizados con éxito.'
            ]);

        } catch (\Throwable $e) {
            // REVERTIR CAMBIOS EN CASO DE FALLAS
            $this->userModel->rollBack();

            // Si la foto nueva se alcanzó a subir pero el commit falló, la borramos para no dejar basura
            if (!empty($rutaArchivoNuevoSubido) && file_exists($rutaArchivoNuevoSubido)) {
                unlink($rutaArchivoNuevoSubido);
            }

            return json_encode([
                'ok' => false,
                'mensaje' => "Error crítico al actualizar: " . $e->getMessage()
            ]);
        }
    }

    public function delete(int $id)
    {
        header('Content-Type: application/json');

        try {
            $eliminado = $this->userModel->delete($id);

            if ($eliminado) {
                return json_encode([
                    'success' => true,
                    'message' => 'El registro ha sido eliminado correctamente.'
                ]);
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'No se encontró el registro o ya fue eliminado.'
                ]);
            }
        } catch (\Throwable $e) {
            return json_encode([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }

    public function wastebasket()
    {
        $title = "Papelera de Usuarios";
        $search = isset($_GET['search']) ? $_GET['search'] : "";

        // Aseguramos limpiar cualquier residuo estructural previo del modelo
        $this->userModel->where = "";
        $this->userModel->values = [];

        // 1. Iniciamos el filtro de borrados lógicos
        $query = $this->userModel->onlyTrashed();

        // 1. Configuramos el select, el join relacional y el ordenamiento
        $query = $this->userModel
            ->select('usuarios.*', 'personas.nombre_completo')
            ->join('personas', 'usuarios.persona_id', '=', 'personas.id');

        if ($search !== "") {
            // 2. Asignamos el WHERE explícito calificando las columnas con su tabla
            $likeSearch = '%' . $search . '%';
            // Calificamos explícitamente las tablas para evitar errores de ambigüedad en el WHERE
            $query->where = "(personas.nombre_completo LIKE ? OR usuarios.username LIKE ?)";
            $query->values = [$likeSearch, $likeSearch];
        }

        // 3. Ordenamos y paginamos de forma nativa
        $users = $query->orderBy('deleted_at', 'DESC')->paginate(5);

        // 4. Renderizamos la vista de la papelera
        return $this->view('admin.usuarios.wastebasket', compact('users', 'title'));
    }

    // Método para restaurar un usuario (Botón Verde)
    public function restore(int $id)
    {
        header('Content-Type: application/json');
        try {
            // Llama al método restore() que añadimos en la clase Model
            $restaurado = $this->userModel->restore($id);

            if ($restaurado) {
                echo json_encode(['success' => true, 'message' => 'El usuario ha sido restaurado con éxito.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo restaurar el usuario.']);
            }
        } catch (\Throwable $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Método para eliminación física definitiva (Botón Rojo)
    public function destroy(int $id)
    {
        header('Content-Type: application/json');

        try {
            // 1. Buscamos al usuario en la base de datos antes de borrarlo
            $usuario = $this->userModel->withTrashed()->find($id);

            if (!$usuario) {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Atención',
                    'mensaje' => 'El usuario no existe en el sistema.',
                    'estado'  => 'warning'
                ]);
                exit;
            }

            // Guardamos el nombre de la foto que está en la base de datos
            $fotoNombre = !empty($usuario['us_foto']) ? $usuario['us_foto'] : 'no-disponible.png';

            // 2. Ejecutamos la eliminación física definitiva en la base de datos
            $resultado = $this->userModel->forceDelete($id);

            if ($resultado) {
                // 3. Si se borró con éxito de la base de datos, procedemos a borrar el archivo físico
                if ($fotoNombre !== 'no-disponible.png' && $fotoNombre !== 'default.png') {
                    $rutaFisicaFoto = dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/' . $fotoNombre;

                    if (file_exists($rutaFisicaFoto)) {
                        unlink($rutaFisicaFoto);
                    }
                }

                echo json_encode([
                    'success' => true,
                    'titulo'  => '¡Completado!',
                    'mensaje' => 'El usuario ha sido eliminado permanentemente del sistema.',
                    'estado'  => 'success'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Error',
                    'mensaje' => 'No se pudo eliminar el registro de la base de datos.',
                    'estado'  => 'error'
                ]);
            }
        } catch (\mysqli_sql_exception $e) {
            // CAPTURA EXITOSA: Ahora que removimos el die(), el catch atrapa el error 1451 perfectamente
            if ($e->getCode() === 1451) {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'No se puede eliminar',
                    'mensaje' => 'El usuario tiene registros vinculados. Debe reasignar o borrar esas dependencias antes de eliminarlo de forma definitiva.',
                    'estado'  => 'error'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'titulo'  => 'Error de Base de Datos',
                    'mensaje' => 'Fallo en la consulta: ' . $e->getMessage(),
                    'estado'  => 'error'
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'titulo'  => 'Error inesperado',
                'mensaje' => $e->getMessage(),
                'estado'  => 'error'
            ]);
        }
        exit;
    }

    public function roles(int $id)
    {
        // 1. El usuario que estamos editando
        $usuario = $this->userModel
            ->select('usuarios.id AS id_usuario', 
            'persona_id', 
            'username', 
            'email', 
            'password', 
            'activo', 
            'avatar', 
            'personas.*')
            ->join('personas', 'usuarios.persona_id', '=', 'personas.id')
            ->where('usuarios.id', $id)
            ->first();

        // 2. TODOS los roles que existen en el sistema (para los checkboxes)
        // Asumo que tienes un roleModel o tabla 'roles'
        $roles = $this->roleModel
            ->orderBy('nombre')
            ->get();

        // 3. Los IDs de los roles que este Usuario ya tiene asignados
        // Esta es la simulación real de: $user->roles->pluck('id')->toArray();
        $userRoles = $this->userModel->getRoleIds($id);

        $title = "Asignación de Roles";

        return $this->view('admin.usuarios.roles', compact('title', 'usuario', 'roles', 'userRoles'));
    }

    public function updateRoles(int $id)
    {
        // $id es el id del usuario
        $RoleIds = $_POST['roles'];
        $this->roleUserModel->sync($id, $RoleIds);

        // Mensaje de éxito
        $_SESSION['mensaje'] = "Roles actualizados satisfactoriamente.";
        $_SESSION['tipo'] = "success";
        $_SESSION['icono'] = "check";
        redireccionar('/usuarios/' . $id . '/roles');
    }
}
