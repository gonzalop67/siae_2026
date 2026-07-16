<?php

namespace App\Models\Admin;

use App\Models\Model;

class Area extends Model
{
    protected string $table = 'areas';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'nombre',
        'estado'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = true; 
}
