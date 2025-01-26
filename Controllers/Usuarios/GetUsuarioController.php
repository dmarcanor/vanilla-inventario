<?php

require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';
require_once '../../helpers.php';

try {
    verificarSesion();
} catch (\Exception $exception) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['mensaje' => 'Sesión expirada']);
    exit();
}

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