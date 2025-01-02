<?php

require_once __DIR__ . '/../../Models/Historial/Historial.php';

$fechaDesde = !empty($_GET['fecha_desde']) ? (new DateTimeImmutable($_GET['fecha_desde']))->format('Y-m-d 00:00:00') : '';
$fechaHasta = !empty($_GET['fecha_hasta']) ? (new DateTimeImmutable($_GET['fecha_hasta']))->format('Y-m-d 23:59:59') : '';

$filtros = [
    'usuario_id' => !empty($_GET['usuarios']) ? $_GET['usuarios'] : '',
    'fecha_desde' => $fechaDesde,
    'fecha_hasta' => $fechaHasta
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $historialUsuarios = Historial::getHistorialUsuarios($filtros, $order);
    $historialUsuariosArray = [];

    foreach ($historialUsuarios as $historialUsuario) {
        $historialUsuariosArray[] = $historialUsuario->toArray();
    }

    echo json_encode([
        'ok' => true,
        "recordsTotal" => $limit,
        "recordsFiltered" => count($historialUsuarios),
        'data' => array_slice($historialUsuariosArray, $skip, $limit)
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}
