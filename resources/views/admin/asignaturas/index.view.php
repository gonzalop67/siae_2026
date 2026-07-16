@extends('layout.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0"><i class="fa-solid fa-graduation-cap me-2"></i> Estructura Curricular</h5>
            </div>
            <div class="card-body">

                <!-- Navegación por Pestañas (Tabs) adaptadas a la plantilla Annex (Bootstrap 4) -->
                <ul class="nav nav-tabs mb-4" id="academicTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" id="areas-tab" data-toggle="tab" href="#areas-pane"
                            role="tab" aria-controls="areas-pane" aria-selected="true">
                            <i class="fa-solid fa-folder me-2"></i> Áreas del Conocimiento
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" id="asignaturas-tab" data-toggle="tab" href="#asignaturas-pane"
                            role="tab" aria-controls="asignaturas-pane" aria-selected="false">
                            <i class="fa-solid fa-book me-2"></i> Asignaturas
                        </a>
                    </li>
                </ul>

                <!-- Contenido de las Pestañas -->
                <div class="tab-content" id="academicTabsContent">

                    <!-- PANE 1: ÁREAS DEL CONOCIMIENTO -->
                    <div class="tab-pane fade show active" id="areas-pane" role="tabpanel" aria-labelledby="areas-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-secondary mb-0">Catálogo de Áreas Matrices</h6>
                            <button class="btn btn-sm btn-success btn-nuevo-area" data-toggle="modal"
                                data-target="#modalArea"><i class="fa-solid fa-plus me-1"></i> Nueva Área</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Área</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($areas))
                                        @foreach ($areas as $area)
                                            <tr>
                                                <td>{{ $area['id'] }}</td>
                                                <td><strong>{{ $area['nombre'] }}</strong></td>
                                                <td>
                                                    @if ($area['estado'] == 1)
                                                        <span
                                                            class="badge bg-success-subtle text-success border border-success-subtle">●
                                                            Activa</span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger-subtle text-danger border border-danger-subtle">●
                                                            Inactiva</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-outline-primary me-1 btn-editar-area"
                                                        data-bs-toggle="modal" data-bs-target="#modalArea"
                                                        data-id="{{ $area['id'] }}" data-nombre="{{ $area['nombre'] }}"
                                                        data-estado="{{ $area['estado'] }}">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>
                                                    <a href="AcademicoController.php?action=deleteArea&id={{ $area['id'] }}"
                                                        class="btn btn-sm btn-outline-danger btn-eliminar"><i
                                                            class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">No hay áreas del
                                                conocimiento registradas.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PANE 2: ASIGNATURAS -->
                    <div class="tab-pane fade" id="asignaturas-pane" role="tabpanel" aria-labelledby="asignaturas-tab">
                        <div class="row align-items-center mb-3">
                            <!-- Bloque de Filtros Izquierdo -->
                            <div class="col-md-8 col-sm-12 mb-2 mb-md-0">
                                <form class="form-row">
                                    <div class="col-md-6 col-sm-12 mb-2 mb-md-0">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light text-muted"><i
                                                        class="fa-solid fa-magnifying-glass"></i></span>
                                            </div>
                                            <input type="text" id="inputBuscarAsignatura"
                                                class="form-control form-control-sm"
                                                placeholder="Buscar por nombre o código...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <select id="selectFiltroArea" class="form-control form-control-sm">
                                            <option value="">-- Todas las áreas del conocimiento --</option>
                                            @if (!empty($areas))
                                                @foreach ($areas as $area)
                                                    @if ($area['estado'] == 1)
                                                        <option value="{{ $area['id'] }}">{{ $area['nombre'] }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <!-- Botón de Acción Derecho -->
                            <div class="col-md-4 col-sm-12 text-md-right text-left">
                                <button class="btn btn-sm btn-success btn-nuevo-asig shadow-sm" data-toggle="modal"
                                    data-target="#modalAsignatura">
                                    <i class="fa-solid fa-plus mr-1"></i> Nueva Asignatura
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border" id="tablaAsignaturas">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Asignatura</th>
                                        <th>Área Asociada</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($asignaturas))
                                        @foreach ($asignaturas as $asig)
                                            <tr data-area-id="{{ $asig['area_id'] }}">
                                                <td><kbd class="bg-secondary">{{ $asig['codigo'] }}</kbd></td>
                                                <td><strong>{{ $asig['nombre'] }}</strong></td>
                                                <td>{{ $asig['area_nombre'] ?? 'Sin Área' }}</td>
                                                <td>
                                                    @if ($asig['estado'] == 1)
                                                        <span
                                                            class="badge bg-success-subtle text-success border border-success-subtle">●
                                                            Activa</span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger-subtle text-danger border border-danger-subtle">●
                                                            Inactiva</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-outline-primary me-1 btn-editar-asig"
                                                        data-bs-toggle="modal" data-bs-target="#modalAsignatura"
                                                        data-id="{{ $asig['id'] }}"
                                                        data-area-id="{{ $asig['area_id'] }}"
                                                        data-nombre="{{ $asig['nombre'] }}"
                                                        data-codigo="{{ $asig['codigo'] }}"
                                                        data-estado="{{ $asig['estado'] }}">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>
                                                    <a href="AcademicoController.php?action=deleteAsignatura&id={{ $asig['id'] }}"
                                                        class="btn btn-sm btn-outline-danger btn-eliminar"><i
                                                            class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">No hay asignaturas
                                                registradas.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 1: REGISTRO / EDICIÓN DE ÁREAS (Diseño Annex / Bootstrap 4) -->
    <div class="modal fade" id="modalArea" tabindex="-1" role="dialog" aria-labelledby="modalAreaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content border-0 shadow" action="AcademicoController.php?action=saveArea" method="POST">
                <!-- Llave primaria oculta para casos de edición -->
                <input type="hidden" name="id" id="area_id" value="">

                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalAreaLabel">
                        <i class="fa-solid fa-folder-plus text-success mr-2"></i>Gestionar Área
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="area_nombre" class="font-weight-bold text-secondary">Nombre del Área <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre" id="area_nombre"
                            placeholder="Ej: Ciencias Naturales" required maxlength="100">
                    </div>

                    <!-- Estructura Custom Switch Nativa de Bootstrap 4 -->
                    <div class="custom-control custom-switch mt-3">
                        <input type="checkbox" class="custom-control-input" name="estado" id="area_estado"
                            value="1" checked>
                        <label class="custom-control-label text-secondary" for="area_estado">Área en estado activo</label>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-floppy-disk mr-1"></i>
                        Guardar Área</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: REGISTRO / EDICIÓN DE ASIGNATURAS (Diseño Annex / Bootstrap 4) -->
    <div class="modal fade" id="modalAsignatura" tabindex="-1" role="dialog" aria-labelledby="modalAsignaturaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content border-0 shadow" action="AcademicoController.php?action=saveAsignatura"
                method="POST">
                <!-- Llave primaria oculta para casos de edición -->
                <input type="hidden" name="id" id="asignatura_id" value="">

                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalAsignaturaLabel">
                        <i class="fa-solid fa-book-medical text-success mr-2"></i>Gestionar Asignatura
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="asig_area" class="font-weight-bold text-secondary">Área del Conocimiento <span
                                class="text-danger">*</span></label>
                        <select class="form-control" name="area_id" id="asig_area" required>
                            <option value="" disabled selected>-- Seleccione un área --</option>
                            @if (!empty($areas))
                                @foreach ($areas as $area)
                                    @if ($area['estado'] == 1)
                                        <option value="{{ $area['id'] }}">{{ $area['nombre'] }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-8 mb-3">
                            <label for="asig_nombre" class="font-weight-bold text-secondary">Nombre de Asignatura <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombre" id="asig_nombre"
                                placeholder="Ej: Física" required maxlength="100">
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label for="asig_codigo" class="font-weight-bold text-secondary">Código <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" name="codigo" id="asig_codigo"
                                placeholder="FIS-01" required maxlength="20">
                        </div>
                    </div>

                    <!-- Estructura Custom Switch Nativa de Bootstrap 4 -->
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" class="custom-control-input" name="estado" id="asig_estado"
                            value="1" checked>
                        <label class="custom-control-label text-secondary" for="asig_estado">Asignatura en estado
                            activo</label>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-floppy-disk mr-1"></i>
                        Guardar Asignatura</button>
                </div>
            </form>
        </div>
    </div>
@endsection
