<?php

namespace App\Models\Admin;

use App\Models\Model;

class UsuarioRol extends Model
{
    protected string $table = 'usuarios_roles';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'usuario_id',
        'rol_id'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
