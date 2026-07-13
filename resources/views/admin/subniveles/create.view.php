@extends('layout.app')

@section('content')
    <div class="container m-t-10">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5><strong>Nuevo Subnivel Educativo</strong></h5>
                        <div>
                            <a href="{{ RUTA_URL }}/niveles" class="btn-volver">Volver al Listado de Subniveles
                                Educativos</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="formulario" action="" method="post">
                            <!-- 1. Selección del Nivel Padre -->
                            <div class="form-group mb-3">
                                <label for="nivel_id" class="form-label">Nivel Educativo Padre <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="nivel_id" name="nivel_id" required>
                                    <option value="">-- Seleccione el Nivel Principal --</option>
                                    <?php if (!empty($niveles)): ?>
                                    <?php foreach ($niveles as $nivel): ?>
                                    <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <!-- Contenedor dinámico de error para JS -->
                                <div class="invalid-feedback" id="error-nivel_id" style="display: none;"></div>
                            </div>

                            <!-- 2. Nombre del Subnivel -->
                            <div class="form-group mb-3">
                                <label for="nombre" class="form-label">Nombre del Subnivel <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    placeholder="Ej: Preparatoria, Elemental, etc." required>
                                <!-- Contenedor dinámico de error para JS -->
                                <div class="invalid-feedback" id="error-nombre" style="display: none;"></div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="row mb-2">
                                <div class="col-sm-10">
                                    <button id="btn-submit" type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ RUTA_URL }}/public/assets/js/pages/admin/subniveles/crear.js"></script>
@endsection
