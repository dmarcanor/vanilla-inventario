<?php

require_once __DIR__ . '/../../Models/Categorias/Categoria.php';

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