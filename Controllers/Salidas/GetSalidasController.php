<?php

require_once __DIR__ . '/../../Models/Salidas/Salida.php';
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
    'observacion' => !empty($_GET['observacion']) ? "%{$_GET['observacion']}%" : '',
    'usuario_id' => !empty($_GET['usuarioId']) ? $_GET['usuarioId'] : '',
    'cliente_id' => !empty($_GET['clienteId']) ? $_GET['clienteId'] : '',
    'fecha_desde' => $fechaDesde,
    'fecha_hasta' => $fechaHasta,
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : '',
    'material' => !empty($_GET['material']) ? $_GET['material'] : '',
    'categoria' => !empty($_GET['categoria']) ? $_GET['categoria'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['limit']) ? (int)$_GET['limit'] : 0;
$length = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'DESC';
$ordenCampo = obtenerCampoOrdenEnTabla();


if ($ordenCampo === 'clienteFullNombre') {
    $ordenCampo = 'cliente_id';
}

if ($ordenCampo === 'usuarioFullNombre') {
    $ordenCampo = 'usuario_id';
}

if ($ordenCampo === 'fechaCreacion') {
    $ordenCampo = 'fecha_creacion';
}

try {
    $salidas = Salida::getSalidas($filtros, $order, $limit, $ordenCampo);
    $salidasArray = [];

    foreach ($salidas as $salida) {
        $salidasArray[] = $salida->toArray();
    }

    echo json_encode([
        'ok' => true,
        "recordsTotal" => $length,
        "recordsFiltered" => count($salidas),
        'data' => array_slice($salidasArray, $skip, $length)
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => [],
        'mensaje' => $exception->getMessage()
    ]);
}