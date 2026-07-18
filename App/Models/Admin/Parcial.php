<?php

namespace App\Models\Admin;

use App\Models\Model;

class Parcial extends Model
{
    protected string $table = 'parciales_evaluacion';
    protected string $primaryKey = 'id';
    
    // Define los campos que se pueden llenar masivamente
    protected array $fillable = [
        'periodo_academico_id',
        'nombre',
        'orden',
        'peso_nota',
        'fecha_inicio',
        'fecha_fin',
        'fecha_cierre_notas'
    ];

    // Activa o desactiva el Soft Delete según tus necesidades en la tabla
    protected bool $useSoftDeletes = false; 
}
