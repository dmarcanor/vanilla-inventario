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
        'usuario' => []
    ]);
    exit();
}

if ($data['contrasenia'] !== $usuario->contraseniaDesencriptada()) {
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'usuario' => []
    ]);
    exit();
}

http_response_code(200);
echo json_encode([
    'ok' => true,
    'usuario' => $usuario->toArray()
]);