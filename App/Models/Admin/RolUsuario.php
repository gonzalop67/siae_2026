<?php

namespace App\Models\Admin;

use App\Models\Model;

class RolUsuario extends Model
{
    protected string $table = 'usuarios_roles';
    protected array $fillable = ['usuario_id', 'rol_id'];

    public function sync(int $id, array $rolesIds)
    {
        $sql = "DELETE FROM {$this->table} WHERE usuario_id = ?";
        $this->query($sql, [$id], 'i');

        for ($i = 0; $i < count($rolesIds); $i++) {
            //Insertar en la tabla usuarios_roles
            $sql = "INSERT INTO {$this->table} (usuario_id, rol_id) VALUES (?, ?)";
            // var_dump($sql); die();
            $this->query($sql, [$id, $rolesIds[$i]], 'ii');
        }
    }
}
