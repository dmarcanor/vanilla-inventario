<?php

require_once __DIR__ . '/../../Models/Materiales/Material.php';

$fechaDesde = !empty($_GET['fecha_desde']) ? (new DateTimeImmutable($_GET['fecha_desde']))->format('Y-m-d 00:00:00') : '';
$fechaHasta = !empty($_GET['fecha_hasta']) ? (new DateTimeImmutable($_GET['fecha_hasta']))->format('Y-m-d 23:59:59') : '';

$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : 0,
    'codigo' => !empty($_GET['codigo']) ? "%{$_GET['codigo']}%" : '',
    'nombre' => !empty($_GET['nombre']) ? "%{$_GET['nombre']}%" : '',
    'descripcion' => !empty($_GET['descripcion']) ? "%{$_GET['descripcion']}%" : '',
    'presentacion' => !empty($_GET['presentacion']) ? "%{$_GET['presentacion']}%" : '',
    'marca' => !empty($_GET['marca']) ? "%{$_GET['marca']}%" : '',
    'categoria_id' => !empty($_GET['categoria_id']) ? $_GET['categoria_id'] : '',
    'unidad' => !empty($_GET['unidad']) ? $_GET['unidad'] : '',
    'precio_desde' => !empty($_GET['precio_desde']) ? $_GET['precio_desde'] : 0,
    'precio_hasta' => !empty($_GET['precio_hasta']) ? $_GET['precio_hasta'] : 0,
    'stock_desde' => !empty($_GET['stock_desde']) ? $_GET['stock_desde'] : 0,
    'stock_hasta' => !empty($_GET['stock_hasta']) ? $_GET['stock_hasta'] : 0,
    'fecha_desde' => $fechaDesde,
    'fecha_hasta' => $fechaHasta,
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : '',
    'stock_minimo' => !empty($_GET['stock_minimo']) ? $_GET['stock_minimo'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['limit']) ? (int)$_GET['limit'] : 0;
$length = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['orden']) ? $_GET['orden'] : 'ASC';
$ordenCampo = !empty($_GET['ordenCampo']) ? $_GET['ordenCampo'] : 'id';

try {
    $materiales = Material::getMateriales($filtros, $order, $ordenCampo, $limit);
    $materialesArray = [];

    foreach ($materiales as $material) {
        $materialesArray[] = $material->toArray();
    }

    echo json_encode([
        'ok' => true,
        "recordsTotal" => $limit,
        "recordsFiltered" => count($materiales),
        'data' => array_slice($materialesArray, $skip, $length)
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => [],
        'mensaje' => $exception->getMessage()
    ]);
}