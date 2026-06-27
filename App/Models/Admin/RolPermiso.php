<?php

namespace App\Models\Admin;

use App\Models\Model;

class RolPermiso extends Model
{
    protected string $table = 'roles_permisos';
    protected array $fillable = ['rol_id', 'permiso_id'];

    public function sync(int $id, array $permissionIds)
    {
        $sql = "DELETE FROM {$this->table} WHERE rol_id = ?";
        $this->query($sql, [$id], 'i');

        for ($i = 0; $i < count($permissionIds); $i++) {
            //Insertar en la tabla sw_perfil_permiso
            $sql = "INSERT INTO {$this->table} (rol_id, permiso_id) VALUES (?, ?)";
            // var_dump($sql); die();
            $this->query($sql, [$id, $permissionIds[$i]], 'ii');
        }
    }
}