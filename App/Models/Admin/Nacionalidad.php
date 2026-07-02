<?php

namespace App\Models\Admin;

use App\Models\Model;

class Nacionalidad extends Model
{
    protected string $table = 'nacionalidades';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'nombre'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
