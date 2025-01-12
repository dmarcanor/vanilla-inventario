<?php

require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';

$filtros = [
    'nombre' => !empty($_GET['nombre']) ? "%{$_GET['nombre']}%" : '',
    'apellido' => !empty($_GET['apellido']) ? "%{$_GET['apellido']}%" : '',
    'cedula' => !empty($_GET['cedula']) ? "%{$_GET['cedula']}%" : '',
    'telefono' => !empty($_GET['telefono']) ? "%{$_GET['telefono']}%" : '',
    'direccion' => !empty($_GET['direccion']) ? "%{$_GET['direccion']}%" : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : '',
    'rol' => !empty($_GET['rol']) ? $_GET['rol'] : '',
    'nombre_usuario' => !empty($_GET['nombre_usuario']) ? "%{$_GET['nombre_usuario']}%" : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $usuarios = Usuario::getUsuarios($filtros, $order);
    $usuariosArray = [];

    foreach ($usuarios as $usuario) {
        $usuariosArray[] = $usuario->toArray();
    }

    echo json_encode([
        'ok' => true,
        "recordsTotal" => $limit,
        "recordsFiltered" => count($usuarios),
        'data' => array_slice($usuariosArray, $skip, $limit)
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}