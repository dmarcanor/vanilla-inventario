<?php

require_once __DIR__ . '/../../Models/Materiales/Material.php';

try {
    $material = Material::getMaterial($_GET['id']);

    echo json_encode([
        'ok' => true,
        'material' => $material->toArray()
    ]);
    exit();
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'mensaje' => $exception->getMessage(),
        'material' => []
    ]);
    exit();
}