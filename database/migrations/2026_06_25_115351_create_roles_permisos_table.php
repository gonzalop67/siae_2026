<?php

use App\Models\Model;

class CreateRolesPermisosTable extends Model
{
    /**
     * Ejecuta la migración (Crear o modificar tablas).
     */
    public function up(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS roles_permisos (
                rol_id INT NOT NULL,
                permiso_id INT NOT NULL,
                PRIMARY KEY (rol_id, permiso_id),
                INDEX idx_permiso (permiso_id),
                CONSTRAINT fk_roles_permisos_rol
                    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT fk_roles_permisos_permiso
                    FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->connection->query($sql);
    }

    /**
     * Revierte la migración (Eliminar tablas).
     */
    public function down(): void
    {
        $sql = "DROP TABLE IF EXISTS roles_permisos;";
        $this->connection->query($sql);
    }
}
