<?php
// 1. IMPORTANTE: Primero cargas el archivo físicamente
require_once 'Core/MiniBlade.php'; 

use Core\MiniBlade;

$cachePath = __DIR__ . '/cache';
$blade = new MiniBlade(__DIR__ . '/views', $cachePath);

$cantidad = $blade->clearCache();

echo "Caché optimizada\n";
echo "Se han eliminado $cantidad archivos temporales.";
