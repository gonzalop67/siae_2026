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
    protected bool $useSoftDeletes = true;

    /**
     * Obtiene el listado lineal y plano de las asignaturas activas con su área vinculada.
     * 
     * @return array Colección plana de registros.
     */
    public function obtenerAsignaturasPlanas(): array
    {
        $sql = "SELECT 
                asig.id,
                asig.area_id,
                asig.nombre,
                asig.codigo,
                asig.estado,
                a.nombre AS area_nombre
            FROM asignaturas asig
            INNER JOIN areas a ON asig.area_id = a.id
            WHERE asig.deleted_at IS NULL AND a.deleted_at IS NULL
            ORDER BY asig.codigo ASC";

        // Ejecuta la consulta directa y obtiene el arreglo de datos en un solo paso
        $resultado = $this->connection->query($sql);

        if ($resultado instanceof \mysqli_result) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }
}
