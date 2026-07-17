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
                                                    <a href="{{ RUTA_URL }}/areas/{{ $area['id'] }}/delete"
                                                        class="btn btn-sm btn-outline-danger btn-eliminar-area"
                                                        data-id="{{ $area['id'] }}">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
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
                                                        data-toggle="modal" data-target="#modalAsignatura"
                                                        data-id="{{ $asig['id'] }}"
                                                        data-area-id="{{ $asig['area_id'] }}"
                                                        data-nombre="{{ $asig['nombre'] }}"
                                                        data-codigo="{{ $asig['codigo'] }}"
                                                        data-estado="{{ $asig['estado'] }}">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>
                                                    <a href="{{ RUTA_URL }}/asignaturas/{{ $asig['id'] }}/delete"
                                                        class="btn btn-sm btn-outline-danger btn-eliminar-asig"><i
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputBuscar = document.getElementById('inputBuscarAsignatura');
            const selectArea = document.getElementById('selectFiltroArea');
            const tablaAsignaturas = document.getElementById('tablaAsignaturas');

            // Validar que los elementos existan en el DOM actual
            if (inputBuscar && selectArea && tablaAsignaturas) {
                const filas = tablaAsignaturas.querySelectorAll('tbody tr');

                // Función unificada para aplicar ambos filtros al mismo tiempo
                const filtrarTabla = () => {
                    const textoBusqueda = inputBuscar.value.toLowerCase().trim();
                    const areaSeleccionada = selectArea.value;

                    let filasVisibles = 0;
                    // Buscar si ya existe la fila de "No se encontraron resultados" para no duplicarla
                    let filaNoResultados = tablaAsignaturas.querySelector('.fila-sin-resultados');

                    filas.forEach(fila => {
                        // Ignorar la fila estática de "No hay asignaturas registradas" de tu Blade y la fila dinámica
                        if (fila.cells.length === 1 || fila.classList.contains('fila-sin-resultados'))
                            return;

                        const codigo = fila.cells[0].textContent.toLowerCase();
                        const nombre = fila.cells[1].textContent.toLowerCase();
                        const areaIdFila = fila.getAttribute('data-area-id');

                        const coincideTexto = codigo.includes(textoBusqueda) || nombre.includes(
                            textoBusqueda);
                        const coincideArea = (areaSeleccionada === '') || (areaIdFila ===
                            areaSeleccionada);

                        if (coincideTexto && coincideArea) {
                            fila.style.display = '';
                            filasVisibles++; // Contabilizar fila que cumple los criterios
                        } else {
                            fila.style.display = 'none';
                        }
                    });

                    // 🔥 GESTIÓN DINÁMICA DEL MENSAJE DE ALERTA
                    if (filasVisibles === 0) {
                        // Si no hay filas visibles y la alerta NO existe en el DOM, la creamos
                        if (!filaNoResultados) {
                            const tbody = tablaAsignaturas.querySelector('tbody');
                            filaNoResultados = document.createElement('tr');
                            filaNoResultados.className = 'fila-sin-resultados';
                            filaNoResultados.innerHTML = `
                                <td colspan="5" class="text-center text-muted py-4 bg-light">
                                    <i class="fa-solid fa-folder-open text-warning fa-2x mb-2 d-block"></i>
                                    No se encontraron asignaturas que coincidan con los criterios de búsqueda.
                                </td>
                            `;
                            tbody.appendChild(filaNoResultados);
                        }
                    } else {
                        // Si vuelven a aparecer registros válidos y la alerta existe, la removemos
                        if (filaNoResultados) {
                            filaNoResultados.remove();
                        }
                    }
                };

                // Escuchar el evento 'input' (detecta escritura, borrado y pegado al instante)
                inputBuscar.addEventListener('input', filtrarTabla);

                // Escuchar el evento 'change' en el elemento select de áreas
                selectArea.addEventListener('change', filtrarTabla);
            }

            // ======================================================================
            // 🗂️ CONFIGURACIÓN Y REFERENCIAS DE COMPONENTES
            // ======================================================================
            // Módulo Áreas
            const formArea = document.querySelector('#modalArea form');
            const modalAreaElement = document.getElementById('modalArea');

            // Módulo Asignaturas (Asegúrate de que tu modal de asignaturas tenga id="modalAsignatura" y contenga un <form>)
            const formAsignatura = document.querySelector('#modalAsignatura form');
            const modalAsignaturaElement = document.getElementById('modalAsignatura');

            // ======================================================================
            // 🎯 1. MANEJAR CLICS GLOBALES (Delegación de Eventos para ambos paneles)
            // ======================================================================
            document.addEventListener('click', async (e) => {

                // ------------------------------------------------------------------
                // [ÁREAS] ACCIÓN A: Capturar datos para EDICIÓN
                // ------------------------------------------------------------------
                const btnEditarArea = e.target.closest('.btn-editar-area');
                if (btnEditarArea) {
                    formArea.classList.remove('was-validated');
                    document.getElementById('modalAreaLabel').innerHTML =
                        '<i class="fa-solid fa-pen-to-square text-warning mr-2"></i>Editar Área';

                    const id = btnEditarArea.getAttribute('data-id');
                    const nombre = btnEditarArea.getAttribute('data-nombre');
                    const estado = btnEditarArea.getAttribute('data-estado');

                    document.getElementById('area_id').value = id;
                    document.getElementById('area_nombre').value = nombre;
                    document.getElementById('area_estado').checked = parseInt(estado) === 1;

                    const buttonSubmit = formArea.querySelector('button[type="submit"]');
                    if (buttonSubmit) {
                        buttonSubmit.innerHTML =
                            '<i class="fa-solid fa-floppy-disk mr-1"></i> Actualizar Área';
                        buttonSubmit.className = 'btn btn-sm btn-warning';
                    }

                    $(modalAreaElement).modal('show');
                    setTimeout(() => document.getElementById('area_nombre').focus(), 150);
                    return;
                }

                // ======================================================================
                // ACCIÓN B: ELIMINAR un área (Intercepta el enlace <a> tradicional)
                // ======================================================================
                const btnEliminar = e.target.closest('.btn-eliminar-area');

                if (btnEliminar) {
                    e.preventDefault(); // Detiene la redirección inmediata del enlace href

                    const id = btnEliminar.getAttribute('data-id');
                    const urlEliminar = btnEliminar.getAttribute('href');

                    const confirmacion = await Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Las asignaturas vinculadas a esta área podrían verse afectadas.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    });

                    if (confirmacion.isConfirmed) {
                        // Mostrar un estado visual de carga mientras el servidor procesa la base de datos
                        Swal.fire({
                            title: 'Eliminando...',
                            text: 'Por favor, espere.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        try {
                            // 1. Realiza la petición de eliminación
                            const response = await fetch(urlEliminar, {
                                method: 'POST'
                            });

                            if (!response.ok) {
                                throw new Error(`Error en el servidor: ${response.status}`);
                            }

                            // 2. 🔥 CAPTURAR EL JSON DE RESPUESTA DEL SERVIDOR
                            const resultado = await response.json();

                            // 3. Evaluar el estado devuelto por tu controlador PHP
                            if (resultado.ok || resultado.success) {
                                // ÉXITO: El registro se borró correctamente
                                await Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: resultado.mensaje, // Usa el mensaje de tu controlador
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Refrescar la tabla o contenedor visual
                                recargarComponentesModulo();
                            } else {
                                // RESTRICCIÓN O ERROR DE NEGOCIO: (Ej: Tabla relacionada con código 1451)
                                Swal.fire({
                                    icon: 'error',
                                    title: 'No se pudo eliminar',
                                    text: resultado
                                        .mensaje // Muestra "No se puede eliminar el área porque tiene registros relacionados..."
                                });
                            }

                        } catch (error) {
                            console.error('Error al eliminar área:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Red',
                                text: 'No se pudo procesar la solicitud debido a un problema de conectividad.'
                            });
                        }
                    }
                    return;
                }

                // ------------------------------------------------------------------
                // [ASIGNATURAS] ACCIÓN C: Capturar datos para EDICIÓN (Versión Blindada)
                // ------------------------------------------------------------------
                const btnEditarAsig = e.target.closest('.btn-editar-asig');
                if (btnEditarAsig) {
                    // Verificar primero si el formulario existe para evitar romper el script
                    if (formAsignatura) formAsignatura.classList.remove('was-validated');

                    // Cambiar título de forma segura
                    const modalLabel = document.getElementById('modalAsignaturaLabel');
                    if (modalLabel) {
                        modalLabel.innerHTML =
                            '<i class="fa-solid fa-pen-to-square text-warning mr-2"></i>Editar Asignatura';
                    }

                    // Extraer datos desde los atributos data-* mapeados en tu fila HTML
                    const id = btnEditarAsig.getAttribute('data-id') || '';
                    const areaId = btnEditarAsig.getAttribute('data-area-id') || '';
                    const nombre = btnEditarAsig.getAttribute('data-nombre') || '';
                    const codigo = btnEditarAsig.getAttribute('data-codigo') || '';
                    const estado = btnEditarAsig.getAttribute('data-estado') || '0';

                    // 🛡️ Inyectar datos en los inputs VALIDANDO QUE EXISTAN (Evita que el script se rompa)
                    const inputId = document.getElementById('asignatura_id') || document.getElementById(
                        'asig_id');
                    if (inputId) inputId.value = id;

                    // Busca tu select de áreas (puede llamarse area_id o asig_area)
                    const inputArea = document.getElementById('asig_area') || document
                        .getElementById('area_id');
                    if (inputArea) inputArea.value = areaId;

                    // Busca tu input de nombre (puede llamarse asig_nombre o nombre)
                    const inputNombre = document.getElementById('asig_nombre') || document
                        .getElementById('nombre');
                    if (inputNombre) inputNombre.value = nombre;

                    // Busca tu input de código (puede llamarse asig_codigo o codigo)
                    const inputCodigo = document.getElementById('asig_codigo') || document
                        .getElementById('codigo');
                    if (inputCodigo) inputCodigo.value = codigo;

                    // Busca tu switch de estado (puede llamarse asig_estado o estado)
                    const inputEstado = document.getElementById('asig_estado') || document
                        .getElementById('estado') || document.getElementById('area_estado');
                    if (inputEstado) inputEstado.checked = parseInt(estado) === 1;

                    // Modificar visualmente el botón Submit del formulario
                    if (formAsignatura) {
                        const buttonSubmit = formAsignatura.querySelector('button[type="submit"]');
                        if (buttonSubmit) {
                            buttonSubmit.innerHTML =
                                '<i class="fa-solid fa-floppy-disk mr-1"></i> Actualizar Asignatura';
                            buttonSubmit.className = 'btn btn-sm btn-warning';
                        }
                    }

                    // 🚀 COMPATIBILIDAD INTEGRAL: Abrir el modal forzadamente
                    if (modalAsignaturaElement) {
                        $(modalAsignaturaElement).modal('show');
                    } else {
                        // Si fallaba la referencia por variable, intentarlo por ID directo de jQuery
                        $('#modalAsignatura').modal('show');
                    }

                    // Autofocus seguro
                    const inputFocus = document.getElementById('asig_nombre') || document
                        .getElementById('nombre');
                    if (inputFocus) setTimeout(() => inputFocus.focus(), 150);

                    return;
                }

                // ------------------------------------------------------------------
                // [ASIGNATURAS] ACCIÓN D: ELIMINAR una Asignatura (¡Nueva adaptación!)
                // ------------------------------------------------------------------
                const btnEliminarAsig = e.target.closest(
                    '.btn-eliminar-asig'); // Tu HTML usa la clase .btn-eliminar
                if (btnEliminarAsig) {
                    e.preventDefault();
                    const urlEliminar = btnEliminarAsig.getAttribute('href');

                    const confirmacion = await Swal.fire({
                        title: '¿Eliminar Asignatura?',
                        text: "Esta acción quitará la asignatura de la malla curricular activa.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    });

                    if (confirmacion.isConfirmed) {
                        try {
                            const response = await fetch(urlEliminar, {
                                method: 'POST'
                            });
                            const resultado = await response.json();

                            if (resultado.ok || resultado.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: resultado.mensaje ||
                                        'Asignatura eliminada con éxito.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                recargarComponentesModulo();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: resultado.mensaje ||
                                        'No se pudo eliminar la asignatura.'
                                });
                            }
                        } catch (error) {
                            console.error('Error al eliminar asignatura:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Red',
                                text: 'No se pudo establecer comunicación con el servidor.'
                            });
                        }
                    }
                    return;
                }
            });

            // ======================================================================
            // ➕ 2. BOTONES "NUEVO RECORD" (Restablecer formularios)
            // ======================================================================
            // Nuevo Registro: Áreas
            const btnNuevoArea = document.querySelector('.btn-nuevo-area');
            if (btnNuevoArea) {
                btnNuevoArea.addEventListener('click', () => {
                    formArea.reset();
                    document.getElementById('area_id').value = '';
                    document.getElementById('area_estado').checked = true;
                    document.getElementById('modalAreaLabel').innerHTML =
                        '<i class="fa-solid fa-folder-plus text-success mr-2"></i>Gestionar Área';

                    const buttonSubmit = formArea.querySelector('button[type="submit"]');
                    if (buttonSubmit) {
                        buttonSubmit.innerHTML =
                            '<i class="fa-solid fa-floppy-disk mr-1"></i> Guardar Área';
                        buttonSubmit.className = 'btn btn-sm btn-primary';
                    }
                    formArea.classList.remove('was-validated');
                });
            }

            // Nuevo Registro: Asignaturas (Clase corregida .btn-nuevo-asig de tu HTML)
            const btnNuevoAsig = document.querySelector('.btn-nuevo-asig');
            if (btnNuevoAsig) {
                btnNuevoAsig.addEventListener('click', () => {
                    formAsignatura.reset();
                    document.getElementById('asignatura_id').value = '';
                    document.getElementById('asig_estado').checked = true;
                    document.getElementById('modalAsignaturaLabel').innerHTML =
                        '<i class="fa-solid fa-folder-plus text-success mr-2"></i>Nueva Asignatura';

                    const buttonSubmit = formAsignatura.querySelector('button[type="submit"]');
                    if (buttonSubmit) {
                        buttonSubmit.innerHTML = ' Guardar Asignatura';
                        buttonSubmit.className = 'btn btn-sm btn-primary';
                    }

                    formAsignatura.classList.remove('was-validated');
                });
            }

            // ======================================================================
            // 📤 3. PROCESAR ENVÍOS DE FORMULARIOS VIA AJAX (Estructura de botones)
            // ======================================================================
            // Submit Formulario: Áreas
            formArea.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!formArea.checkValidity()) {
                    formArea.classList.add('was-validated');
                    return;
                }
                const buttonSubmit = formArea.querySelector('button[type="submit"]');
                const idArea = document.getElementById('area_id').value;
                const formData = new FormData(formArea);
                const esActualizacion = buttonSubmit.innerText.includes('Actualizar');
                const url = esActualizacion ? '/areas/' + idArea + '/update' : '/areas';
                buttonSubmit.disabled = true;
                try {
                    let resp = await fetch(base_url + url, {
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: formData
                    });
                    const resultado = await resp.json();
                    if (resultado.ok || resultado.success) {
                        $(modalAreaElement).modal('hide');
                        formArea.reset();
                        formArea.classList.remove('was-validated');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Operación Exitosa!',
                            text: resultado.mensaje || 'Cambios guardados.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        recargarComponentesModulo();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resultado.mensaje || 'Error al guardar.'
                        });
                    }
                } catch (error) {
                    console.error('Error Fetch áreas:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Red',
                        text: 'Error de conexión.'
                    });
                } finally {
                    buttonSubmit.disabled = false;
                }
            });

            // Submit Formulario: Asignaturas (¡Nueva adaptación!)
            formAsignatura.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!formAsignatura.checkValidity()) {
                    formAsignatura.classList.add('was-validated');
                    return;
                }
                const buttonSubmit = formAsignatura.querySelector('button[type="submit"]');
                const idAsig = document.getElementById('asignatura_id').value;
                const formData = new FormData(formAsignatura);
                // Adaptación solicitada basada en el texto del botón
                const esActualizacion = buttonSubmit.innerText.includes('Actualizar');
                const url = esActualizacion ? '/asignaturas/' + idAsig + '/update' : '/asignaturas';
                buttonSubmit.disabled = true;
                try {
                    let resp = await fetch(base_url + url, {
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: formData
                    });
                    const resultado = await resp.json();
                    if (resultado.ok || resultado.success) {
                        $(modalAsignaturaElement).modal('hide');
                        formAsignatura.reset();
                        formAsignatura.classList.remove('was-validated');
                        Swal.fire({
                            icon: 'success',
                            title: '¡Asignatura Guardada!',
                            text: resultado.mensaje || 'Los cambios se procesaron con éxito.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        recargarComponentesModulo(); // Refresca las tablas de forma unificada
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resultado.mensaje || 'No se pudieron consolidar los datos.'
                        });
                    }
                } catch (error) {
                    console.error('Error Fetch asignaturas:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Red',
                        text: 'Sin respuesta del servidor.'
                    });
                } finally {
                    buttonSubmit.disabled = false;
                }
            });

            // ======================================================================
            // 🔄 4. FUNCIÓN AUXILIAR GLOBAL: Recargar Contenedores HTML parciales
            // ======================================================================
            async function recargarComponentesModulo() {
                try {
                    const response = await fetch(window.location.href);
                    const htmlCompleto = await response.text();
                    const parser = new DOMParser();
                    const docTemporal = parser.parseFromString(htmlCompleto, 'text/html');
                    // 1. Refrescar Tabla del Pane de Áreasconst 
                    nuevaTablaAreas = docTemporal.getElementById('areas-pane');
                    const tablaAreasActual = document.getElementById('areas-pane');
                    if (nuevaTablaAreas && tablaAreasActual) {
                        tablaAreasActual.innerHTML = nuevaTablaAreas.innerHTML;
                    }
                    // 2. Refrescar Tabla del Pane de Asignaturas
                    const nuevaTablaAsig = docTemporal.getElementById('asignaturas-pane');
                    const tablaAsigActual = document.getElementById('asignaturas-pane');
                    if (nuevaTablaAsig && tablaAsigActual) {
                        tablaAsigActual.innerHTML = nuevaTablaAsig.innerHTML;
                    }
                    // 3. Refrescar el Select del buscador interactivo de áreas
                    const nuevoSelect = docTemporal.getElementById('selectFiltroArea');
                    const selectActual = document.getElementById('selectFiltroArea');
                    if (nuevoSelect && selectActual) {
                        selectActual.innerHTML = nuevoSelect.innerHTML;
                    }
                } catch (error) {
                    console.error('Error crítico al sincronizar vistas asíncronas:', error);
                }
            }
        });
    </script>
@endsection
