<?php

declare(strict_types=1);

require_once __DIR__ . '/../../BD/ConexionBD.php';

$baseDatos = (new ConexionBD())->getConexion();
$consulta = $baseDatos->prepare("SELECT id, nombre, correo, estado, rol, estado FROM usuarios WHERE id = ?");
$consulta->execute([$_GET['id']]);
$usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);

if (empty($usuarios)) {
    echo json_encode([
        'ok' => false,
        'usuario' => []
    ]);
    exit();
}

echo json_encode([
    'ok' => true,
    'usuario' => $usuarios[0]
]);