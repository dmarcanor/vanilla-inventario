<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Salidas/Salida.php';
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

    Salida::crear(
        $data['clienteId'],
        $data['observacion'],
        $data['usuario_id'],
        $data['lineas'],
        $data['usuarioSesion']
    );

    $respuesta = ['ok' => true];

    // alerta de superar stock minimo
    if (isset($_POST['mensaje'])) {
        $respuesta['mensaje'] = $_POST['mensaje'];
    }

    echo json_encode($respuesta);
    exit();
} catch (\Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage()
    ]);
}