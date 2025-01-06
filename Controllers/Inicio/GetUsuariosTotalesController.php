<?php

require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';

try {
    $usuarios = Usuario::getUsuarios([], 'ASC');
    $totalUsuarios = count($usuarios);

    echo json_encode([
        'ok' => true,
        'data' => $totalUsuarios
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => 0,
        'mensaje' => $exception->getMessage()
    ]);
}
