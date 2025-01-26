<?php

require_once __DIR__ . '/../../Models/Clientes/Cliente.php';
require_once '../../helpers.php';

try {
    verificarSesion();
} catch (\Exception $exception) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['mensaje' => 'Sesión expirada']);
    exit();
}

$filtros = [
    'nombre' => !empty($_GET['nombre']) ? "%{$_GET['nombre']}%" : '',
    'apellido' => !empty($_GET['apellido']) ? "%{$_GET['apellido']}%" : '',
    'tipo_identificacion' => !empty($_GET['tipo_identificacion']) ? $_GET['tipo_identificacion'] : '',
    'numero_identificacion' => !empty($_GET['numero_identificacion']) ? "%{$_GET['numero_identificacion']}%" : '',
    'telefono' => !empty($_GET['telefono']) ? "%{$_GET['telefono']}%" : '',
    'direccion' => !empty($_GET['direccion']) ? "%{$_GET['direccion']}%" : '',
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';
$ordenCampo = obtenerCampoOrdenEnTabla();


if ($ordenCampo === 'tipoIdentificacion') {
    $ordenCampo = 'tipo_identificacion';
}

if ($ordenCampo === 'numeroIdentificacion') {
    $ordenCampo = 'numero_identificacion';
}

try {
    $clientes = Cliente::getClientes($filtros, $order, $ordenCampo);
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