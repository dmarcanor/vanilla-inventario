<?php

require_once __DIR__ . '/../../Models/Entradas/Entrada.php';

try {
    $entrada = Entrada::getEntrada($_GET['id']);

    echo json_encode([
        'ok' => true,
        'entrada' => $entrada->toArray()
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