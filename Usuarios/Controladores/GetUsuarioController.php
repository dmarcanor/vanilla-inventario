<?php

require_once __DIR__ . '/../Modelos/Usuario.php';

$usuario = Usuario::getUsuario($_GET['id'])->toArray();

if (empty($usuario)) {
    echo json_encode([
        'ok' => false,
        'usuario' => []
    ]);
    exit();
}

echo json_encode([
    'ok' => true,
    'usuario' => $usuario
]);