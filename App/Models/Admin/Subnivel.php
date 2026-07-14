<?php

namespace App\Models\Admin;

use App\Models\Model;

class Subnivel extends Model
{
    protected string $table = 'subniveles_educativos';
    protected string $primaryKey = 'id';

    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'nivel_id',
        'nombre',
        'orden',
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = true;

    /**
     * Obtiene todos los subniveles con sus respectivos cursos (Eager Loading manual)
     * @return array
     */
    public function obtenerConCursos(): array
    {
        // 1. PRIMERA CONSULTA: Obtener todos los subniveles ordenados por nivel_id
        $subniveles = $this->orderBy('nivel_id', 'ASC')
            ->orderBy('orden', 'ASC')
            ->get();

        if (empty($subniveles)) {
            return [];
        }

        // Extraemos todos los IDs de los subniveles obtenidos de forma segura
        $subnivelesIds = array_column($subniveles, 'id');

        // 2. SEGUNDA CONSULTA: Obtener todos los cursos que pertenezcan a esos IDs
        $cursoModel = new Model(); // Instancia genérica o tu CursoModel específico
        $cursoModel->query = null; // Asegurar estado limpio

        // Creamos los marcadores de posición (?,?,?) según la cantidad de IDs
        $placeholders = implode(',', array_fill(0, count($subnivelesIds), '?'));

        // Ejecutamos la consulta directa usando el método query() de tu arquitectura base
        $sqlCursos = "SELECT * FROM cursos WHERE subnivel_id IN ($placeholders) ORDER BY orden ASC";

        // Ejecutamos pasando los IDs de los subniveles como parámetros para evitar inyección SQL
        $cursoModel->query($sqlCursos, $subnivelesIds);
        $todosLosCursos = $cursoModel->get();

        // 3. AGRUPACIÓN EN MEMORIA: Indexar cursos por su subnivel_id para un acceso ultra rápido
        $cursosAgrupados = [];
        foreach ($todosLosCursos as $curso) {
            $cursosAgrupados[$curso['subnivel_id']][] = $curso;
        }

        // 4. INYECCIÓN FINAL: Asociar los cursos a cada subnivel correspondiente
        foreach ($subniveles as &$subnivel) {
            $subnivel['cursos'] = $cursosAgrupados[$subnivel['id']] ?? [];
        }
        unset($subnivel); // Romper referencia de memoria segura

        return $subniveles;
    }
}
