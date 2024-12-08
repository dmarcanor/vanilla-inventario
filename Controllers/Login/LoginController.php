<?php

declare(strict_types=1);

require_once __DIR__ . '/../../BD/ConexionBD.php';

$data = json_decode(file_get_contents('php://input'), true);

$baseDatos = (new ConexionBD())->getConexion();
$consulta = $baseDatos->prepare("SELECT id, nombre, apellido, cedula, contrasenia, rol, estado FROM usuarios WHERE nombre = ?");
$consulta->execute([$data['usuario']]);
$usuario = $consulta->fetch(PDO::FETCH_ASSOC);

if (empty($usuario)) {
    http_response_code(404);
    echo json_encode([
        'ok' => false,
        'usuario' => []
    ]);
    exit();
}

if (password_verify($data['contrasenia'], $usuario['contrasenia']) === false) {
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
    'usuario' => $usuario
]);