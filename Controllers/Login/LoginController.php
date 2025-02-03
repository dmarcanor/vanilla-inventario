<?php

declare(strict_types=1);

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';

$data = json_decode(file_get_contents('php://input'), true);
$usuario = Usuario::getUsuarioPorNombreUsuario($data['usuario']);

if (empty($usuario)) {
    http_response_code(404);
    echo json_encode([
        'ok' => false,
        'usuario' => [],
        'mensaje' => 'Usuario o contraseña incorrectos'
    ]);
    exit();
}

if ($usuario->estado() !== 'incorporado') {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'usuario' => [],
        'mensaje' => 'El usuario no está incorporado'
    ]);
    exit();
}

if ($data['contrasenia'] !== $usuario->contraseniaDesencriptada()) {
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'usuario' => [],
        'mensaje' => 'Usuario o contraseña incorrectos'
    ]);
    exit();
}

session_start();

// Regenerar el ID de sesión para evitar fijación de sesión
session_regenerate_id(true);

// Guardar datos en la sesión
$_SESSION['usuario_id'] = $usuario->id();
$_SESSION['nombre_usuario'] = $usuario->nombreUsuario();
$_SESSION['last_activity'] = time();

http_response_code(200);
echo json_encode([
    'ok' => true,
    'usuario' => $usuario->toArray()
]);