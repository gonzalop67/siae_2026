<?php

namespace App\Models\Admin;

use App\Models\Model;

class Role extends Model
{
    protected string $table = 'roles';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'nombre',
        'slug',
        'descripcion'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = true; 
}
