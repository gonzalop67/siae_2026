<!-- Editar Menu Modal -->
<div class="modal fade" id="editarMenuModal" tabindex="-1" role="dialog" aria-labelledby="editarMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarMenuModalLabel"><i class="fas fa-edit mr-2"></i> Editar Menú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_update" action="<?= RUTA_URL ?>/menus/update" method="POST">
                <!-- Llave oculta del registro actual -->
                <input type="hidden" name="id_update" id="id_update">

                <div class="modal-body">

                    <!-- Nombre -->
                    <div class="form-group mb-3">
                        <label for="nombre_update" class="font-weight-bold">Texto / Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre_update" required>
                        <div id="error-nombre_update" class="invalid-feedback"></div>
                    </div>

                    <!-- URL -->
                    <div class="form-group mb-3">
                        <label for="url_update" class="font-weight-bold">Enlace / URL:</label>
                        <input type="text" class="form-control" name="url" id="url_update" required>
                        <div id="error-url_update" class="invalid-feedback"></div>
                    </div>

                    <!-- Ícono -->
                    <div class="form-group mb-3">
                        <label for="icono_update" class="font-weight-bold">Ícono:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="icono" id="icono_update">
                            <div class="input-group-append">
                                <span class="input-group-text bg-light d-flex align-items-center justify-content-center p-0" style="width: 38px; height: 38px;">
                                    <i id="mostrar-icono-update" class="fas fa-question text-secondary"></i>
                                </span>
                            </div>
                        </div>
                        <div id="error-icono_update" class="invalid-feedback"></div>
                    </div>

                    <!-- Permiso Slug -->
                    <div class="form-group mb-3">
                        <label for="permiso_slug_update" class="font-weight-bold">Permiso Requerido:</label>
                        <select class="form-control" name="permiso_slug" id="permiso_slug_update">
                            <option value="">-- Público / Contenedor (Sin Permiso) --</option>
                            <!-- @foreach ($permisos_disponibles as $permiso) -->
                            <?php 
                            foreach($permisos_disponibles as $permiso):
                            ?>
                            <option value="<?= $permiso['slug'] ?>"><?= $permiso['nombre'] . " (" . $permiso['slug'] . ")" ?>
                            </option>
                            <?php 
                            endforeach;
                            ?>
                        </select>
                        <div id="error-permiso_slug_update" class="invalid-feedback"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="button-update-submit" class="btn btn-success"><i class="fa fa-save mr-1"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>