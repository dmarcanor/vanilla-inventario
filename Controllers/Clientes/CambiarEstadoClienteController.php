<?php

require_once __DIR__ . '/../../Models/Clientes/Cliente.php';
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
    http_response_code(200);
    Cliente::cambiarEstado($data['id'], $data['usuarioSesion']);

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Cliente actualizado correctamente.'
    ]);
    exit();
} catch (\Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage()
    ]);
}