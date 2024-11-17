<?php

require_once __DIR__ . '/../Modelos/Cliente.php';

$cliente = Cliente::getCliente($_GET['id'])->toArray();

if (empty($cliente)) {
    echo json_encode([
        'ok' => false,
        'cliente' => []
    ]);
    exit();
}

echo json_encode([
    'ok' => true,
    'cliente' => $cliente
]);