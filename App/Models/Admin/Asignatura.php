<?php

namespace App\Models\Admin;

use App\Models\Model;

class Asignatura extends Model
{
    protected string $table = 'asignaturas';
    protected string $primaryKey = 'id';

    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'area_id',
        'nombre',
        'codigo',
        'estado'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false;

    /**
     * Obtiene el listado lineal de asignaturas activas, opcionalmente filtradas por nombre.
     * 
     * @param string $buscar Término de búsqueda opcional.
     * @return array Colección plana de registros.
     */
    public function obtenerAsignaturasPlanas(string $buscar = ''): array
    {
        $sql = "SELECT asig.id, asig.area_id, asig.nombre, asig.codigo, asig.estado, a.nombre AS area_nombre 
            FROM asignaturas asig 
            INNER JOIN areas a ON asig.area_id = a.id 
            WHERE asig.deleted_at IS NULL AND a.deleted_at IS NULL";

        // Si hay un término de búsqueda, añadimos la condición a la consulta
        if ($buscar !== '') {
            $sql .= " AND asig.nombre LIKE ?";
        }

        $sql .= " ORDER BY asig.area_id ASC, asig.nombre ASC";

        $stmt = $this->connection->prepare($sql);

        if (!$stmt) {
            return [];
        }

        // Si hay búsqueda, vinculamos el parámetro con comodines %
        if ($buscar !== '') {
            $termino = "%" . $buscar . "%";
            $stmt->bind_param("s", $termino);
        }

        // Ejecutamos y obtenemos el resultado
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado instanceof \mysqli_result) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }
}
