<?php

namespace App\Models\Admin;

use App\Models\Model;

class Usuario extends Model
{
    protected string $table = 'usuarios';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'username',
        'email',
        'password',
        'avatar',
        'activo'
    ];

    // Activas la funcionalidad exclusivamente para este modelo
    protected bool $useSoftDeletes = true;

    // CORRECCIÓN: Tipado estricto ?int para el ID
    public function validate(array $data, ?int $id = null): bool
    {
        $this->errors = [];
        $is_updating = ($id !== null);

        // 1. Recuperar y limpiar datos del FormData
        $us_titulo             = trim($data['us_titulo'] ?? '');
        $us_titulo_descripcion = trim($data['us_titulo_descripcion'] ?? '');
        $us_apellidos          = preg_replace('/\s+/', ' ', trim($data['us_apellidos'] ?? ''));
        $us_nombres            = preg_replace('/\s+/', ' ', trim($data['us_nombres'] ?? ''));
        $us_shortname          = trim($data['us_shortname'] ?? '');
        $us_login              = preg_replace('/\s+/', ' ', trim($data['us_login'] ?? ''));
        $us_email              = trim($data['us_email'] ?? '');
        $us_password           = trim($data['us_password'] ?? '');
        $perfiles              = $data['perfiles'] ?? [];

        // 2. Bloque de Validaciones Estrictas

        // Título
        if (empty($us_titulo)) {
            $this->errors['us_titulo'] = "El campo Título es obligatorio.";
        } elseif (!preg_match('/^[a-zA-Z\.]{3,7}$/', $us_titulo)) {
            $this->errors['us_titulo'] = "La abreviatura del título tiene que ser de 3 a 7 caracteres (alfabéticos y punto).";
        }

        // Descripción del Título
        if (empty($us_titulo_descripcion)) {
            $this->errors['us_titulo_descripcion'] = "El campo Descripción del Título es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\.\,\-\_\:\;\(\)\n]{4,500}$/u', $us_titulo_descripcion)) {
            $this->errors['us_titulo_descripcion'] = "La descripción del título tiene que ser de 4 a 500 caracteres.";
        }

        // Apellidos
        if (empty($us_apellidos)) {
            $this->errors['us_apellidos'] = "El campo Apellidos es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]{3,32}$/u', $us_apellidos)) {
            $this->errors['us_apellidos'] = "Los apellidos del usuario deben contener de 3 a 32 caracteres alfabéticos.";
        }

        // Nombres
        if (empty($us_nombres)) {
            $this->errors['us_nombres'] = "El campo Nombres es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s]{3,32}$/u', $us_nombres)) {
            $this->errors['us_nombres'] = "Los nombres del usuario deben contener de 3 a 32 caracteres alfabéticos.";
        }

        // CORRECCIÓN LÓGICA: Procesar el shortname y fullname SOLO si nombres/apellidos pasaron la validación básica
        if (empty($this->errors['us_apellidos']) && empty($this->errors['us_nombres'])) {
            $us_fullname = trim($us_apellidos . " " . $us_nombres);

            if (!empty($us_fullname) && $this->exists('us_fullname', $us_fullname, $id)) {
                $this->errors['us_fullname'] = "Ya existe el nombre completo del usuario.";
            }

            // Manejo seguro del shortname automático
            if ($us_shortname !== "") {
                $us_shortname = preg_replace('/\s+/', ' ', $us_shortname);
            } else {
                $apellidos = explode(" ", $us_apellidos);
                $nombres   = explode(" ", $us_nombres);
                $primer_nombre   = $nombres[0] ?? '';
                $primer_apellido = $apellidos[0] ?? '';
                $us_shortname    = trim($us_titulo . " " . $primer_nombre . " " . $primer_apellido);
            }
        }

        // Login / Usuario
        if (empty($us_login)) {
            $this->errors['us_login'] = "El campo Usuario es obligatorio.";
        } elseif (!preg_match('/^[a-zA-Z0-9\_\-]{4,16}$/', $us_login)) {
            $this->errors['us_login'] = "El Nombre de Usuario debe tener entre 4 y 16 caracteres sin espacios.";
        } elseif ($this->exists('us_login', $us_login, $id)) {
            $this->errors['us_login'] = "Ya existe el Nombre de Usuario en la base de datos.";
        }

        // Email
        if (empty($us_email)) {
            $this->errors['us_email'] = "El campo Email es obligatorio.";
        } elseif (!filter_var($us_email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['us_email'] = "El correo electrónico ingresado no es válido.";
        } elseif ($this->exists('us_email', $us_email, $id)) {
            $this->errors['us_email'] = "Ya existe el correo electrónico.";
        }

        // Password condicional
        if (!$is_updating && empty($us_password)) {
            $this->errors['us_password'] = 'El campo Password es obligatorio.';
        } elseif (!empty($us_password)) {
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&+])[A-Za-z\d$@$!%*?&+]{8,}$/', $us_password)) {
                $this->errors['us_password'] = 'Contraseña débil [Mínimo 8 caracteres, mayúscula, minúscula, número y símbolo].';
            }
        }

        // Checkboxes (Perfiles)
        if (!is_array($perfiles) || count($perfiles) === 0) {
            $this->errors['perfiles'] = "Debe asignar al menos un perfil al usuario.";
        }

        // CORRECCIÓN CRÍTICA: Validación Imbatible de Imágenes (MIME Type real)
        if (isset($_FILES['us_foto']) && $_FILES['us_foto']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['us_foto']['tmp_name'];
            $fileSize    = $_FILES['us_foto']['size'];

            // 1. Validar el tamaño del archivo primero
            if ($fileSize > 2 * 1024 * 1024) {
                $this->errors['us_foto'] = "La imagen es muy pesada. Máximo permitido: 2MB.";
            } else {
                // 2. Leer el contenido real del archivo usando la extensión 'finfo' de PHP
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($fileTmpPath);

                // Mimes seguros y permitidos para imágenes
                $mimesPermitidos = [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/gif',
                    'image/webp'
                ];

                if (!in_array($mimeType, $mimesPermitidos)) {
                    $this->errors['us_foto'] = "El archivo no es una imagen válida (Solo JPG, PNG, GIF, WEBP).";
                }
            }
        }

        return empty($this->errors);
    }

    public function getRoleIds(string $userId)
    {
        $sql = "SELECT id_perfil FROM sw_usuario_perfil WHERE id_usuario = ?";
        $data = $this->query($sql, [$userId])->get();

        // Aquí es donde simulamos el pluck('id')->toArray()
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

        // Si tienes un perfil Administrador Supremo (por ejemplo id_perfil = 1) 
        // puedes darle acceso total automáticamente sin validar la tabla pivote:
        if ($perfilId === 1) {
            return true;
        }

        // Consulta adaptada: Relaciona el perfil de la sesión con los permisos
        // Asumimos que tu tabla pivote de permisos se llama 'sw_perfil_permiso' 
        // y tiene las columnas 'id_perfil' e 'id_permiso'
        $sql = "SELECT COUNT(*) as total FROM sw_perfil_permiso pp
                JOIN sw_permiso p ON pp.id_permiso = p.id_permiso
                WHERE pp.id_perfil = ? AND p.slug = ?";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('is', $perfilId, $permissionSlug);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return ($result['total'] ?? 0) > 0;
    } 
}
