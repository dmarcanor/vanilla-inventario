<?php

declare(strict_types=1);


require_once __DIR__ . '/../../Models/Marcas/Marca.php';
require_once '../../helpers.php';

try {
    verificarSesion();
} catch (\Exception $exception) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['mensaje' => 'Sesión expirada']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(201);

    $marca = Marca::crear(
        $data['nombre'],
        $data['usuarioSesion']
    );

    echo json_encode([
        'ok' => true,
        'marca' => $marca->toArray()
    ]);
    exit();
} catch (\Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage()
    ]);
}