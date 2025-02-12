<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Materiales/Material.php';
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
    http_response_code(201);

    Material::crear(
        $data['codigo'],
        $data['nombre'],
        $data['descripcion'],
        $data['marca'],
        $data['categoria_id'],
        $data['unidad'],
        $data['presentacion'],
        $data['estado'],
        $data['precioDetal'],
        $data['precioMayor'],
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