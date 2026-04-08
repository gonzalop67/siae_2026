<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= NOMBRESITIO . $tituloPagina ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media = 'all'" />
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= RUTA_URL ?>assets/adminlte4/css/adminlte.css">
    <style>
        body {
            background-image: url('<?= RUTA_URL ?>assets/img/loginFont.jpg');
            background-size: cover;
        }
    </style>
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <a href="<?= $institucion->url ?>"
                    class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"><b>S. I. A. E.</b></h1>
                </a>
            </div>

            <div class="card-body login-card-body">
                <p class="login-box-msg">Introduzca sus datos de ingreso</p>

                <form id="form-login">
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input id="email" name="email" type="email" class="form-control"
                                value="" placeholder="" autocomplete="username" />
                            <label for="email">Correo electrónico</label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>

                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input id="password" name="password" type="password" class="form-control" placeholder=""
                                autocomplete="current-password" />
                            <label for="password">Contraseña</label>
                        </div>
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>

                    <!-- begin::Row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">Ingresar</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- end::Row -->
                </form>
            </div>

            <!-- /.login-card-body -->
            <div class="card-footer text-center">
                <?= $institucion->nombre ?>
            </div>
        </div>
    </div>

    <!-- jQuery 3 -->
    <script src="<?php echo RUTA_URL ?>assets/js/jquery/jquery.min.js"></script>

    <script>
        const base_url = "<?php echo RUTA_URL; ?>";
    $('#form-login').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo RUTA_URL ?>auth/login', // Ruta amigable al método del controlador
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.trim() === 'ok') {
                    window.location.href = '/dashboard';
                } else {
                    Swal.fire('Error', 'Usuario o contraseña incorrectos', 'error');
                }
            }
        });
    });
    </script>
</body>

</html>