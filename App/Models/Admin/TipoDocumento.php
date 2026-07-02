<?php

namespace App\Models\Admin;

use App\Models\Model;

class TipoDocumento extends Model
{
    protected string $table = 'tipo_documento';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'descripcion'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
