<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Entradas/Entrada.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(201);

    Entrada::crear(
        $data['numeroEntrada'],
        $data['observacion'],
        $data['usuario_id'],
        $data['lineas'],
        $data['usuarioSesion']
    );

    echo json_encode([
        'ok' => true
    ]);
    exit();
} catch (\Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage()
    ]);
}