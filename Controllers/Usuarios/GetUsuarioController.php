<?php

require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';

try {
    $usuario = Usuario::getUsuario($_GET['id']);

    echo json_encode([
        'ok' => true,
        'usuario' => $usuario->toArray()
    ]);
    exit();
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage(),
        'usuario' => []
    ]);
    exit();
}