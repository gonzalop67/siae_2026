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
}
