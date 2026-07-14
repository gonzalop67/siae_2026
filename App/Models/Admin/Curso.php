<?php

namespace App\Models\Admin;

use App\Models\Model;

class Curso extends Model
{
    protected string $table = 'cursos';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'subnivel_id',
        'nombre',
        'seccion',
        'orden'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
