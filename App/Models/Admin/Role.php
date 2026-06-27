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

    public function validate(array $data, ?int $id = null): bool
    {
        $this->errors = [];

        // Limpiar espacios múltiples en el nombre
        $nombre = preg_replace('/\s+/', ' ', trim($data['nombre'] ?? ''));

        // El slug no debe llevar espacios; JS ya los convierte en guiones (-)
        $slug   = trim($data['slug'] ?? '');

        // -------------------------------------------------------------
        // VALIDACIÓN: NOMBRE
        // -------------------------------------------------------------
        if (empty($nombre)) {
            $this->errors['nombre'] = "El campo Nombre es obligatorio.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ.\s]{4,64}$/u', $nombre)) {
            // Nota: Se agrega el modificador 'u' (Unicode) para soportar acentos correctamente en PHP
            $this->errors['nombre'] = "El nombre del rol tiene que ser de 4 a 64 caracteres (alfabéticos con acentos y espacio).";
        } elseif ($this->exists('nombre', $nombre, $id)) {
            $this->errors['nombre'] = "Ya existe el Nombre del Rol en la base de datos.";
        }

        // -------------------------------------------------------------
        // VALIDACIÓN: SLUG
        // -------------------------------------------------------------
        if (empty($slug)) {
            $this->errors['slug'] = "El campo Slug es obligatorio.";
        }
        // Corregido: Permite minúsculas, números, guion bajo y guion medio (igual que tu JS)
        elseif (!preg_match('/^[a-z0-9_-]{4,64}$/', $slug)) {
            $this->errors['slug'] = "El slug del rol tiene que ser de 4 a 64 caracteres (minúsculas, números, guion bajo o medio).";
        } elseif ($this->exists('slug', $slug, $id)) {
            $this->errors['slug'] = "Ya existe el Slug del Rol en la base de datos.";
        }

        return empty($this->errors);
    }
}
