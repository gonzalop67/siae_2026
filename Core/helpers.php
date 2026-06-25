<?php

function show(mixed $stuff)
{
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

//Para redireccionar página
function redireccionar(string $pagina): void
{
    header('Location: ' . RUTA_URL . $pagina);
}

function tiene_permiso(string $slug) {
    if (!isset($_SESSION['permisos'])) return false;
    return in_array($slug, $_SESSION['permisos']);
}