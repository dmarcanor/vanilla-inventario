<?php

require_once __DIR__ . '/../../Models/Materiales/Material.php';

$filtros = [
    'nombre' => !empty($_GET['nombre']) ? "%{$_GET['nombre']}%" : '',
    'descripcion' => !empty($_GET['descripcion']) ? "%{$_GET['descripcion']}%" : '',
    'marca' => !empty($_GET['marca']) ? "%{$_GET['marca']}%" : '',
    'usuario_id' => !empty($_GET['usuario_id']) ? $_GET['usuario_id'] : '',
    'categoria_id' => !empty($_GET['categoria_id']) ? $_GET['categoria_id'] : '',
    'unidad' => !empty($_GET['unidad']) ? $_GET['unidad'] : '',
    'peso_desde' => !empty($_GET['peso_desde']) ? $_GET['peso_desde'] : 0,
    'peso_hasta' => !empty($_GET['peso_hasta']) ? $_GET['peso_hasta'] : 0,
    'precio_desde' => !empty($_GET['precio_desde']) ? $_GET['precio_desde'] : 0,
    'precio_hasta' => !empty($_GET['precio_hasta']) ? $_GET['precio_hasta'] : 0,
    'stock_desde' => !empty($_GET['stock_desde']) ? $_GET['stock_desde'] : 0,
    'stock_hasta' => !empty($_GET['stock_hasta']) ? $_GET['stock_hasta'] : 0,
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $materiales = Material::getMateriales($filtros, $order);
    $materialesArray = [];

    foreach ($materiales as $material) {
        $materialesArray[] = $material->toArray();
    }

    echo json_encode([
        'ok' => true,
        "recordsTotal" => $limit,
        "recordsFiltered" => count($materiales),
        'data' => array_slice($materialesArray, $skip, $limit)
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => [],
        'mensaje' => $exception->getMessage()
    ]);
}