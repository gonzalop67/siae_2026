@extends('layout.app')

@section('style')
    <style>
        .nav-tabs .nav-link.active {
            font-weight: bold;
            color: #4e73df !important;
            border-bottom: 3px solid #4e73df;
        }

        .card {
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid my-4" style="max-width: 1200px;">
        <!-- Encabezado -->
        <div class="row mb-4 align-items-center">
            <div class="col-sm-8">
                <h1 class="h3 font-weight-bold text-gray-800">Estructura Académica Institucional</h1>
                <p class="text-muted small">Administración integral de los años lectivos, trimestres y parciales de
                    evaluación.</p>
            </div>
            <div class="col-sm-4 text-sm-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalLectivo">
                    <i class="fas fa-plus mr-1"></i> Nuevo Año Lectivo
                </button>
            </div>
        </div>

        <!-- Menú de Pestañas Navegables (Bootstrap 4) -->
        <ul class="nav nav-tabs mb-4" id="academicTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-secondary" id="lectivos-tab" data-toggle="tab" href="#tab-lectivos"
                    role="tab">
                    <i class="fas fa-calendar-alt mr-1"></i> 1. Años Lectivos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-secondary" id="academicos-tab" data-toggle="tab" href="#tab-academicos"
                    role="tab">
                    <i class="fas fa-layer-group mr-1"></i> 2. Trimestres / Bimestres
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-secondary" id="parciales-tab" data-toggle="tab" href="#tab-parciales"
                    role="tab">
                    <i class="fas fa-cubes mr-1"></i> 3. Parciales y Bloques
                </a>
            </li>
        </ul>

        <!-- CONTENIDO DE LAS PESTAÑAS -->
        <div class="tab-content" id="academicTabsContent">
            <!-- Tab 1: Años Lectivos -->
            <div class="tab-pane fade show active" id="tab-lectivos" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nombre del Ciclo</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-lectivos">
                                <!-- Cargado dinámicamente con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Periodos Académicos -->
            <div class="tab-pane fade" id="tab-academicos" role="tabpanel">
                <div class="card shadow-sm border-0 p-3 mb-3">
                    <div class="form-inline">
                        <label class="my-1 mr-2 font-weight-bold text-secondary" for="select-lectivos-filtro">Filtrar por
                            Año Lectivo:</label>
                        <select class="form-control form-control-sm bg-white" id="select-lectivos-filtro"
                            onchange="alCambiarLectivo(this.value)">
                            <option value="">-- Seleccione un Año Lectivo --</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm my-1" id="btn-nuevo-academico" data-toggle="modal"
                        data-target="#modalAcademico" disabled>
                        <i class="fas fa-plus mr-1"></i> Nuevo Bloque Académico
                    </button>
                </div>
                <div class="card shadow-sm border-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nombre del Bloque</th>
                                    <th>Tipo</th>
                                    <th class="text-center">Orden Secuencial</th>
                                    <th>Rango de Fechas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-academicos">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Selecciona un año lectivo en el
                                        filtro superior.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Parciales de Evaluación -->
            <div class="tab-pane fade" id="tab-parciales" role="tabpanel">
                <div class="card shadow-sm border-0 p-3 mb-3">
                    <div class="form-inline">
                        <label class="my-1 mr-2 font-weight-bold text-secondary" for="select-academicos-filtro">Filtrar por
                            Bloque Académico:</label>
                        <select class="form-control form-control-sm bg-white" id="select-academicos-filtro"
                            onchange="alCambiarAcademico(this.value)">
                            <option value="">-- Seleccione un Bloque Académico --</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm my-1" id="btn-nuevo-parcial" data-toggle="modal"
                        data-target="#modalParcial" disabled>
                        <i class="fas fa-plus mr-1"></i> Nuevo Parcial
                    </button>
                </div>
                <div class="card shadow-sm border-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Descripción del Parcial</th>
                                    <th class="text-center">Peso Nota</th>
                                    <th>Rango Cronológico</th>
                                    <th>Cierre de Plataforma</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-parciales">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Selecciona un bloque académico en
                                        el filtro superior.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Nuevo Año Lectivo (Bootstrap 4) -->
    <div class="modal fade" id="modalLectivo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">Registrar Nuevo Período Lectivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-lectivo" onsubmit="guardarLectivo(event)">
                    <input type="hidden" name="id" id="edit-lectivo-id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre del Ciclo Escolar</label>
                            <input type="text" name="nombre" class="form-control"
                                placeholder="Ej: Ciclo Sierra 2025-2026" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label class="font-weight-bold">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="form-group col-6">
                                <label class="font-weight-bold">Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">Guardar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Nuevo Periodo Académico -->
    <div class="modal fade" id="modalAcademico" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">Registrar Bloque Académico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-academico" onsubmit="guardarAcademico(event)">
                    <!-- Input oculto para vincularlo al año lectivo seleccionado -->
                    <input type="hidden" name="periodo_lectivo_id" id="hidden-lectivo-id">
                    <!-- Almacena el ID del Trimestre a EDITAR (El nuevo input agregado) -->
                    <input type="hidden" name="id" id="edit-academico-id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre del Bloque</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Primer Trimestre"
                                required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label class="font-weight-bold">Tipo de Periodo</label>
                                <select name="tipo" class="form-control" required>
                                    <option value="Trimestre">Trimestre</option>
                                    <option value="Bimestre">Bimestre</option>
                                    <option value="Bloque">Bloque</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label class="font-weight-bold">Orden Secuencial</label>
                                <input type="number" name="orden" class="form-control" min="1"
                                    placeholder="Ej: 1" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label class="font-weight-bold">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="form-group col-6">
                                <label class="font-weight-bold">Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">Guardar Bloque</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Nuevo Parcial de Evaluación -->
    <div class="modal fade" id="modalParcial" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">Registrar Parcial de Evaluación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-parcial" onsubmit="guardarParcial(event)">
                    <!-- Input oculto para vincularlo al bloque académico seleccionado -->
                    <input type="hidden" name="periodo_academico_id" id="hidden-academico-id">
                    <!-- 2. Almacena el ID del Parcial a EDITAR (El nuevo input agregado) -->
                    <input type="hidden" name="id" id="edit-parcial-id">
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-8">
                                <label class="font-weight-bold">Nombre del Parcial</label>
                                <input type="text" name="nombre" class="form-control" placeholder="Ej: Parcial 1"
                                    required>
                            </div>
                            <div class="form-group col-4">
                                <label class="font-weight-bold">Peso Nota (%)</label>
                                <input type="number" name="peso_nota" class="form-control" min="1"
                                    max="100" placeholder="30" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Orden Secuencial</label>
                            <input type="number" name="orden" class="form-control" min="1"
                                placeholder="Ej: 1" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-4">
                                <label class="font-weight-bold">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="form-group col-4">
                                <label class="font-weight-bold">Fecha Fin</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                            <div class="form-group col-4">
                                <label class="font-weight-bold">Cierre Notas</label>
                                <input type="date" name="fecha_cierre_notas" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">Guardar Parcial</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Conexión asíncrona nativa hacia tu MVC -->
    <script>
        const BASE_URL = typeof base_url !== 'undefined' ? base_url : '';
        const API_LECTIVOS = `${BASE_URL}/api/lectivos`;
        const API_GUARDAR = `${BASE_URL}/configuracion/periodos/guardar-lectivo`;
        const API_ACADEMICOS = `${BASE_URL}/api/periodos-academicos`; // Se le concatenará /ID
        const API_PARCIALES = `${BASE_URL}/api/parciales`; // Se le concatenará /ID
        // Nuevas constantes de guardado
        const API_GUARDAR_ACADEMICO = `${BASE_URL}/configuracion/periodos/guardar-academico`;
        const API_GUARDAR_PARCIAL = `${BASE_URL}/configuracion/periodos/guardar-parcial`;
        // Nuevas constantes de eliminado
        const API_ELIMINAR_LECTIVOS = `${BASE_URL}/configuracion/periodos/eliminar-lectivo`;
        const API_ELIMINAR_ACADEMICOS = `${BASE_URL}/configuracion/periodos/eliminar-academico`;
        const API_ELIMINAR_PARCIALES = `${BASE_URL}/configuracion/periodos/eliminar-parcial`;

        // 🔥 LAS CONSTANTES QUE FALTABAN (Rutas de edición mapeadas a tu Core\Route)
        const API_EDITAR_LECTIVO = `${BASE_URL}/configuracion/periodos/editar-lectivo`;
        const API_EDITAR_ACADEMICO = `${BASE_URL}/configuracion/periodos/editar-academico`;
        const API_EDITAR_PARCIAL = `${BASE_URL}/configuracion/periodos/editar-parcial`;

        document.addEventListener("DOMContentLoaded", () => {
            cargarTablaLectivos();
        });

        // ==========================================
        // 1. CARGAR TABLA AÑOS LECTIVOS (PESTAÑA 1)
        // ==========================================
        function cargarTablaLectivos() {
            fetch(API_LECTIVOS)
                .then(res => {
                    if (!res.ok) throw new Error('Error al obtener años lectivos');
                    return res.json();
                })
                .then(data => {
                    const tbody = document.getElementById('tbody-lectivos');
                    const selectFiltro = document.getElementById('select-lectivos-filtro');

                    tbody.innerHTML = '';
                    // Limpiar select manteniendo la opción por defecto
                    if (selectFiltro) {
                        selectFiltro.innerHTML = '<option value="">-- Seleccione un Año Lectivo --</option>';
                    }

                    if (data.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="5" class="text-center text-muted py-3">No hay años lectivos registrados.</td></tr>`;
                        return;
                    }

                    data.forEach(item => {
                        // Configurar la medalla de estado académico
                        const estadoBadge = item.estado == 1 ?
                            '<span class="badge badge-success">Activo</span>' :
                            '<span class="badge badge-secondary">Inactivo</span>';

                        tbody.innerHTML += `
                    <tr>
                        <td class="font-weight-bold">${item.nombre}</td>
                        <td>${item.fecha_inicio}</td>
                        <td>${item.fecha_fin}</td>
                        <td>${estadoBadge}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <!-- BOTÓN EDITAR: Sin onclick, usa atributos data-* leídos por el escuchador global -->
                                <button type="button" class="btn btn-sm btn-outline-warning btn-editar-registro" 
                                        data-id="${item.id}" 
                                        data-tipo="lectivo"
                                        data-nombre="${item.nombre}"
                                        data-inicio="${item.fecha_inicio}"
                                        data-fin="${item.fecha_fin}"
                                        title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <!-- BOTÓN ELIMINAR: Sin onclick, usa href leído por el escuchador global -->
                                <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-registro" 
                                        href="${API_ELIMINAR_LECTIVOS}/${item.id}" 
                                        data-id="${item.id}" 
                                        data-tipo="lectivo" 
                                        title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;

                        // Rellenar dinámicamente el filtro de la Pestaña 2
                        if (selectFiltro) {
                            selectFiltro.innerHTML += `<option value="${item.id}">${item.nombre}</option>`;
                        }
                    });
                })
                .catch(err => {
                    console.error("Error al cargar años lectivos:", err);
                    const tbody = document.getElementById('tbody-lectivos');
                    if (tbody) {
                        tbody.innerHTML =
                            `<tr><td colspan="5" class="text-center text-danger py-3">Error al cargar la información principal.</td></tr>`;
                    }
                });
        }

        function guardarLectivo(event) {
            event.preventDefault();
            const form = event.target;

            // 1. Capturar los datos EXACTAMENTE en el momento del clic, antes de cerrar nada
            const formData = new FormData(form);
            const objetoData = Object.fromEntries(formData.entries());

            const id = objetoData.id;
            const url = (id && id !== "") ? `${API_EDITAR_LECTIVO}/${id}` : API_GUARDAR;

            console.log("Enviando de forma segura -> URL:", url, "Datos capturados:", objetoData);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(objetoData)
                })
                .then(res => {
                    if (!res.ok) throw new Error();
                    return res.json();
                })
                .then(() => {
                    // 2. Cerramos el modal SOLAMENTE cuando el servidor ya respondió con éxito
                    $('#modalLectivo').modal('hide');

                    // 3. Limpiamos el formulario manualmente aquí de forma segura
                    form.reset();
                    document.getElementById('edit-lectivo-id').value = "";

                    // 4. Restaurar los textos del modal a modo de registro estándar
                    const modalEl = document.getElementById('modalLectivo');
                    modalEl.querySelector('.modal-title').textContent = 'Registrar Nuevo Período Lectivo';
                    modalEl.querySelector('button[type="submit"]').textContent = 'Guardar Registro';

                    Swal.fire({
                        icon: 'success',
                        title: (id && id !== "") ? '¡Actualizado!' : '¡Registrado!',
                        text: 'Operación realizada con éxito.',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    cargarTablaLectivos();
                })
                .catch((err) => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron procesar los cambios en el servidor.'
                    });
                });
        }

        // ==========================================
        // 2. GESTIÓN DE PERIODOS ACADÉMICOS (BLOQUES)
        // ==========================================
        function alCambiarLectivo(lectivoId) {
            const tbody = document.getElementById('tbody-academicos');
            const selectFiltroParciales = document.getElementById('select-academicos-filtro');
            const btnNuevoAcademico = document.getElementById('btn-nuevo-academico');
            const hiddenLectivoId = document.getElementById('hidden-lectivo-id');

            // 1. Limpieza preventiva de la tabla y del selector de la pestaña 2
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3">Cargando bloques...</td></tr>`;
            if (selectFiltroParciales) {
                selectFiltroParciales.innerHTML = '<option value="">-- Seleccione un Bloque Académico --</option>';
            }

            // 🔥 NUEVA CORRECCIÓN: Limpiar en cascada la tabla de la Pestaña 3 (Parciales) 
            // y desactivar su botón para que no arrastre datos del filtro anterior.
            const tbodyParciales = document.getElementById('tbody-parciales');
            const btnNuevoParcial = document.getElementById('btn-nuevo-parcial');
            if (tbodyParciales) {
                tbodyParciales.innerHTML =
                    `<tr><td colspan="5" class="text-center text-muted py-3">Selecciona un bloque académico en el filtro superior.</td></tr>`;
            }
            if (btnNuevoParcial) {
                btnNuevoParcial.disabled = true;
                btnNuevoParcial.classList.add('disabled');
            }

            // 2. Control si el usuario selecciona la opción vacía o deselecciona
            if (!lectivoId || lectivoId === "") {
                tbody.innerHTML =
                    `<tr><td colspan="5" class="text-center text-muted py-3">Selecciona un año lectivo en el filtro superior.</td></tr>`;
                if (btnNuevoAcademico) btnNuevoAcademico.disabled = true;
                if (hiddenLectivoId) hiddenLectivoId.value = '';
                return;
            }

            // (El resto de la función fetch permanece exactamente igual...)
            if (btnNuevoAcademico) {
                btnNuevoAcademico.disabled = false;
                btnNuevoAcademico.classList.remove('disabled');
            }
            if (hiddenLectivoId) hiddenLectivoId.value = lectivoId;

            fetch(`${API_ACADEMICOS}/${lectivoId}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="5" class="text-center text-muted py-3">No hay bloques configurados para este ciclo.</td></tr>`;
                        return;
                    }
                    data.forEach(item => {
                        tbody.innerHTML += `
                    <tr>
                        <td class="font-weight-bold">${item.nombre}</td>
                        <td><span class="badge badge-info">${item.tipo}</span></td>
                        <td class="text-center">${item.orden}</td>
                        <td>${item.fecha_inicio} a ${item.fecha_fin}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-warning btn-editar-registro" 
                                        data-id="${item.id}" data-tipo="academico" data-parent="${item.periodo_lectivo_id}" data-nombre="${item.nombre}" data-tipo-bloque="${item.tipo}" data-orden="${item.orden}" data-inicio="${item.fecha_inicio}" data-fin="${item.fecha_fin}" title="Editar"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-registro" href="${API_ELIMINAR_ACADEMICOS}/${item.id}" data-id="${item.id}" data-tipo="academico" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
                        if (selectFiltroParciales) selectFiltroParciales.innerHTML +=
                            `<option value="${item.id}">${item.nombre}</option>`;
                    });
                }).catch(err => console.error(err));
        }

        // ==========================================
        // 3. GESTIÓN DE PARCIALES DE EVALUACIÓN
        // ==========================================
        function alCambiarAcademico(bloqueId) {
            const tbody = document.getElementById('tbody-parciales');
            const btnNuevoParcial = document.getElementById('btn-nuevo-parcial');
            const hiddenAcademicoId = document.getElementById('hidden-academico-id');

            // 1. Limpieza preventiva de la tabla de parciales
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3">Cargando parciales...</td></tr>`;

            // 2. Control si el usuario selecciona la opción vacía o deselecciona
            if (!bloqueId || bloqueId === "") {
                tbody.innerHTML =
                    `<tr><td colspan="5" class="text-center text-muted py-3">Selecciona un bloque académico en el filtro superior.</td></tr>`;
                if (btnNuevoParcial) btnNuevoParcial.disabled = true;
                if (hiddenAcademicoId) hiddenAcademicoId.value = '';
                return;
            }

            // 3. DIAGNÓSTICO EN CONSOLA: Verifica la captura del ID y existencia del botón
            console.log("Bloque Seleccionado ID:", bloqueId);
            console.log("¿Botón de Parcial encontrado?:", btnNuevoParcial ? "SÍ" : "NO");

            // 4. Activar el botón de inserción de forma nativa y visual (Bootstrap 4)
            if (btnNuevoParcial) {
                btnNuevoParcial.disabled = false; // Quita bloqueo nativo HTML
                btnNuevoParcial.classList.remove('disabled'); // Quita clase gris de Bootstrap
            }

            if (hiddenAcademicoId) {
                hiddenAcademicoId.value = bloqueId; // Pasa el ID al input oculto del modal 3
            }

            // 5. Petición asíncrona por segmento de URL (/api/parciales/ID) hacia tu enrutador
            fetch(`${API_PARCIALES}/${bloqueId}`)
                .then(res => {
                    if (!res.ok) throw new Error(`Error HTTP: ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="5" class="text-center text-muted py-3">No hay parciales asignados a este bloque.</td></tr>`;
                        return;
                    }

                    // 6. Renderizar las filas mapeadas con los campos exactos de tu modelo Parcial
                    data.forEach(item => {
                        tbody.innerHTML += `
                            <tr>
                                <td class="font-weight-bold">${item.nombre} <small class="text-muted">(Orden: ${item.orden})</small></td>
                                <td class="text-center"><span class="badge badge-light">${item.peso_nota}%</span></td>
                                <td>${item.fecha_inicio} al ${item.fecha_fin}</td>
                                <td>
                                    <span class="text-danger font-weight-bold">
                                        <i class="fas fa-clock mr-1"></i>${item.fecha_cierre_notas}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <!-- CORRECCIÓN: Botón unificado con clases y atributos data-* para el Escuchador Global -->
                                        <button type="button" class="btn btn-sm btn-outline-warning btn-editar-registro" 
                                                data-id="${item.id}" 
                                                data-tipo="parcial"
                                                data-parent="${item.periodo_academico_id}"
                                                data-nombre="${item.nombre}"
                                                data-peso="${item.peso_nota}"
                                                data-orden="${item.orden}"
                                                data-inicio="${item.fecha_inicio}"
                                                data-fin="${item.fecha_fin}"
                                                data-cierre="${item.fecha_cierre_notas}"
                                                title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                            
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-registro" 
                                                href="${API_ELIMINAR_PARCIALES}/${item.id}" 
                                                data-id="${item.id}" 
                                                data-tipo="parcial" 
                                                title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });

                })
                .catch(err => {
                    console.error("Error en Fetch Parciales:", err);
                    tbody.innerHTML =
                        `<tr><td colspan="5" class="text-center text-danger py-3">Error al cargar los parciales de evaluación.</td></tr>`;
                });
        }

        // ==========================================
        // NUEVAS FUNCIONES DE GUARDADO ASÍNCRONO
        // ==========================================
        function guardarAcademico(event) {
            event.preventDefault();
            const form = event.target;

            // 1. CAPTURA BLINDADA: Extraer los datos en caliente antes de alterar el modal
            const formData = new FormData(form);
            const objetoData = Object.fromEntries(formData.entries());

            // Leer el ID inyectado por el escuchador global en tu input oculto
            const id = objetoData.id;

            // Decidir la ruta exacta: Si hay ID va a editar/ID, si no, a guardar nuevo
            const url = (id && id !== "") ? `${API_EDITAR_ACADEMICO}/${id}` : API_GUARDAR_ACADEMICO;

            console.log("Enviando Bloque Académico de forma segura -> URL:", url, "Datos:", objetoData);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(objetoData)
                })
                .then(res => {
                    if (!res.ok) throw new Error();
                    return res.json();
                })
                .then(() => {
                    // 2. Cerrar el modal ÚNICAMENTE cuando el servidor confirme el éxito
                    $('#modalAcademico').modal('hide');

                    // 3. Limpieza manual y segura de campos
                    form.reset();
                    if (document.getElementById('edit-academico-id')) {
                        document.getElementById('edit-academico-id').value = "";
                    }

                    // 4. Restaurar la interfaz gráfica al modo de registro estándar
                    const modalEl = document.getElementById('modalAcademico');
                    if (modalEl) {
                        modalEl.querySelector('.modal-title').textContent = 'Registrar Bloque Académico';
                        modalEl.querySelector('button[type="submit"]').textContent = 'Guardar Bloque';
                    }

                    // Alerta profesional de SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: (id && id !== "") ? '¡Actualizado!' : '¡Guardado!',
                        text: 'El bloque académico se procesó correctamente.',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // 5. Recarga quirúrgica manteniendo el filtro activo del ciclo escolar
                    alCambiarLectivo(document.getElementById('select-lectivos-filtro').value);
                })
                .catch((err) => {
                    console.error("Error al procesar bloque:", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron guardar los cambios en el servidor.'
                    });
                });
        }

        function guardarParcial(event) {
            event.preventDefault();
            const form = event.target;

            // 1. CAPTURA INMEDIATA: Extraer los datos antes de realizar cualquier cierre o reset
            const formData = new FormData(form);
            const objetoData = Object.fromEntries(formData.entries());

            // Leer el ID inyectado por el escuchador global en tu input oculto name="id"
            const id = objetoData.id;

            // Decidir la ruta: Si hay ID apunta a editar-parcial/ID; si no, a guardar nuevo parcial
            const url = (id && id !== "") ? `${API_EDITAR_PARCIAL}/${id}` : API_GUARDAR_PARCIAL;

            console.log("Enviando Parcial de forma segura -> URL:", url, "Datos capturados:", objetoData);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(objetoData)
                })
                .then(res => {
                    if (!res.ok) throw new Error(`Error en servidor: ${res.status}`);
                    return res.json();
                })
                .then(() => {
                    // 2. Cerrar el modal ÚNICAMENTE cuando el servidor confirme que guardó con éxito
                    $('#modalParcial').modal('hide');

                    // 3. Limpieza manual y segura post-envío
                    form.reset();
                    if (document.getElementById('edit-parcial-id')) {
                        document.getElementById('edit-parcial-id').value = "";
                    }

                    // 4. Restaurar la interfaz gráfica al modo de registro estándar
                    const modalEl = document.getElementById('modalParcial');
                    if (modalEl) {
                        modalEl.querySelector('.modal-title').textContent = 'Registrar Parcial de Evaluación';
                        modalEl.querySelector('button[type="submit"]').textContent = 'Guardar Parcial';
                    }

                    // Alerta interactiva de SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: (id && id !== "") ? '¡Actualizado!' : '¡Guardado!',
                        text: 'El parcial de evaluación se procesó correctamente.',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // 5. Recarga quirúrgica de la tabla manteniendo el bloque seleccionado activo
                    alCambiarAcademico(document.getElementById('select-academicos-filtro').value);
                })
                .catch((err) => {
                    console.error("Error al procesar parcial:", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron salvar los cambios en el servidor.'
                    });
                });
        }

        // ==========================================
        function abrirEditarAcademico(item) {
            const modal = $('#modalAcademico');
            modal.find('.modal-title').text('Modificar Bloque Académico');
            modal.find('button[type="submit"]').text('Actualizar Cambios');

            // Inyectar ambos IDs sin interferencias
            document.getElementById('edit-academico-id').value = item.id; // Para el UPDATE
            document.getElementById('hidden-lectivo-id').value = item.periodo_lectivo_id; // Mantiene la relación padre

            // Rellenar campos de texto y fechas
            modal.find('input[name="nombre"]').val(item.nombre);
            modal.find('select[name="tipo"]').val(item.tipo);
            modal.find('input[name="orden"]').val(item.orden);
            modal.find('input[name="fecha_inicio"]').val(item.fecha_inicio);
            modal.find('input[name="fecha_fin"]').val(item.fecha_fin);

            modal.modal('show');
        }

        function abrirEditarParcial(item) {
            const modal = $('#modalParcial');
            modal.find('.modal-title').text('Modificar Parcial de Evaluación');
            modal.find('button[type="submit"]').text('Actualizar Cambios');

            // Inyectar ambos IDs sin interferencias
            document.getElementById('edit-parcial-id').value = item.id; // Para el UPDATE
            document.getElementById('hidden-academico-id').value = item.periodo_academico_id; // Mantiene la relación padre

            // Rellenar campos de texto y fechas
            modal.find('input[name="nombre"]').val(item.nombre);
            modal.find('input[name="peso_nota"]').val(item.peso_nota);
            modal.find('input[name="orden"]').val(item.orden);
            modal.find('input[name="fecha_inicio"]').val(item.fecha_inicio);
            modal.find('input[name="fecha_fin"]').val(item.fecha_fin);
            modal.find('input[name="fecha_cierre_notas"]').val(item.fecha_cierre_notas);

            modal.modal('show');
        }
        // ==========================================

        // ==========================================
        // ACCIONES DE ELIMINACIÓN INTERACTIVA
        // ==========================================
        function confirmarEliminarLectivo(id) {
            Swal.fire({
                title: '¿Eliminar Periodo Lectivo?',
                text: "¡Cuidado! Se borrarán de forma permanente todos los bloques y parciales en cascada asociados a este año.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Sí, borrar todo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${API_ELIMINAR_LECTIVOS}/${id}`, {
                            method: 'POST'
                        })
                        .then(res => {
                            if (!res.ok) throw new Error();
                            return res.json();
                        })
                        .then(() => {
                            Swal.fire('¡Eliminado!', 'El ciclo lectivo ha sido removido.', 'success');
                            cargarTablaLectivos(); // Refrescar vista principal
                        })
                        .catch(() => Swal.fire('Error', 'No se pudo eliminar el periodo seleccionado.', 'error'));
                }
            });
        }

        function confirmarEliminarAcademico(id) {
            Swal.fire({
                title: '¿Eliminar Bloque Académico?',
                text: "Se removerán de manera definitiva todos los parciales y configuraciones de notas pertenecientes a este bloque.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${API_ELIMINAR_ACADEMICOS}/${id}`, {
                            method: 'POST'
                        })
                        .then(res => {
                            if (!res.ok) throw new Error();
                            return res.json();
                        })
                        .then(() => {
                            Swal.fire('¡Eliminado!', 'El bloque académico ha sido removido con éxito.',
                                'success');
                            alCambiarLectivo(document.getElementById('select-lectivos-filtro')
                                .value); // Recargar tabla actual
                        })
                        .catch(() => Swal.fire('Error', 'No se pudo procesar la baja del bloque.', 'error'));
                }
            });
        }

        function confirmarEliminarParcial(id) {
            Swal.fire({
                title: '¿Eliminar Parcial de Evaluación?',
                text: "Esta acción removerá el parcial de la grilla de calificaciones de los docentes.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Sí, remover',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${API_ELIMINAR_PARCIALES}/${id}`, {
                            method: 'POST'
                        })
                        .then(res => {
                            if (!res.ok) throw new Error();
                            return res.json();
                        })
                        .then(() => {
                            Swal.fire('¡Removido!', 'El parcial ha sido eliminado del sistema.', 'success');
                            alCambiarAcademico(document.getElementById('select-academicos-filtro')
                                .value); // Recargar tabla actual
                        })
                        .catch(() => Swal.fire('Error', 'No se pudo eliminar el parcial de evaluación.', 'error'));
                }
            });
        }

        // ======================================================================
        // ESCUCHADOR GLOBAL: Controla Eliminación y Edición (JavaScript Puro)
        // ======================================================================
        document.addEventListener('click', async (e) => {

            // ------------------------------------------------------------------
            // ACCIÓN 1: INTERCEPTAR EL CLIC DE ELIMINACIÓN (.btn-eliminar-registro)
            // ------------------------------------------------------------------
            const btnEliminar = e.target.closest('.btn-eliminar-registro');
            if (btnEliminar) {
                e.preventDefault();

                const id = btnEliminar.getAttribute('data-id');
                const tipo = btnEliminar.getAttribute('data-tipo');
                const urlEliminar = btnEliminar.getAttribute('href');

                let mensajeAdvertencia = "Esta acción eliminará el registro de forma permanente.";
                if (tipo === 'lectivo') mensajeAdvertencia =
                    "¡Cuidado! Se borrarán todos los bloques y parciales en cascada asociados a este año.";
                if (tipo === 'academico') mensajeAdvertencia =
                    "Se removerán definitivamente todos los parciales y configuraciones de notas de este bloque.";

                const confirmacion = await Swal.fire({
                    title: '¿Estás seguro?',
                    text: mensajeAdvertencia,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                });

                if (confirmacion.isConfirmed) {
                    Swal.fire({
                        title: 'Eliminando...',
                        text: 'Por favor, espere mientras se actualizan los registros escolares.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const response = await fetch(urlEliminar, {
                            method: 'POST'
                        });
                        if (!response.ok) throw new Error(`Error en el servidor: ${response.status}`);

                        const resultado = await response.json();

                        if (resultado.ok || resultado.success) {
                            await Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: resultado.mensaje || 'El registro ha sido removido.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            if (tipo === 'lectivo') {
                                cargarTablaLectivos();
                            } else if (tipo === 'academico') {
                                alCambiarLectivo(document.getElementById('select-lectivos-filtro').value);
                            } else if (tipo === 'parcial') {
                                alCambiarAcademico(document.getElementById('select-academicos-filtro').value);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'No se pudo eliminar',
                                text: resultado.mensaje
                            });
                        }
                    } catch (error) {
                        console.error('Error al eliminar:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Red',
                            text: 'Problema de conectividad con el servidor.'
                        });
                    }
                }
                return;
            }

            // ------------------------------------------------------------------
            // ACCIÓN 2: INTERCEPTAR EL CLIC DE EDICIÓN (.btn-editar-registro)
            // ------------------------------------------------------------------
            const btnEditar = e.target.closest('.btn-editar-registro');
            if (btnEditar) {
                e.preventDefault();

                const tipo = btnEditar.getAttribute('data-tipo');
                const id = btnEditar.getAttribute('data-id');

                // Caso A: Modificar Periodo Lectivo (Pestaña 1)
                if (tipo === 'lectivo') {
                    const modal = document.getElementById('modalLectivo');
                    modal.querySelector('.modal-title').textContent = 'Modificar Período Lectivo';
                    modal.querySelector('button[type="submit"]').textContent = 'Actualizar Cambios';

                    document.getElementById('edit-lectivo-id').value = id;
                    modal.querySelector('input[name="nombre"]').value = btnEditar.getAttribute('data-nombre');
                    modal.querySelector('input[name="fecha_inicio"]').value = btnEditar.getAttribute(
                        'data-inicio');
                    modal.querySelector('input[name="fecha_fin"]').value = btnEditar.getAttribute('data-fin');

                    $('#modalLectivo').modal('show'); // Único uso de jQuery obligado por Bootstrap 4

                    // Caso B: Modificar Bloque Académico (Pestaña 2)
                } else if (tipo === 'academico') {
                    const modal = document.getElementById('modalAcademico');
                    const selectTipo = modal.querySelector('select[name="tipo"]');
                    const valorBaseDatos = btnEditar.getAttribute('data-tipo-bloque');

                    modal.querySelector('.modal-title').textContent = 'Modificar Bloque Académico';
                    modal.querySelector('button[type="submit"]').textContent = 'Actualizar Cambios';

                    document.getElementById('edit-academico-id').value = id;
                    document.getElementById('hidden-lectivo-id').value = btnEditar.getAttribute('data-parent');
                    modal.querySelector('input[name="nombre"]').value = btnEditar.getAttribute('data-nombre');
                    modal.querySelector('select[name="tipo"]').value = btnEditar.getAttribute(
                        'data-tipo-bloque');
                    modal.querySelector('input[name="orden"]').value = btnEditar.getAttribute('data-orden');
                    modal.querySelector('input[name="fecha_inicio"]').value = btnEditar.getAttribute(
                        'data-inicio');
                    modal.querySelector('input[name="fecha_fin"]').value = btnEditar.getAttribute('data-fin');

                    if (selectTipo && valorBaseDatos) {
                        // Convertimos el valor de la BD a minúsculas y limpiamos espacios para comparar de forma segura
                        const valorBuscar = valorBaseDatos.trim().toLowerCase();
                        let coincidenciaEncontrada = false;

                        // Recorremos dinámicamente todas las opciones que existan en el select HTML
                        Array.from(selectTipo.options).forEach(option => {
                            if (option.value.trim().toLowerCase() === valorBuscar) {
                                option.selected = true; // Activa la selección automáticamente
                                coincidenciaEncontrada = true;
                            }
                        });

                        // Opcional: Si el valor de la base de datos no coincide con ninguna opción, 
                        // dejamos la primera por defecto para evitar que se quede roto.
                        if (!coincidenciaEncontrada && selectTipo.options.length > 0) {
                            selectTipo.options[0].selected = true;
                        }
                    }

                    $('#modalAcademico').modal('show');

                    // Caso C: Modificar Parcial de Evaluación (Pestaña 3)
                } else if (tipo === 'parcial') {
                    const modal = document.getElementById('modalParcial');
                    modal.querySelector('.modal-title').textContent = 'Modificar Parcial de Evaluación';
                    modal.querySelector('button[type="submit"]').textContent = 'Actualizar Cambios';

                    document.getElementById('edit-parcial-id').value = id;
                    document.getElementById('hidden-academico-id').value = btnEditar.getAttribute(
                        'data-parent');
                    modal.querySelector('input[name="nombre"]').value = btnEditar.getAttribute('data-nombre');
                    modal.querySelector('input[name="peso_nota"]').value = btnEditar.getAttribute('data-peso');
                    modal.querySelector('input[name="orden"]').value = btnEditar.getAttribute('data-orden');
                    modal.querySelector('input[name="fecha_inicio"]').value = btnEditar.getAttribute(
                        'data-inicio');
                    modal.querySelector('input[name="fecha_fin"]').value = btnEditar.getAttribute('data-fin');

                    // 🎯 CORRECCIÓN QUIRÚRGICA: Recuperar el texto plano y aislar la fecha (YYYY-MM-DD) de la hora
                    const fechaCierreRaw = btnEditar.getAttribute('data-cierre') || '';

                    // Si la fecha contiene la hora (un espacio en blanco), la divide y toma solo la primera parte
                    const fechaLimpia = fechaCierreRaw.includes(' ') ? fechaCierreRaw.split(' ')[0] :
                        fechaCierreRaw;

                    // Inyectar el formato puro de 10 caracteres que el input HTML5 exige
                    modal.querySelector('input[name="fecha_cierre_notas"]').value = fechaLimpia.trim();

                    $('#modalParcial').modal('show');
                }
                return;
            }
        });

        // Listener nativo sin rastro de jQuery para restaurar los modales cuando se ocultan
        ['modalLectivo', 'modalAcademico', 'modalParcial'].forEach(modalId => {
            const modalEl = document.getElementById(modalId);
            if (modalEl) {
                // CORRECCIÓN: Usamos el event listener nativo del navegador 'hidden.bs.modal'
                modalEl.addEventListener('hidden.bs.modal', function() {
                    const form = modalEl.querySelector('form');
                    if (form) form.reset(); // Limpieza nativa de campos de texto/fechas

                    // Restaurar textos e interfaz a modo de registro estándar
                    const title = modalEl.querySelector('.modal-title');
                    if (title && title.textContent.includes('Modificar')) {
                        title.textContent = title.textContent.replace('Modificar', 'Registrar');

                        const btnSubmit = modalEl.querySelector('button[type="submit"]');
                        if (btnSubmit) btnSubmit.textContent = 'Guardar Registro';
                    }

                    // Vaciar explícitamente los inputs ocultos de EDICIÓN estrictamente
                    const editLectivo = document.getElementById('edit-lectivo-id');
                    const editAcademico = document.getElementById('edit-academico-id');
                    const editParcial = document.getElementById('edit-parcial-id');

                    if (editLectivo) editLectivo.value = "";
                    if (editAcademico) editAcademico.value = "";
                    if (editParcial) editParcial.value = "";
                });
            }
        });
    </script>
@endsection
