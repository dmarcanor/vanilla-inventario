<?php

require_once __DIR__ . '/../../Models/Salidas/Salida.php';

$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : '',
    'observacion' => !empty($_GET['observacion']) ? "%{$_GET['observacion']}%" : '',
    'usuario_id' => !empty($_GET['usuarioId']) ? $_GET['usuarioId'] : '',
    'cliente_id' => !empty($_GET['clienteId']) ? $_GET['clienteId'] : '',
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $salidas = Salida::getSalidas($filtros, $order);
    $salidasArray = [];

    foreach ($salidas as $salida) {
        $salidasArray[] = $salida->toArray();
    }

    echo json_encode([
        'ok' => true,
        "recordsTotal" => $limit,
        "recordsFiltered" => count($salidas),
        'data' => array_slice($salidasArray, $skip, $limit)
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}