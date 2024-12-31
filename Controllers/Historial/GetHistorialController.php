<?php

require_once __DIR__ . '/../../Models/Historial/Historial.php';

$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : '',
    'usuarios' => !empty($_GET['usuarios']) ? $_GET['usuarios'] : '',
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : ''
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
