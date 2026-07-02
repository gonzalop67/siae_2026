@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Lista de Usuarios</h1>

            <?php $search = isset($_GET['search']) ? $_GET['search'] : ''; ?>

            <nav class="navbar navbar-expand navbar-light bg-light mb-4">
                <div class="container-fluid d-flex justify-content-between align-items-center w-100">

                    <!-- Contenedor para los botones (Alineados a la izquierda) -->
                    <div class="d-flex align-items-center">
                        <a href="<?= RUTA_URL ?>/usuarios/create" class="btn btn-primary btn-sm mr-1">
                            <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
                        </a>
                        <a href="<?= RUTA_URL ?>/usuarios/wastebasket" class="btn btn-danger btn-sm">
                            <i class="fa-solid fa-trash"></i> Papelera
                        </a>
                    </div>

                    <!-- Formulario de búsqueda (Alineado a la derecha) -->
                    <form action="<?= RUTA_URL ?>/usuarios" class="form-inline" role="search">
                        <!-- ✔ CORRECCIÓN: Se cambió {{ $search }} por PHP nativo seguro -->
                        <input class="form-control form-control-sm mr-2" type="search" name="search"
                            value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" placeholder="Usuario a buscar..." aria-label="Search">
                        <button class="btn btn-outline-primary btn-sm" type="submit">Buscar</button>
                    </form>

                </div>
            </nav>

            @if (count($usuarios['data']) > 0)
                <div class="table-responsive-sm">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Nombre de Usuario</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th class="text-center">Roles</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contador = $usuarios['from'] - 1;
                            @endphp
                            @foreach ($usuarios['data'] as $usuario)
                                @php
                                    $contador++;
                                @endphp
                                <tr>
                                    <td>{{ $contador }}</td>
                                    @php
                                        $fotoNombre = !empty($usuario['avatar']) ? $usuario['avatar'] : 'no-disponible.png';
                                        $rutaFisica = dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/' . $fotoNombre;

                                        if (!file_exists($rutaFisica)) {
                                            $fotoNombre = 'no-disponible.png';
                                        }

                                        $avatarUrl = RUTA_URL . '/public/uploads/' . $fotoNombre;
                                    @endphp

                                    <td>
                                        <img src="{{ $avatarUrl }}" style="border-radius: 50%" width="45" alt="Avatar">
                                    </td>
                                    <td>{{ $usuario['username'] }}</td>

                                    <!-- ✔ VERIFICADO: Extrae de forma dinámica el campo nombre_completo de tu matriz exitosa -->
                                    <td>{{ !empty($usuario['nombre_completo']) ? $usuario['nombre_completo'] : 'Sin asignar' }}</td>

                                    <td>{{ $usuario['email'] }}</td>
                                    <td class="text-center">
                                        <a href="{{ RUTA_URL }}/usuarios/{{ $usuario['id'] }}/roles" class="btn btn-sm btn-primary" title="Roles">
                                            <i class="fa-solid fa-user-gear"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ RUTA_URL }}/usuarios/{{ $usuario['id'] }}/edit" class="btn btn-success btn-sm" title="Editar">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion({{ $usuario['id'] }})" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
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
                    Aún no se han registrado Usuarios o no coinciden con la búsqueda.
                </div>
            @endif

        </div>
    </div>
@endsection
