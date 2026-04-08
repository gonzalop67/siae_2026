<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAE Web</title>
    <!-- Bootstrap CSS -->
    <link href="<?= RUTA_URL; ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="<?= RUTA_URL; ?>assets/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="stylesheet" href="<?= RUTA_URL; ?>assets/css/main.css">
</head>
<body>
    <!-- ==================================== -->
    <!-- ENCABEZADO -->
    <!-- ==================================== -->
    <header class="container-fluid bg-primary d-flex justify-content-center">
        <p class="text-light mb-0 p-2 fs-6">Contáctanos 593-022-256311</p>
    </header>

    <nav class="navbar navbar-expand-lg bg-body-tertiary p-3" id="menu">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <span class="text-primary fs-5 fw-bold"><?= $nombreInstitucion ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Inscripciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                </ul>
                <ul class="nav justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= RUTA_URL . 'auth' ?>">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Regístrate</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ==================================== -->
    <!-- SLIDER DE IMAGENES -->
    <!-- ==================================== -->

    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="20000">
                <img src="./assets/img/slide1.jpg" class="d-block w-100" alt="slide1">
            </div>

            <div class="carousel-item" data-bs-interval="3000">
                <img src="./assets/img/slide2.jpg" class="d-block w-100" alt="slide2">
            </div>

            <div class="carousel-item" data-bs-interval="3000">
                <img src="./assets/img/slide3.jpg" class="d-block w-100" alt="slide3">
            </div>

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- ==================================== -->
    <!-- INTRO -->
    <!-- ==================================== -->

    <section class="w-50 mx-auto text-center pt-5" id="intro">
        <h1 class="p-3 fs-2 border-top border-3">Una unidad educativa dedicada a formar<br>
            <span class="text-primary">Líderes del Futuro</span>
        </h1>
        <p class="p-3 fs-4"><span class="text-primary"><?= $nombreInstitucion ?></span> te brinda la oportunidad de obtener
            tu título de bachiller y cumplir tu meta de terminar tus estudios secundarios.</p>
    </section>

    <!-- ==================================== -->
    <!-- SERVICIOS -->
    <!-- ==================================== -->

    <section class="container-fluid">
        <div class="row w-75 mx-auto my-5 servicio-fila">
            <div class="col-lg-6 col-md-12 col-sm-12 d-flex my-5 icono-wrap">
                <img src="./assets/img/secretary.png" alt="desarrollo" width="180" height="160">
                <div>
                    <h3 class="fds-5 mt-4 px-4 pb-1">Matrículas</h3>
                    <p class="px-4">A través del perfil de Secretaría, el personal de este departamento puede matricular estudiantes nuevos y antiguos en el SIAE.</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 d-flex my-5 icono-wrap">
                <img src="./assets/img/students.png" alt="concepto" width="180" height="160">
                <div>
                    <h3 class="fds-5 mt-4 px-4 pb-1">Estudiantes</h3>
                    <p class="px-4">Cada estudiante puede consultar sus calificaciones que fueron registradas por los docentes en el SIAE de forma fácil y segura.</p>
                </div>
            </div>
        </div>
    </section>

    <!--========================================================== -->
    <!-- SECCION ACERCA DE NOSOTROS-->
    <!--========================================================== -->

    <section>
        <div class="container w-75 m-auto text-center" id="equipo">
            <h1 class="mb-5 fs-2">Equipo pequeño con <span class="text-primary"> Grandes Resultados</span>.</h1>
            <p class="fs-4 ">Contamos con docentes calificados en sus áreas del conocimiento. Si te sientes listo
                para este reto, te esperamos para que te unas a nuestra institución.</p>
        </div>
    </section>

    <!--========================================================== -->
    <!--FOOTER-->
    <!--========================================================== -->

    <footer class="w-100 d-flex align-items-center justify-content-center flex-wrap">
        <p class="fs-5 px-3 pt-3"><?= $nombreInstitucion ?> &copy; Todos Los Derechos Reservados <?php echo date("  Y"); ?></p>
        <div id="iconos">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
        </div>
    </footer>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="<?= RUTA_URL; ?>assets/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>