<?php

require_once __DIR__ . '/../../Models/Clientes/Cliente.php';

try {
    $cliente = Cliente::getCliente($_GET['id']);

    echo json_encode([
        'ok' => true,
        'cliente' => $cliente->toArray()
    ]);
    exit();
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage(),
        'cliente' => []
    ]);
    exit();
}