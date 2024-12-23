<?php

require_once __DIR__ . '/../../Models/Salidas/Salida.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(200);
    Salida::eliminar($data['id']);

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Salida eliminada correctamente.'
    ]);
    exit();
} catch (\Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage()
    ]);
}