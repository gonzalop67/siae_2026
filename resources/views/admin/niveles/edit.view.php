@extends('layout.app')

@section('content')
    <div class="container m-t-10">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5><strong>Editar Nivel Educativo: {{ $nivel['nombre'] }}</strong></h5>
                        <div>
                            <a href="{{ RUTA_URL }}/niveles" class="btn-volver">Volver al Listado de Niveles Educativos</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="formulario" action="" method="post">
                            <input type="text" name="id_nivel" id="id_nivel" value="{{ $nivel['id'] }}" hidden>
                            <div class="row mb-2">
                                <label for="nombre" class="col-sm-2 col-form-label">Nombre:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nombre" id="nombre" value="{{ $nivel['nombre'] }}"
                                        placeholder="Nombre del Permiso e.g. Ver Usuarios" required autofocus>
                                    <div id="error-nombre" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-10">
                                    <button id="btn-submit" type="submit" class="btn btn-success"><i class="fa fa-pencil"></i> Actualizar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ RUTA_URL }}/public/assets/js/pages/admin/niveles/crear.js"></script>
@endsection
