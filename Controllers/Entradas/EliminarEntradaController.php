<?php

require_once __DIR__ . '/../../Models/Entradas/Entrada.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(200);
    Entrada::eliminar($data['id']);

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Entrada eliminada correctamente.'
    ]);
    exit();
} catch (\Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage()
    ]);
}