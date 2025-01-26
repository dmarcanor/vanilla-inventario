<?php

require_once __DIR__ . '/../../Models/Marcas/Marca.php';
require_once '../../helpers.php';

try {
    verificarSesion();
} catch (\Exception $exception) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['mensaje' => 'Sesión expirada']);
    exit();
}

try {
    $marcas = Marca::getMarcas();
    $marcasArray = [];

    foreach ($marcas as $marca) {
        $marcasArray[] = $marca->toArray();
    }

    echo json_encode([
        'ok' => true,
        'data' => $marcasArray
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}