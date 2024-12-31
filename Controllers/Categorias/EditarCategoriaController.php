<?php

require_once __DIR__ . '/../../Models/Categorias/Categoria.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(200);
    Categoria::editar(
        $data['id'],
        $data['nombre'],
        $data['descripcion'],
        $data['estado'],
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