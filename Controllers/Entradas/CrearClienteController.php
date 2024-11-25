<?php

declare(strict_types=1);

require_once __DIR__ . '/../Modelos/Cliente.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(201);

    Cliente::crear(
        $data['nombre'],
        $data['apellido'],
        $data['tipo_identificacion'],
        $data['numero_identificacion'],
        $data['telefono'],
        $data['direccion'],
        $data['estado']
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