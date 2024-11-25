<?php

require_once __DIR__ . '/../../Models/Categorias/Categoria.php';

$filtros = [
    'nombre' => !empty($_GET['nombre']) ? "%{$_GET['nombre']}%" : '',
    'descripcion' => !empty($_GET['descripcion']) ? "%{$_GET['descripcion']}%" : '',
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $categorias = Categoria::getCategorias($filtros, $order);
    $categoriasArray = [];

    foreach ($categorias as $categoria) {
        $categoriasArray[] = $categoria->toArray();
    }

    echo json_encode([
        'ok' => true,
        "recordsTotal" => $limit,
        "recordsFiltered" => count($categorias),
        'data' => array_slice($categoriasArray, $skip, $limit)
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}