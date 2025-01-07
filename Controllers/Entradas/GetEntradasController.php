<?php

require_once __DIR__ . '/../../Models/Entradas/Entrada.php';

$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : '',
    'numero_entrada' => !empty($_GET['numeroEntrada']) ? "%{$_GET['numeroEntrada']}%" : '',
    'material' => !empty($_GET['material']) ? $_GET['material'] : '',
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['limit']) ? (int)$_GET['limit'] : 0;
$length = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['orden']) ? $_GET['orden'] : 'ASC';

try {
    $entradas = Entrada::getEntradas($filtros, $order, $limit);
    $entradasArray = [];

    foreach ($entradas as $entrada) {
        $entradasArray[] = $entrada->toArray();
    }
//var_dump($limit, $skip, $order, $entradasArray);
//    exit();
    echo json_encode([
        'ok' => true,
        "recordsTotal" => $length,
        "recordsFiltered" => count($entradas),
        'data' => array_slice($entradasArray, $skip, $length)
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage(),
        'data' => []
    ]);
}