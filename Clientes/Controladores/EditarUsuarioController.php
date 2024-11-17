<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';

$data = json_decode(file_get_contents('php://input'), true);

$baseDatos = (new ConexionBD())->getConexion();
$usuarioConsulta = $baseDatos->prepare("SELECT id, nombre, correo, contrasenia, estado, rol, estado FROM usuarios WHERE id = ?");
$usuarioConsulta->execute([$data['id']]);

$usuario = $usuarioConsulta->fetch(PDO::FETCH_ASSOC);

if (empty($usuario)) {
    http_response_code(404);
    echo json_encode([
        'ok' => false,
        'mensaje' => "Usuario no encontrado."
    ]);
    exit();
}

$debeValidarCorreo = $usuario['correo'] !== $data['correo'];

if ($debeValidarCorreo) {
    $consultaValidarCorreo = $baseDatos->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");
    $consultaValidarCorreo->execute([$data['correo']]);

    if (!empty($consultaValidarCorreo->fetch(PDO::FETCH_ASSOC))) {
        http_response_code(400);
        echo json_encode([
            'ok' => false,
            'mensaje' => "El correo {$data['correo']} ya está en uso."
        ]);
        exit();
    }
}

if (!empty($data['contrasenia'])) {
    $consultaEditarUsuario = $baseDatos->prepare("
        UPDATE usuarios 
        SET nombre = ?, correo = ?, contrasenia = ?, rol = ?, estado = ?
        WHERE id = ?
    ");

    try {
        http_response_code(200);
        $consultaEditarUsuario->execute([
            $data['nombre'],
            $data['correo'],
            $data['contrasenia'],
            $data['rol'],
            $data['estado'],
            $data['id']
        ]);

        echo json_encode([
            'ok' => true,
            'mensaje' => 'Usuario actualizado correctamente.'
        ]);
        exit();
    } catch (\Exception $exception) {
        http_response_code(500);
        echo json_encode([
            'ok' => false,
            'mensaje' => $exception->getMessage()
        ]);
    }
} else {
    $consultaEditarUsuario = $baseDatos->prepare("
        UPDATE usuarios 
        SET nombre = ?, correo = ?, rol = ?, estado = ?
        WHERE id = ?
    ");

    try {
        http_response_code(200);
        $consultaEditarUsuario->execute([
            $data['nombre'],
            $data['correo'],
            $data['rol'],
            $data['estado'],
            $data['id']
        ]);

        echo json_encode([
            'ok' => true,
            'mensaje' => 'Usuario actualizado correctamente.'
        ]);
        exit();
    } catch (\Exception $exception) {
        http_response_code(500);
        echo json_encode([
            'ok' => false,
            'mensaje' => $exception->getMessage()
        ]);
    }
}

