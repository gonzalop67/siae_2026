<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= NOMBRESITIO . "| Login" ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media = 'all'" />
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= baseurl() ?>/assets/adminlte4/css/adminlte.css">
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <a href="#"
                    class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"><b>S. I. A. E.</b></h1>
                </a>
            </div>

            <div class="card-body login-card-body">
                <p class="login-box-msg">Introduzca sus datos de ingreso</p>

                <div id="mensaje">
                    <!-- Aqui van los mensajes de error -->
                </div>

                <form id="form-login" autocomplete="off">
                    <div class="form-floating mb-1">
                        <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Ingrese usuario" autocomplete="username">
                        <label for="usuario"><i class="bi bi-person-fill-lock"></i> Usuario</label>
                        <p id="error-usuario" class="invalid-feedback"></p>
                    </div>

                    <div class="form-floating mb-1">
                        <input class="form-control" id="clave" name="clave" type="password" placeholder="Ingrese contraseña" autocomplete="current-password">
                        <label for="clave"><i class="bi bi-key-fill"></i> Contraseña</label>
                        <p id="error-clave" class="invalid-feedback"></p>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" name="perfil" id="perfil">
                            <option value="">Seleccione...</option>
                            <!-- Aquí van los perfiles -->
                        </select>
                        <label for="perfil"><i class="bi bi-person-fill-gear"></i></i> Perfil</label>
                        <p id="error-perfil" class="invalid-feedback"></p>
                    </div>

                    <!-- begin::Row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Ingresar</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- end::Row -->
                </form>
            </div>
            <!-- /.login-card-body -->

            <div class="card-footer text-center">
                <?= "UNIDAD EDUCATIVA SALAMANCA" ?>
            </div>
        </div>
    </div>
</body>

</html>