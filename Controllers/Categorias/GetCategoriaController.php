<?php

require_once __DIR__ . '/../../Models/Categorias/Categoria.php';

require_once '../../helpers.php';
try {
    verificarSesion();
} catch (\Exception $exception) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['mensaje' => 'Sesión expirada']);
    exit();
}

try {
    $categoria = Categoria::getCategoria($_GET['id']);

    echo json_encode([
        'ok' => true,
        'categoria' => $categoria->toArray()
    ]);
    exit();
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage(),
        'categoria' => []
    ]);
    exit();
}