<?php

require_once __DIR__ . '/../../Models/Entradas/Entrada.php';

$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : '',
    'numero_entrada' => !empty($_GET['numeroEntrada']) ? "%{$_GET['numeroEntrada']}%" : '',
    'materiol' => !empty($_GET['material']) ? $_GET['material'] : '',
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $clientes = Entrada::getEntradas($filtros, $order);
    $clientesArray = [];

    foreach ($clientes as $cliente) {
        $clientesArray[] = $cliente->toArray();
    }

    echo json_encode([
        'ok' => true,
        "recordsTotal" => $limit,
        "recordsFiltered" => count($clientes),
        'data' => array_slice($clientesArray, $skip, $limit)
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}