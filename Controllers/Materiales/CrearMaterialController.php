<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Materiales/Material.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(201);

    Material::crear(
        $data['nombre'],
        $data['descripcion'],
        $data['marca'],
        $data['usuario_id'],
        $data['categoria_id'],
        $data['unidad'],
        $data['peso'],
        $data['precio'],
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