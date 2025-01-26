<?php

require_once __DIR__ . '/../../Models/Materiales/Material.php';
require_once '../../helpers.php';

try {
    verificarSesion();
} catch (\Exception $exception) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['mensaje' => 'Sesión expirada']);
    exit();
}

try {
    $material = Material::getMaterial($_GET['id']);

    echo json_encode([
        'ok' => true,
        'material' => $material->toArray()
    ]);
    exit();
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage(),
        'material' => []
    ]);
    exit();
}