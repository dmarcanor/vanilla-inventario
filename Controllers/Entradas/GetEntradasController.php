<?php

require_once __DIR__ . '/../../Models/Entradas/Entrada.php';
require_once '../../helpers.php';

try {
    verificarSesion();
} catch (\Exception $exception) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['mensaje' => 'Sesión expirada']);
    exit();
}

$fechaDesde = !empty($_GET['fecha_desde']) ? (new DateTimeImmutable($_GET['fecha_desde']))->format('Y-m-d 00:00:00') : '';
$fechaHasta = !empty($_GET['fecha_hasta']) ? (new DateTimeImmutable($_GET['fecha_hasta']))->format('Y-m-d 23:59:59') : '';

$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : '',
    'numero_entrada' => !empty($_GET['numeroEntrada']) ? "%{$_GET['numeroEntrada']}%" : '',
    'material' => !empty($_GET['material']) ? $_GET['material'] : '',
    'fecha_desde' => $fechaDesde,
    'fecha_hasta' => $fechaHasta,
    'categoria' => !empty($_GET['categoria']) ? $_GET['categoria'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['limit']) ? (int)$_GET['limit'] : 0;
$length = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'DESC';
$ordenCampo = obtenerCampoOrdenEnTabla();


if ($ordenCampo === 'id') {
    $ordenCampo = 'entradas.id';
}

if ($ordenCampo === 'numeroEntrada') {
    $ordenCampo = 'numero_entrada';
}

if ($ordenCampo === 'fechaCreacionSinHora') {
    $ordenCampo = 'fecha_creacion';
}

try {
    $entradas = Entrada::getEntradas($filtros, $order, $limit, $ordenCampo);
    $entradasArray = [];

    foreach ($entradas as $entrada) {
        $entradasArray[] = $entrada->toArray();
    }

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