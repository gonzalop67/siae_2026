@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Lista de Subniveles de Educación</h1>

            <!-- Reemplaza el antiguo enlace <a> por este botón de acción directa -->
            <button class="btn btn-primary btn-sm mr-1 m-b-10 btn-nuevo" data-toggle="modal" data-target="#modalSubnivel">
                <i class="fas fa-plus"></i> Nuevo Subnivel
            </button>

            <!-- 💡 PASO CRÍTICO: El contenedor que recibirá el AJAX -->
            <div id="contenedor-tabla">
                @include('admin.subniveles.partials.tabla')
            </div>
        </div>
    </div>

    <!-- Ventana Modal para Registro / Edición de Subniveles -->
    <div class="modal fade" id="modalSubnivel" tabindex="-1" role="dialog" aria-labelledby="modalSubnivelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Título dinámico que puedes cambiar con JS si lo deseas -->
                    <h5 class="modal-title" id="modalSubnivelLabel">Formulario de Subnivel</h5>
                    <button type="button" class="close btn-cerrar-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario" novalidate>

                        <!-- Campo oculto para almacenar el ID del subnivel al editar -->
                        <input type="hidden" id="id_subnivel" name="id_subnivel" value="">

                        <!-- 1. Combo de Selección del Nivel Padre -->
                        <div class="form-group mb-3">
                            <label for="nivel_id" class="form-label">Nivel Educativo Padre <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="nivel_id" name="nivel_id" required>
                                <option value="">-- Seleccione el Nivel Principal --</option>
                                <!-- 💡 CORRECCIÓN: Recorrer el array plano '$niveles' enviado desde el controlador -->
                                <?php if (!empty($niveles)): ?>
                                <?php foreach ($niveles as $nivel): ?>
                                <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <!-- Contenedor dinámico de error para tu JS -->
                            <div class="invalid-feedback" id="error-nivel_id" style="display: none;"></div>
                        </div>

                        <!-- 2. Campo de Texto para el Nombre del Subnivel -->
                        <div class="form-group mb-3">
                            <label for="nombre" class="form-label">Nombre del Subnivel <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Ej: Inicial 1, Básica Superior, etc." required>
                            <!-- Contenedor dinámico de error para tu JS -->
                            <div class="invalid-feedback" id="error-nombre" style="display: none;"></div>
                        </div>

                        <!-- Botones de Control del Formulario -->
                        <div class="modal-footer px-0 pb-0 mt-4">
                            <button type="button" class="btn btn-light btn-cancelar" data-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btn-submit" class="btn btn-primary">Guardar Subnivel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ RUTA_URL }}/public/assets/js/pages/admin/subniveles/crear.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ======================================================================
            // 💡 AQUÍ EXACTAMENTE VA EL ESCUCHADOR PARA NUEVO RECURSO
            // ======================================================================
            const btnNuevo = document.querySelector('.btn-nuevo');
            if (btnNuevo) {
                btnNuevo.addEventListener('click', () => {
                    // Asegurar el título correcto en el encabezado del modal
                    document.getElementById('modalSubnivelLabel').innerText =
                        'Registrar Nuevo Subnivel Educativo';

                    // Forzar que el botón de envío esté en su estado inicial de inserción
                    const buttonSubmit = document.getElementById('btn-submit');
                    if (buttonSubmit) {
                        buttonSubmit.innerText = 'Guardar Subnivel';
                        buttonSubmit.classList.remove('btn-warning', 'btn-info');
                        buttonSubmit.classList.add('btn-primary');
                    }

                    // Garantizar que el combo padre esté visible y sea obligatorio
                    const selectNivelPadre = document.getElementById('nivel_id');
                    if (selectNivelPadre) {
                        selectNivelPadre.closest('.form-group').style.display = 'block';
                        selectNivelPadre.setAttribute('required', 'required');
                    }

                    // Limpiar la casilla oculta del ID por seguridad
                    document.getElementById('id_subnivel').value = '';
                });
            }

            // 💡 CORRECCIÓN CRÍTICA: Escuchar el contenedor estático que NUNCA se elimina del DOM por AJAX
            const contenedorTabla = document.getElementById('contenedor-tabla');
            if (!contenedorTabla) return;

            contenedorTabla.addEventListener('click', async (e) => {

                // ======================================================================
                // --- INTERRUPTOR A: Alternar subniveles (Expandir / Colapsar) ---------
                // ======================================================================
                const btn = e.target.closest('.btn-toggle-subnivel');
                if (btn) {
                    const idPadre = btn.getAttribute('data-target');
                    const filasHijas = document.querySelectorAll(`.subnivel-of-${idPadre}`);
                    const icono = btn.querySelector('i');

                    filasHijas.forEach(fila => {
                        if (fila.style.display === 'none') {
                            fila.style.display = 'table-row';
                            if (icono) icono.className = 'mdi mdi-chevron-down';
                        } else {
                            fila.style.display = 'none';
                            if (icono) icono.className = 'mdi mdi-chevron-right';
                        }
                    });

                    return; // Salir del evento si la acción fue expandir/colapsar
                }

                // ======================================================================
                // --- INTERRUPTOR B: Eliminación lógica (Soft Delete mediante Fetch) ---
                // ======================================================================
                const btnEliminar = e.target.closest('.btn-eliminar-subnivel');
                if (btnEliminar) {
                    if (btnEliminar.disabled) return;

                    const idSubnivel = btnEliminar.getAttribute('data-id');
                    const fila = btnEliminar.closest('tr');

                    btnEliminar.disabled = true;

                    const resultado = await Swal.fire({
                        title: '¿Estás seguro?',
                        text: "El subnivel se desactivará del sistema.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    });

                    if (resultado.isConfirmed) {
                        try {
                            const respuesta = await fetch(
                                `${base_url}/subniveles/${idSubnivel}/delete`, {
                                    method: 'POST',
                                    cache: 'no-cache'
                                });

                            const texto = await respuesta.text();
                            let json;

                            try {
                                json = JSON.parse(texto);
                            } catch (err) {
                                console.error("El servidor no devolvió un JSON válido:", texto);
                                throw new Error("Respuesta inválida del servidor.");
                            }

                            if (json.ok || json.success) {
                                Swal.fire({
                                    title: '¡Eliminado!',
                                    text: json.mensaje ||
                                        'El subnivel ha sido eliminado lógicamente.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                fila.style.transition = 'all 0.5s ease';
                                fila.style.opacity = '0';
                                setTimeout(() => fila.remove(), 500);

                            } else {
                                Swal.fire('Error', json.mensaje || 'No se pudo eliminar el registro.',
                                    'error');
                                btnEliminar.disabled = false;
                            }

                        } catch (error) {
                            console.error('Error crítico:', error);
                            Swal.fire('Error Crítico', 'Hubo un problema de conexión con el servidor.',
                                'error');
                            btnEliminar.disabled = false;
                        }
                    } else {
                        btnEliminar.disabled = false;
                    }

                    return; // Finalizar la acción del interruptor B
                }

                // ======================================================================
                // --- 💡 NUEVO INTERRUPTOR C: Cargar datos para Edición (Fetch GET) ----
                // ======================================================================
                const btnEditar = e.target.closest('.btn-editar-subnivel');
                if (btnEditar) {
                    if (btnEditar.disabled) return;

                    const idSubnivel = btnEditar.getAttribute('data-id');
                    btnEditar.disabled = true; // Bloquear para evitar peticiones paralelas

                    try {
                        // Realizar petición GET nativa a tu controlador
                        const respuesta = await fetch(`${base_url}/subniveles/${idSubnivel}/edit`, {
                            method: 'GET',
                            cache: 'no-cache'
                        });

                        const texto = await respuesta.text();
                        let json;

                        try {
                            json = JSON.parse(texto);
                        } catch (err) {
                            console.error("El servidor no devolvió un JSON válido para edición:",
                                texto);
                            throw new Error("Respuesta inválida del servidor.");
                        }

                        if (json.ok || json.success) {
                            const data = json.data;

                            // Rellenar las casillas del formulario con la respuesta del backend
                            document.getElementById('id_subnivel').value = data.id;
                            document.getElementById('nivel_id').value = data.nivel_id;
                            document.getElementById('nombre').value = data.nombre;

                            // Modificar visualmente tu botón submit (#btn-submit)
                            const buttonSubmit = document.getElementById('btn-submit');
                            if (buttonSubmit) {
                                buttonSubmit.innerText = 'Actualizar';
                                buttonSubmit.classList.remove('btn-primary');
                                buttonSubmit.classList.add('btn-warning'); // Estilo naranja de edición
                            }

                            // 💡 COMPATIBILIDAD ANNEX: Abrir la ventana modal de Bootstrap 4 mediante jQuery
                            $('#modalSubnivel').modal('show');

                            // Opcional: Enfocar automáticamente el primer input útil
                            document.getElementById('nombre').focus();

                        } else {
                            Swal.fire('Error', json.mensaje ||
                                'No se pudo recuperar la información del registro.', 'error');
                        }

                    } catch (error) {
                        console.error('Error crítico al editar:', error);
                        Swal.fire('Error Crítico',
                            'No se pudo comunicar con el servidor para precargar los datos.',
                            'error');
                    } finally {
                        btnEditar.disabled = false; // Desbloquear siempre al terminar el flujo
                    }

                    return; // Finalizar la acción del interruptor C
                }

                // ======================================================================
                // --- 💡 NUEVO INTERRUPTOR D: Cargar datos de NIVEL PADRE para Edición --
                // ======================================================================
                const btnEditarNivel = e.target.closest('.btn-editar-nivel');
                if (btnEditarNivel) {
                    if (btnEditarNivel.disabled) return;
                    const idNivel = btnEditarNivel.getAttribute('data-id');
                    btnEditarNivel.disabled = true;

                    try {
                        // 1. Realizar petición GET al endpoint de niveles principales
                        const respuesta = await fetch(`${base_url}/niveles/${idNivel}/datos-ajax`, {
                            method: 'GET',
                            cache: 'no-cache'
                        });
                        const json = await respuesta.json();

                        if (json.ok || json.success) {
                            const data = json.data;

                            // 2. Rellenar las casillas adaptando el formulario híbrido
                            document.getElementById('id_subnivel').value = data
                                .id; // Guardamos el ID en la misma casilla oculta
                            document.getElementById('nombre').value = data
                                .nombre; // Cargamos el nombre actual del nivel

                            // Ocultamos el campo del combo "Nivel Padre" porque un nivel principal no tiene padre
                            document.getElementById('nivel_id').closest('.form-group').style.display =
                                'none';
                            // Quitamos temporalmente la propiedad required para que la validación no falle al enviar
                            document.getElementById('nivel_id').removeAttribute('required');

                            // 3. Modificar visualmente el botón submit
                            const buttonSubmit = document.getElementById('btn-submit');
                            if (buttonSubmit) {
                                buttonSubmit.innerText = 'Actualizar Nivel';
                                buttonSubmit.classList.remove('btn-primary', 'btn-warning');
                                buttonSubmit.classList.add(
                                    'btn-info'
                                ); // Color azul distintivo para diferenciar que es un Nivel
                            }

                            // Cambiar dinámicamente el título del encabezado de la ventana modal
                            document.getElementById('modalSubnivelLabel').innerText =
                                'Editar Nivel Educativo Principal';

                            // 4. Abrir la ventana modal
                            $('#modalSubnivel').modal('show');
                            document.getElementById('nombre').focus();
                        } else {
                            Swal.fire('Error', json.mensaje ||
                                'No se pudo recuperar la información del nivel.', 'error');
                        }
                    } catch (error) {
                        console.error('Error crítico al editar nivel:', error);
                        Swal.fire('Error Crítico',
                            'No se pudo comunicar con el servidor para precargar el nivel.', 'error'
                        );
                    } finally {
                        btnEditarNivel.disabled = false;
                    }
                    return;
                }
            });
        });

        // Listener global para el cierre/ocultamiento de la ventana modal
        if (typeof jQuery !== 'undefined') {
            // Escucha el momento exacto en que el modal de Annex se cierra/oculta
            jQuery('#modalSubnivel').on('hidden.bs.modal', function() {
                const formulario = document.getElementById('formulario');
                const buttonSubmit = document.getElementById('btn-submit');
                const selectNivelPadre = document.getElementById('nivel_id');
                const inputs = document.querySelectorAll('#formulario input, #formulario select');

                if (formulario) {
                    formulario.reset();
                    document.getElementById('id_subnivel').value = ''; // Crítico: vaciar ID de edición
                }

                if (buttonSubmit) {
                    buttonSubmit.innerText = 'Guardar Subnivel';
                    buttonSubmit.classList.remove('btn-warning', 'btn-info');
                    buttonSubmit.classList.add('btn-primary');
                }

                // Restaurar visibilidad y requerimiento obligatorio del combo nivel padre
                if (selectNivelPadre) {
                    selectNivelPadre.closest('.form-group').style.display = 'block';
                    selectNivelPadre.setAttribute('required', 'required');
                }

                document.getElementById('modalSubnivelLabel').innerText = 'Formulario de Subnivel';

                // Limpiar todas las clases rojas y verdes de validación
                inputs.forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                    const errorEl = document.getElementById(`error-${input.name || input.id}`);
                    if (errorEl) errorEl.style.display = 'none';
                });
            });
        }
    </script>
@endsection
