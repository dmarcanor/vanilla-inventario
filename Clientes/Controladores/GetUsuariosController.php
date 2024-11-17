<?php

declare(strict_types=1);

require_once __DIR__ . '/../../BD/ConexionBD.php';

$baseDatos = (new ConexionBD())->getConexion();
$consulta = $baseDatos->prepare("SELECT id, nombre, correo, estado, rol, estado FROM usuarios");

try {
    $consulta->execute();
    $usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'ok' => true,
        "recordsTotal" => count($usuarios),
        "recordsFiltered" => count($usuarios),
        'data' => $usuarios
    ]);
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'usuario' => []
    ]);
}