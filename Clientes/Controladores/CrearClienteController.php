<?php

declare(strict_types=1);

require_once __DIR__ . '/../../BD/ConexionBD.php';

$data = json_decode(file_get_contents('php://input'), true);

$baseDatos = (new ConexionBD())->getConexion();

$consultaCrearCliente = $baseDatos->prepare("
    INSERT INTO clientes (nombre, tipo_identificacion, numero_identificacion, estado) VALUES 
    (?, ?, ?, ?)
");

try {
    http_response_code(201);
    $consultaCrearCliente->execute([
        $data['nombre'],
        $data['tipo_identificacion'],
        $data['numero_identificacion'],
        $data['estado']
    ]);

    echo json_encode([
        'ok' => true,
        'code' => 201
    ]);
    exit();
} catch (\Exception $exception) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'code' => 500,
        'mensaje' => $exception->getMessage()
    ]);
}