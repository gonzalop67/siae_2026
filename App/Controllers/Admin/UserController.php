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

use Override;

class UserController extends Controller
{
    protected Role $roleModel;
    protected Usuario $userModel;
    protected Persona $personaModel;
    protected RolUsuario $roleUserModel;
    protected Nacionalidad $nacionalidadModel;
    protected TipoDocumento $tipoDocumentoModel;

    #[Override]
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
        $roles = $this->roleModel->orderBy('nombre')->get();
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
            'titulo_descripcion' => trim($input['titulo_descripcion'] ?? ''),
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
     * Muestra un recurso específico.
     */
    public function show($id)
    {
        // $data = $this->model->find($id);
        // return $this->view('admin.user.show', compact('data'));
    }

    /**
     * Muestra el formulario para editar un recurso específico.
     */
    public function edit($id)
    {
        $title = 'Editar UserController';
        // $data = $this->model->find($id);
        // return $this->view('admin.user.edit', compact('data', 'title'));
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update($id)
    {
        // $this->model->update($id, $_POST);
        // return redirect('/user');
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy($id)
    {
        // $this->model->delete($id);
        // return redirect('/user');
    }
}
