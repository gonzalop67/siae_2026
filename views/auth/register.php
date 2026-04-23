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
        <div class="card card-outline card-danger">
            <div class="card-header">
                <a href="<?= $institucion->url ?>"
                    class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"><b>S. I. A. E.</b></h1>
                </a>
            </div>

            <div class="card-body login-card-body">
                <p class="login-box-msg">Introduzca sus datos de registro</p>

                <div id="mensaje">
                    <!-- Aqui van los mensajes de error o de éxito -->
                </div>

                <form id="form-register" autocomplete="off">
                    <div class="form-floating mb-1">
                        <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Ingrese usuario">
                        <label for="usuario"><i class="bi bi-person-fill-lock"></i> Usuario</label>
                        <p id="error-usuario" class="invalid-feedback"></p>
                    </div>

                    <div class="form-floating mb-1">
                        <input class="form-control" id="email" name="email" type="email" placeholder="Ingrese email">
                        <label for="email"><i class="bi bi-envelope-at-fill"></i> Email</label>
                        <p id="error-email" class="invalid-feedback"></p>
                    </div>

                    <div class="form-floating mb-1">
                        <input class="form-control" id="clave" name="clave" type="password" placeholder="Ingrese contraseña">
                        <label for="clave"><i class="bi bi-key-fill"></i> Contraseña</label>
                        <p id="error-clave" class="invalid-feedback"></p>
                    </div>

                    <div class="form-floating mb-1">
                        <input class="form-control" id="confirmar_clave" name="confirmar_clave" type="password" placeholder="Ingrese confirmación de contraseña">
                        <label for="confirmar_clave"><i class="bi bi-key-fill"></i> Confirmar Contraseña</label>
                        <p id="error-confirmar_clave" class="invalid-feedback"></p>
                    </div>

                    <!-- begin::Row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">Registrar</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- end::Row -->
                </form>

                <div id="img_loader" class="text-center mt-2">
                    <!-- Aqui va el gif animado que representa el loader -->
                </div>
            </div>

            <!-- /.login-card-body -->
            <div class="card-footer text-center">
                <?= $institucion->nombre ?>
                <p class="mb-0">
                    <a href="<?= RUTA_URL ?>auth">Ya tengo una cuenta</a>
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery 3 -->
    <script src="<?php echo RUTA_URL ?>assets/js/jquery/jquery.min.js"></script>

    <script>
        const base_url = "<?php echo RUTA_URL; ?>";
        const usuario = document.getElementById("usuario");
        const email = document.getElementById("email");
        const clave = document.getElementById("clave");
        const confirmar_clave = document.getElementById("confirmar_clave");

        $('#form-register').submit(function(e) {
            e.preventDefault();
            if (usuario.value.trim() == "" || email.value.trim() == "" || clave.value.trim() == "" || confirmar_clave.value.trim() == "") {
                if (usuario.value.trim() == "") {
                    usuario.classList.add("is-invalid");
                    document.getElementById("error-usuario").innerHTML = "El campo Usuario es obligatorio.";
                } else {
                    usuario.classList.remove("is-invalid");
                    document.getElementById("error-usuario").innerHTML = "";
                }

                if (email.value.trim() == "") {
                    email.classList.add("is-invalid");
                    document.getElementById("error-email").innerHTML = "El campo Email es obligatorio.";
                } else {
                    email.classList.remove("is-invalid");
                    document.getElementById("error-email").innerHTML = "";
                }

                if (clave.value.trim() == "") {
                    clave.classList.add("is-invalid");
                    document.getElementById("error-clave").innerHTML = "El campo Contraseña es obligatorio.";
                } else {
                    clave.classList.remove("is-invalid");
                    document.getElementById("error-clave").innerHTML = "";
                }

                if (confirmar_clave.value.trim() == "") {
                    confirmar_clave.classList.add("is-invalid");
                    document.getElementById("error-confirmar_clave").innerHTML = "El campo Confirmar Contraseña es obligatorio.";
                } else {
                    confirmar_clave.classList.remove("is-invalid");
                    document.getElementById("error-confirmar_clave").innerHTML = "";
                }
            } else {
                // Eliminar todos los mensajes de error
                usuario.classList.remove("is-invalid");
                document.getElementById("error-usuario").innerHTML = "";
                email.classList.remove("is-invalid");
                document.getElementById("error-email").innerHTML = "";
                clave.classList.remove("is-invalid");
                document.getElementById("error-clave").innerHTML = "";
                confirmar_clave.classList.remove("is-invalid");
                document.getElementById("error-confirmar_clave").innerHTML = "";
                // Confirmar si la clave y su confirmación coinciden
                if (clave.value.trim() !== confirmar_clave.value.trim()) {
                    var mensaje = `
                    <div class='alert alert-danger'>
                    La Contraseña y Confirmación no coinciden.
                    </div>
                    `;
                    document.getElementById("mensaje").innerHTML = mensaje;
                } else {
                    document.getElementById("img_loader").innerHTML = "<img src='"+base_url+"assets/img/ajax-loader5.GIF' alt='image loader'>";
                }
            }
            /*$.ajax({
                url: '<?php echo RUTA_URL ?>auth/login', // Ruta amigable al método del controlador
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    console.log(res)
                    /*if (res.trim() === 'ok') {
                        window.location.href = '/dashboard';
                    } else {
                        Swal.fire('Error', 'Usuario o contraseña incorrectos', 'error');
                    }
                }
            });*/
        });
    </script>
</body>

</html>