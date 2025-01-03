<?php

require_once __DIR__ . '/../../Models/Materiales/Material.php';

$data = json_decode(file_get_contents('php://input'), true);

try {
    http_response_code(200);
    Material::editar(
        $data['id'],
        $data['codigo'],
        $data['nombre'],
        $data['descripcion'],
        $data['marca'],
        $data['categoria_id'],
        $data['unidad'],
        $data['presentacion'],
        $data['estado'],
        $data['precio'],
        $data['stockMinimo'],
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