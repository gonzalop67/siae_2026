<?php

namespace App\Models\Admin;

use App\Models\Model;

class PeriodoLectivo extends Model
{
    protected string $table = 'periodos_lectivos';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
