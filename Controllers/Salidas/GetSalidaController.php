<?php

require_once __DIR__ . '/../../Models/Salidas/Salida.php';

try {
    $salida = Salida::getSalida($_GET['id']);

    echo json_encode([
        'ok' => true,
        'salida' => $salida->toArray()
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