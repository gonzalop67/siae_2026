<?php

namespace App\Models\Admin;

use App\Models\Model;
use App\Models\Admin\Subnivel;

class Nivel extends Model
{
    protected string $table = 'niveles_educativos';
    protected string $primaryKey = 'id';

    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'nombre'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false;

    public function validate(array $data, ?int $id = null): bool
    {
        $this->errors = [];

        // Limpiar espacios múltiples en el nombre
        $nombre = preg_replace('/\s+/', ' ', trim($data['nombre'] ?? ''));

        // -------------------------------------------------------------
        // VALIDACIÓN: NOMBRE
        // -------------------------------------------------------------
        if (empty($nombre)) {
            $this->errors['nombre'] = "El campo Nombre es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ.\s]{4,64}$/u', $nombre)) {
            // Nota: Se agrega el modificador 'u' (Unicode) para soportar acentos correctamente en PHP
            $this->errors['nombre'] = "El nombre del nivel educativo tiene que ser de 4 a 64 caracteres (alfabéticos con acentos y espacio).";
        } elseif ($this->exists('nombre', $nombre, $id)) {
            $this->errors['nombre'] = "Ya existe el Nombre del Nivel Educativo en la base de datos.";
        }

        return empty($this->errors);
    }

    /**
     * Obtiene todos los niveles con sus respectivos subniveles (Eager Loading manual)
     * @return array
     */
    public function obtenerConSubniveles(): array
    {
        // 1. PRIMERA CONSULTA: Obtener todos los niveles ordenados por id
        $niveles = $this->orderBy('id', 'ASC')->get();

        if (empty($niveles)) {
            return [];
        }

        // Extraemos todos los IDs de los niveles obtenidos de forma segura
        $nivelesIds = array_column($niveles, 'id');

        // 2. SEGUNDA CONSULTA: Obtener todos los subniveles que pertenezcan a esos IDs
        // Como tu ORM maneja un método where estructurado, emulamos un WHERE IN de forma limpia
        $subnivelModel = new Model(); // Instancia genérica o tu SubnivelModel específico
        $subnivelModel->query = null; // Asegurar estado limpio

        // Creamos los marcadores de posición (?,?,?) según la cantidad de IDs
        $placeholders = implode(',', array_fill(0, count($nivelesIds), '?'));

        // Ejecutamos la consulta directa usando el método query() de tu arquitectura base
        $sqlSubniveles = "SELECT * FROM subniveles_educativos WHERE nivel_id IN ($placeholders) AND deleted_at IS NULL ORDER BY orden ASC";

        // Ejecutamos pasando los IDs como parámetros para evitar inyección SQL
        $subnivelModel->query($sqlSubniveles, $nivelesIds);
        $todosLosSubniveles = $subnivelModel->get();

        // 3. AGRUPACIÓN EN MEMORIA: Indexar subniveles por su nivel_id para un acceso ultra rápido
        $subnivelesAgrupados = [];
        foreach ($todosLosSubniveles as $subnivel) {
            $subnivelesAgrupados[$subnivel['nivel_id']][] = $subnivel;
        }

        // 4. INYECCIÓN FINAL: Asociar los subniveles a cada nivel correspondiente
        foreach ($niveles as &$nivel) {
            $nivel['subniveles'] = $subnivelesAgrupados[$nivel['id']] ?? [];
        }
        unset($nivel); // Romper referencia

        return $niveles;
    }
}
