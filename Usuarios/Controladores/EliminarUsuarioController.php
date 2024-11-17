<?php

require_once __DIR__ . '/../Modelos/Usuario.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(200);
    Usuario::eliminar($data['id']);

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Usuario eliminado correctamente.'
    ]);
    exit();
} catch (\Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage()
    ]);
}