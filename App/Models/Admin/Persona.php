<?php

namespace App\Models\Admin;

use App\Models\Model;

class Persona extends Model
{
    protected string $table = 'personas';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'tipo_documento_id',
        'nacionalidad_id',
        'dni',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'nombre_corto',
        'nombre_completo',
        'titulo',
        'descripcion_titulo',
        'genero',
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'sector',
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
