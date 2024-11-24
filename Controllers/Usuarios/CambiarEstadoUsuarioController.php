<?php

require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(200);
    Usuario::cambiarEstado($data['id']);

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Usuario actualizado correctamente.'
    ]);
    exit();
} catch (\Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage()
    ]);
}