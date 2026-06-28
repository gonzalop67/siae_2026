<?php

use App\Models\Model;

class ModificarTablaUsuarios extends Model
{
    public function up(): void
    {
        // Ejemplo de sintaxis SQL o Query Builder para agregar columnas
        $sql = "ALTER TABLE `usuarios` ADD COLUMN `persona_id` INT NOT NULL AFTER `id`;";

        $this->connection->query($sql);
    }

    public function down(): void
    {
        // Lógica para revertir el cambio
        $sql = "ALTER TABLE `usuarios` DROP COLUMN `persona_id`;";
        
        $this->connection->query($sql);
    }
}
