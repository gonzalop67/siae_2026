@extends('layout.app')

@section('styles')
    <style>
        /* Apunta al elemento SVG que inyecta Font Awesome 6 */
        .btn-toggle-cursos.active svg {
            transform: rotate(-90deg) !important;
        }

        .btn-toggle-cursos svg {
            transition: transform 0.2s ease !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Lista de Cursos por Subnivel</h1>

            <!-- Botón de acción directa para abrir el modal híbrido -->
            <button class="btn btn-primary btn-sm mr-1 m-b-10 btn-nuevo" data-toggle="modal" data-target="#modalCurso">
                <i class="fas fa-plus"></i> Nuevo Curso
            </button>

            <!-- Contenedor asíncrono que renderiza el partial -->
            <div id="contenedor-tabla">
                @include('admin.cursos.partials.tabla')
            </div>
        </div>
    </div>

    <!-- Modal de Cursos -->
    <div class="modal fade" id="modalCurso" tabindex="-1" role="dialog" aria-labelledby="modalCursoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalCursoLabel">Nuevo Curso</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCurso" novalidate>
                    <div class="modal-body">
                        <!-- ID Oculto: Si está vacío es CREAR, si tiene valor es EDITAR -->
                        <input type="hidden" id="curso_id" name="id">

                        <!-- Campo: Nombre del Curso -->
                        <div class="form-group">
                            <label for="nombre" class="font-weight-bold">Nombre del Curso <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Ej. Primero de Bachillerato" required>
                            <div class="invalid-feedback">Por favor, ingresa el nombre del curso.</div>
                        </div>

                        <!-- Campo: Selección de Subnivel -->
                        <div class="form-group">
                            <label for="subnivel_id" class="font-weight-bold">Subnivel Educativo <span
                                    class="text-danger">*</span></label>
                            <select class="form-control custom-select" id="subnivel_id" name="subnivel_id" required>
                                <option value="" selected disabled>-- Selecciona un Subnivel --</option>
                                <?php if(!empty($subnivelesPlanos)): ?>
                                <?php foreach($subnivelesPlanos as $sub): ?>
                                <option value="<?= $sub['id'] ?>"><?= htmlspecialchars($sub['nombre']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">Por favor, selecciona un subnivel educativo.</div>
                        </div>

                        <!-- Campo: Sección (ENUM) -->
                        <div class="form-group">
                            <label for="seccion" class="font-weight-bold">Sección / Horario <span
                                    class="text-danger">*</span></label>
                            <select class="form-control custom-select" id="seccion" name="seccion" required>
                                <option value="Matutina" selected>Matutina</option>
                                <option value="Vespertina">Vespertina</option>
                                <option value="Nocturna">Nocturna</option>
                            </select>
                            <div class="invalid-feedback">Por favor, selecciona una sección válida.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="btnGuardar">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Adaptativo para Editar Subnivel Educativo (Acción D) -->
    <div class="modal fade" id="modalSubnivel" tabindex="-1" role="dialog" aria-labelledby="modalSubnivelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalSubnivelLabel">Editar Subnivel Educativo</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- ID único para el formulario de subniveles -->
                <form id="formSubnivel" novalidate>
                    <div class="modal-body">
                        <!-- ID Oculto del Subnivel mapeado en JavaScript -->
                        <input type="hidden" id="id_subnivel" name="id">

                        <!-- Campo: Nombre del Subnivel (ID modificado para evitar colisión con el de cursos) -->
                        <div class="form-group">
                            <label for="nombre_subnivel" class="font-weight-bold">Nombre del Subnivel <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_subnivel" name="nombre"
                                placeholder="Ej. Educación General Básica Superior" required>
                            <div class="invalid-feedback">Por favor, ingresa el nombre del subnivel.</div>
                        </div>

                        <!-- Campo: Selección de Nivel Principal (Aparece o desaparece dinámicamente con JS) -->
                        <div class="form-group">
                            <label for="nivel_id" class="font-weight-bold">Nivel Institucional Padre <span
                                    class="text-danger">*</span></label>
                            <select class="form-control custom-select" id="nivel_id" name="nivel_id" required>
                                <option value="" selected disabled>-- Selecciona el Nivel Institucional --</option>
                                <?php if(!empty($nivelesPlanos)): ?>
                                <?php foreach($nivelesPlanos as $niv): ?>
                                <option value="<?= $niv['id'] ?>"><?= htmlspecialchars($niv['nombre']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">Por favor, selecciona el nivel institucional jerárquico.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                        <!-- ID único para conmutar texto y color desde el JavaScript -->
                        <button type="submit" class="btn btn-info btn-sm" id="btn-submit-subnivel">
                            Actualizar Subnivel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const formCurso = document.getElementById('formCurso');
            const modalElement = document.getElementById('modalCurso');

            // 1. MANEJAR CLICS GLOBALES (Alternar cursos, Editar, Eliminar)
            document.addEventListener('click', async (e) => {

                // ACCIÓN A: Alternar colapso/expansión de las filas de cursos
                const btnToggle = e.target.closest('.btn-toggle-cursos');
                if (btnToggle) {
                    const targetClass = btnToggle.getAttribute('data-target');
                    const rows = document.querySelectorAll(`.${targetClass}`);

                    rows.forEach(row => {
                        const isHidden = window.getComputedStyle(row).display === 'none';
                        row.style.display = isHidden ? 'table-row' : 'none';
                    });

                    btnToggle.classList.toggle('active');
                    return;
                }

                // ACCIÓN B: Capturar datos para EDICIÓN de un CURSO (Optimizado con bloqueo y estilos)
                const btnEditar = e.target.closest('.btn-editar-curso');
                if (btnEditar) {
                    if (btnEditar.disabled) return;
                    const id = btnEditar.getAttribute('data-id');
                    btnEditar.disabled = true; // Bloquear para evitar peticiones paralelas

                    document.getElementById('modalCursoLabel').textContent = 'Editar Curso';
                    formCurso.classList.remove('was-validated');

                    try {
                        // Realizar petición GET nativa al controlador de cursos
                        const response = await fetch(`${base_url}/cursos/${id}`, {
                            method: 'GET',
                            cache: 'no-cache'
                        });
                        const texto = await response.text();

                        let resultado;
                        try {
                            resultado = JSON.parse(texto);
                        } catch (err) {
                            console.error("El servidor no devolvió un JSON válido para edición:",
                            texto);
                            throw new Error("Respuesta inválida del servidor.");
                        }

                        if (resultado.ok || resultado.success) {
                            const data = resultado.data;

                            // Rellenar las casillas específicas del formulario de cursos
                            document.getElementById('curso_id').value = data.id;
                            document.getElementById('nombre').value = data.nombre;
                            document.getElementById('subnivel_id').value = data.subnivel_id;
                            document.getElementById('seccion').value = data
                            .seccion; // Mapea el valor ENUM

                            // Modificar visualmente el botón submit de cursos (#btnGuardar)
                            const buttonSubmit = document.getElementById('btnGuardar');
                            if (buttonSubmit) {
                                buttonSubmit.innerHTML = '<i class="fas fa-save mr-1"></i> Actualizar';
                                buttonSubmit.classList.remove('btn-primary');
                                buttonSubmit.classList.add('btn-warning'); // Estilo naranja de edición
                            }

                            // 💡 COMPATIBILIDAD ANNEX: Abrir la ventana modal de Bootstrap 4 mediante jQuery
                            $(modalElement).modal('show');

                            // Enfocar automáticamente el input del nombre
                            document.getElementById('nombre').focus();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: resultado.mensaje ||
                                    'No se pudo recuperar la información del registro.'
                            });
                        }
                    } catch (error) {
                        console.error('Error crítico al editar curso:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Crítico',
                            text: 'No se pudo comunicar con el servidor para precargar los datos.'
                        });
                    } finally {
                        btnEditar.disabled = false; // Desbloquear siempre al terminar el flujo
                    }
                    return;
                }

                // ACCIÓN C: ELIMINAR un curso de la tabla
                const btnEliminar = e.target.closest('.btn-eliminar-curso');
                if (btnEliminar) {
                    const id = btnEliminar.getAttribute('data-id');

                    const confirmacion = await Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    });

                    if (confirmacion.isConfirmed) {
                        try {
                            const response = await fetch(`${base_url}/cursos/${id}/delete`, {
                                method: 'POST'
                            });
                            const resultado = await response.json();

                            if (resultado.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: resultado.mensaje,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                recargarTabla();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: resultado.mensaje
                                });
                            }
                        } catch (error) {
                            console.error('Error al eliminar:', error);
                        }
                    }
                    return;
                }

                // ACCIÓN D: Cargar datos de SUBNIVEL PADRE para Edición rápida (Adaptado)
                const btnEditarSubnivel = e.target.closest('.btn-editar-subnivel');
                if (btnEditarSubnivel) {
                    if (btnEditarSubnivel.disabled) return;
                    const idSubnivel = btnEditarSubnivel.getAttribute('data-id');
                    btnEditarSubnivel.disabled = true;

                    try {
                        // Realizar petición GET al módulo de subniveles
                        const respuesta = await fetch(
                            `${base_url}/subniveles/${idSubnivel}/datos-ajax`, {
                                method: 'GET',
                                cache: 'no-cache'
                            });
                        const json = await respuesta.json();

                        if (json.ok || json.success) {
                            const data = json.data;

                            // Si usas un modal exclusivo o reutilizas el del módulo anterior:
                            // Asegúrate de tener un formulario con id="formSubnivel" o mapea a sus campos correspondientes
                            const formSub = document.getElementById('formSubnivel');
                            if (formSub) formSub.classList.remove('was-validated');

                            document.getElementById('id_subnivel').value = data.id;
                            document.getElementById('nombre_subnivel').value = data
                                .nombre; // Evitamos colisión de IDs con 'nombre' del curso

                            if (document.getElementById('nivel_id')) {
                                document.getElementById('nivel_id').value = data.nivel_id;
                                document.getElementById('nivel_id').closest('.form-group').style
                                    .display = 'block';
                                document.getElementById('nivel_id').setAttribute('required',
                                    'required');
                            }

                            const buttonSubmitSub = document.getElementById('btn-submit-subnivel');
                            if (buttonSubmitSub) {
                                buttonSubmitSub.innerText = 'Actualizar Subnivel';
                                buttonSubmitSub.className = 'btn btn-info btn-sm';
                            }

                            document.getElementById('modalSubnivelLabel').innerText =
                                'Editar Subnivel Educativo';
                            $('#modalSubnivel').modal('show');
                        } else {
                            Swal.fire('Error', json.mensaje ||
                                'No se pudo recuperar la información del subnivel.', 'error');
                        }
                    } catch (error) {
                        console.error('Error crítico al editar subnivel:', error);
                        Swal.fire('Error Crítico', 'No se pudo comunicar con el servidor.', 'error');
                    } finally {
                        btnEditarSubnivel.disabled = false;
                    }
                    return;
                }
            });

            // 2. BOTÓN "NUEVO CURSO" (Restablecer formulario al abrir vacío)
            const btnNuevo = document.querySelector('.btn-nuevo');
            if (btnNuevo) {
                btnNuevo.addEventListener('click', () => {
                    formCurso.reset();
                    document.getElementById('curso_id').value = '';
                    document.getElementById('modalCursoLabel').textContent = 'Nuevo Curso';
                    formCurso.classList.remove('was-validated');
                });
            }

            // 3. PROCESAR EL ENVÍO DEL FORMULARIO DE CURSOS (Crear y Actualizar vía AJAX)
            formCurso.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (!formCurso.checkValidity()) {
                    formCurso.classList.add('was-validated');
                    return;
                }

                const id = document.getElementById('curso_id').value;
                const formData = new FormData(formCurso);
                const url = id ? `${base_url}/cursos/${id}/update` : `${base_url}/cursos`;

                const btnGuardar = document.getElementById('btnGuardar');
                btnGuardar.disabled = true;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData
                    });

                    const resultado = await response.json();

                    if (resultado.ok) {
                        $(modalElement).modal('hide');
                        formCurso.reset();
                        formCurso.classList.remove('was-validated');

                        Swal.fire({
                            icon: 'success',
                            title: '¡Operación Exitosa!',
                            text: resultado.mensaje,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        recargarTabla();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resultado.mensaje || 'Ocurrió un problema.'
                        });
                    }
                } catch (error) {
                    console.error('Error en la petición:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Red',
                        text: 'No se pudo conectar con el servidor.'
                    });
                } finally {
                    btnGuardar.disabled = false;
                }
            });

            // ======================================================================
            // --- PROCESAR EL ENVÍO DEL FORMULARIO DE SUBNIVELES (Acción D - AJAX) --
            // ======================================================================
            const formSubnivel = document.getElementById('formSubnivel');

            if (formSubnivel) {
                formSubnivel.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    // Validación visual de Bootstrap 4
                    if (!formSubnivel.checkValidity()) {
                        formSubnivel.classList.add('was-validated');
                        return;
                    }

                    const idSubnivel = document.getElementById('id_subnivel').value;
                    const formData = new FormData(formSubnivel);

                    // Endpoint para la actualización de subniveles
                    const url = `${base_url}/subniveles/${idSubnivel}/update`;

                    const btnSubmitSubnivel = document.getElementById('btn-submit-subnivel');
                    if (btnSubmitSubnivel) btnSubmitSubnivel.disabled = true;

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData
                        });

                        const resultado = await response.json();

                        if (resultado.ok || resultado.success) {
                            // Cerrar el modal usando Bootstrap 4 nativo
                            $('#modalSubnivel').modal('hide');
                            formSubnivel.reset();
                            formSubnivel.classList.remove('was-validated');

                            Swal.fire({
                                icon: 'success',
                                title: '¡Estructura Actualizada!',
                                text: resultado.mensaje ||
                                    'El subnivel educativo fue modificado correctamente.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // 💡 Refrescar la tabla principal para actualizar los nombres de los subniveles padres
                            recargarTabla();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de validación',
                                text: resultado.mensaje ||
                                    'No se pudieron guardar los cambios en el subnivel.'
                            });
                        }
                    } catch (error) {
                        console.error('Error al actualizar subnivel via AJAX:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Red',
                            text: 'No se pudo establecer conexión para actualizar el subnivel.'
                        });
                    } finally {
                        if (btnSubmitSubnivel) btnSubmitSubnivel.disabled = false;
                    }
                });
            }

            // 4. FUNCIÓN AUXILIAR: Recargar el contenedor parcial HTML de la tabla
            async function recargarTabla() {
                try {
                    const response = await fetch(`${base_url}/cursos/tabla-html`);
                    const html = await response.text();
                    document.getElementById('contenedor-tabla').innerHTML = html;
                } catch (error) {
                    console.error('Error al refrescar la tabla:', error);
                }
            }
        });
    </script>
@endsection
