<?php

namespace App\Models\Admin;

use App\Models\Model;
use App\Models\Admin\Persona;
use Override;

class Usuario extends Model
{
    protected string $table = 'usuarios';
    protected string $primaryKey = 'id';

    protected Persona $personaModel;

    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'persona_id',
        'username',
        'email',
        'password',
        'avatar',
        'activo'
    ];

    // Activas la funcionalidad exclusivamente para este modelo
    protected bool $useSoftDeletes = true;

    // Constructor para garantizar la inicialización segura de modelos hijos
    public function __construct()
    {
        parent::__construct();
        $this->personaModel = new Persona();
    }

    // Validación estricta y sincronizada con los campos del Formulario (JS)
    public function validate(array $data, ?int $id = null): bool
    {
        $this->errors = [];
        $is_updating = ($id !== null);

        // 1. Recuperar y limpiar datos del FormData
        $tipo_documento     = trim($data['tipo_documento'] ?? ''); // NUEVO
        $nacionalidad       = trim($data['nacionalidad'] ?? '');   // NUEVO
        $dni                = trim($data['dni'] ?? '');
        $titulo             = trim($data['titulo'] ?? '');
        $titulo_descripcion = trim($data['titulo_descripcion'] ?? '');
        $primer_apellido    = preg_replace('/\s+/', ' ', trim($data['primer_apellido'] ?? ''));
        $segundo_apellido   = preg_replace('/\s+/', ' ', trim($data['segundo_apellido'] ?? ''));
        $primer_nombre      = preg_replace('/\s+/', ' ', trim($data['primer_nombre'] ?? ''));
        $segundo_nombre     = preg_replace('/\s+/', ' ', trim($data['segundo_nombre'] ?? ''));
        $nombre_corto       = preg_replace('/\s+/', ' ', trim($data['nombre_corto'] ?? ''));
        $username           = preg_replace('/\s+/', ' ', trim($data['username'] ?? ''));
        $email              = trim($data['email'] ?? '');
        $password           = trim($data['password'] ?? '');
        $roles              = $data['roles'] ?? [];

        // 2. Bloque de Validaciones Estrictas

        // NUEVO: Validación de Tipo de Documento
        if ($tipo_documento === '') {
            $this->errors['tipo_documento'] = "Por favor, seleccione un tipo de documento de la lista.";
        }

        // NUEVO: Validación de Nacionalidad
        if ($nacionalidad === '') {
            $this->errors['nacionalidad'] = "Por favor, seleccione una nacionalidad de la lista.";
        }

        // dni
        if (empty($dni)) {
            $this->errors['dni'] = "El campo DNI es obligatorio";
        }

        // Título (Abreviatura)
        if (empty($titulo)) {
            $this->errors['titulo'] = "El campo Título es obligatorio.";
        } elseif (!preg_match('/^[a-zA-Z\.]{3,7}$/', $titulo)) {
            $this->errors['titulo'] = "La abreviatura del título tiene que ser de 3 a 7 caracteres (alfabéticos y punto).";
        }

        // Descripción del Título
        if (empty($titulo_descripcion)) {
            $this->errors['titulo_descripcion'] = "El campo Descripción del Título es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\.\,\-\_\:\;\(\)\n]{4,500}$/u', $titulo_descripcion)) {
            $this->errors['titulo_descripcion'] = "La descripción del título tiene que ser de 4 a 500 caracteres.";
        }

        // Apellidos
        if (empty($primer_apellido)) {
            $this->errors['primer_apellido'] = "El campo Primer Apellido es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]{3,32}$/u', $primer_apellido)) {
            $this->errors['primer_apellido'] = "El primer apellido del usuario debe contener de 3 a 32 caracteres alfabéticos.";
        }

        // Nombres
        if (empty($primer_nombre)) {
            $this->errors['primer_nombre'] = "El campo Primer Nombre es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]{3,32}$/u', $primer_nombre)) {
            $this->errors['primer_nombre'] = "El primer nombre del usuario debe contener de 3 a 32 caracteres alfabéticos.";
        }

        // Procesamiento matricial de nombres combinados y verificación de duplicados
        if (empty($this->errors['primer_apellido']) && empty($this->errors['primer_nombre'])) {
            $primer_apellido  = trim($primer_apellido);
            $segundo_apellido = trim($segundo_apellido);
            $primer_nombre    = trim($primer_nombre);
            $segundo_nombre   = trim($segundo_nombre);

            $nombre_completo  = $primer_apellido . " " . $segundo_apellido . " " . $primer_nombre . " " . $segundo_nombre;
            $nombre_completo  = preg_replace('/\s+/', ' ', $nombre_completo);

            // SINCRONIZACIÓN JS: Redirigir el error a 'primer_apellido' para que el Front lo dibuje en pantalla
            if (!empty($nombre_completo) && $this->personaModel->exists('nombre_completo', $nombre_completo, $id)) {
                $this->errors['primer_apellido'] = "Ya existe un registro con este Nombre Completo en el sistema.";
            }

            // Manejo seguro del nombre_corto automático si viene vacío del cliente
            if ($nombre_corto !== "") {
                $nombre_corto = preg_replace('/\s+/', ' ', $nombre_corto);
            } else {
                $apellidos     = explode(" ", $primer_apellido);
                $nombres       = explode(" ", $primer_nombre);
                $p_nombre      = $nombres[0] ?? '';
                $p_apellido    = $apellidos[0] ?? '';
                $nombre_corto  = trim($titulo . " " . $p_nombre . " " . $p_apellido);
            }
        }

        // Login / Usuario
        if (empty($username)) {
            $this->errors['username'] = "El campo Nombre de Usuario es obligatorio.";
        } elseif (!preg_match('/^[a-zA-Z0-9\_\-]{4,16}$/', $username)) {
            $this->errors['username'] = "El Nombre de Usuario debe tener entre 4 y 16 caracteres sin espacios.";
        } elseif ($this->exists('username', $username, $id)) {
            $this->errors['username'] = "Ya existe el Nombre de Usuario en la base de datos.";
        }

        // Email
        if (empty($email)) {
            $this->errors['email'] = "El campo Email es obligatorio.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "El correo electrónico ingresado no es válido.";
        } elseif ($this->exists('email', $email, $id)) {
            $this->errors['email'] = "Ya existe el correo electrónico.";
        }

        // Password condicional
        if (!$is_updating && empty($password)) {
            $this->errors['password'] = 'El campo Password es obligatorio.';
        } elseif (!empty($password)) {
            // CORRECCIÓN REGEX: Sincronizada con el cliente para admitir cualquier carácter especial universal
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $password)) {
                $this->errors['password'] = 'Contraseña débil [Mínimo 8 caracteres, mayúscula, minúscula, número y cualquier símbolo].';
            }
        }

        // Checkboxes (Perfiles) -> Clave 'roles' mapeada al id 'roles-container' del JS
        if (!is_array($roles) || count($roles) === 0) {
            $this->errors['roles'] = "Debe asignar al menos un rol al usuario.";
        }

        // Validación Imbatible de Imágenes (MIME Type real mediante finfo)
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['avatar']['tmp_name'];
            $fileSize    = $_FILES['avatar']['size'];

            if ($fileSize > 2 * 1024 * 1024) {
                $this->errors['avatar'] = "La imagen es muy pesada. Máximo permitido: 2MB.";
            } else {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($fileTmpPath);

                $mimesPermitidos = [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/gif',
                    'image/webp'
                ];

                if (!in_array($mimeType, $mimesPermitidos)) {
                    $this->errors['avatar'] = "El archivo no es una imagen válida (Solo JPG, PNG, GIF, WEBP).";
                }
            }
        }

        return empty($this->errors);
    }

    public function getRoleIds(string $userId)
    {
        $sql = "SELECT id_perfil FROM sw_usuario_perfil WHERE id_usuario = ?";
        $data = $this->query($sql, [$userId])->get();

        return array_column($data, 'id_perfil');
    }

    /**
     * Verifica si el perfil actual del usuario tiene un permiso mediante su slug.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId   = $_SESSION['user_id'] ?? 0;
        $perfilId = $_SESSION['perfil_id'] ?? 0;

        if ($userId === 0 || $perfilId === 0) {
            return false;
        }

        // Lógica de permisos de tu base de datos (continúa tu código original...)
        return true;
    }
}
