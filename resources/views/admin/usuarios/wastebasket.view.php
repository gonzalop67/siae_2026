@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Page Heading (Modificado para la papelera) -->
            <h1 class="h3 mb-4 text-gray-800">Usuarios Eliminados (Papelera)</h1>

            <?php
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            ?>

            <nav class="navbar navbar-expand navbar-light bg-light mb-4">
                <div class="container-fluid d-flex justify-content-between align-items-center w-100">

                    <!-- Contenedor para los botones (Modificado: Botón para volver a la lista principal) -->
                    <div class="d-flex align-items-center">
                        <a href="<?= RUTA_URL ?>/usuarios" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i>
                            Volver a Usuarios</a>
                    </div>

                    <!-- Formulario de búsqueda (Modificado: Apunta a la ruta de la papelera) -->
                    <form action="<?= RUTA_URL ?>/usuarios/wastebasket" class="form-inline" role="search">
                        <input class="form-control form-control-sm mr-2" type="search" name="search"
                            value="{{ $search }}" placeholder="Buscar en papelera..." aria-label="Search">
                        <button class="btn btn-outline-primary btn-sm" type="submit">Buscar</button>
                    </form>

                </div>
            </nav>

            @include('includes.message')

            @if (count($users) > 0)
                <div class="table-responsive-sm">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Nombre de Usuario</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <!-- Se removió la columna de Roles por no ser relevante aquí -->
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contador = $users['from'] - 1;
                            @endphp
                            @foreach ($users['data'] as $user)
                                @php
                                    $contador++;
                                @endphp
                                <tr>
                                    <td>{{ $contador }}</td>
                                    @php
                                        $fotoNombre = !empty($user['avatar']) ? $user['avatar'] : 'no-disponible.png';
                                        $rutaFisica = dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/' . $fotoNombre;

                                        if (!file_exists($rutaFisica)) {
                                            $fotoNombre = 'no-disponible.png';
                                        }

                                        $avatarUrl = RUTA_URL . '/public/uploads/' . $fotoNombre;
                                    @endphp

                                    <td>
                                        <img src="{{ $avatarUrl }}" style="border-radius: 50%" width="45"
                                            alt="Avatar del Usuario">
                                    </td>
                                    <td>{{ $user['username'] }}</td>
                                    <td>{{ $user['nombre_completo'] }}</td>
                                    <td>{{ $user['email'] }}</td>

                                    <!-- Acciones de la Papelera: Restaurar y Eliminar Definitivamente -->
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Acciones Papelera">
                                            <!-- Botón Restaurar -->
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="confirmarRestauracion({{ $user['id'] }})"
                                                title="Restaurar Usuario">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </button>
                                            <!-- Botón Eliminar Permanentemente -->
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmarEliminacionDefinitiva({{ $user['id'] }})"
                                                title="Eliminar Permanentemente">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('includes.pagination')
            @else
                <div class="text-center">
                    La papelera está vacía.
                </div>
            @endif
        </div>
    </div>

    <!-- Scripts de Confirmación con SweetAlert2 -->
    <script>
        // 1. Función para Restaurar el Usuario
        function confirmarRestauracion(idUsuario) {
            Swal.fire({
                title: '¿Restaurar usuario?',
                text: "El usuario volverá a estar activo en la lista principal.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, restaurar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${base_url}/usuarios/${idUsuario}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('¡Restaurado!', 'El usuario ha sido activado.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error', 'No se pudo restaurar el usuario.', 'error');
                            }
                        });
                }
            });
        }

        // 2. Función para Eliminar Permanentemente de la Base de Datos
        function confirmarEliminacionDefinitiva(idUsuario) {
            Swal.fire({
                title: '¿Eliminar permanentemente?',
                text: "Esta acción no se puede deshacer de ninguna manera.",
                icon: 'warning', // 'danger' no es un icono válido en SweetAlert2, se usa 'warning' o 'error'
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar definitivamente',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${base_url}/usuarios/${idUsuario}/destroy`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('¡Eliminado!', 'El usuario ha sido borrado para siempre.', 'success')
                                    .then(() => location.reload());
                            } else {
                                // Muestra el mensaje específico enviado desde el controlador (Restricción de integridad)
                                Swal.fire({
                                    title: 'No se puede eliminar',
                                    text: data.mensaje,
                                    icon: 'error'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Ocurrió un fallo en la comunicación con el servidor.', 'error');
                        });
                }
            });
        }
    </script>
@endsection
