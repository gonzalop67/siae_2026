<?php

namespace App\Models\Admin;

use App\Models\Model;

class PeriodoAcademico extends Model
{
    protected string $table = 'periodos_academicos';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'periodo_lectivo_id',
        'nombre',
        'tipo',
        'orden',
        'fecha_inicio',
        'fecha_fin'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
