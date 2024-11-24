<?php

require_once __DIR__ . '/../../Models/Materiales/Material.php';

$material = Material::getMaterial($_GET['id'])->toArray();

if (empty($material)) {
    echo json_encode([
        'ok' => false,
        'material' => []
    ]);
    exit();
}

echo json_encode([
    'ok' => true,
    'material' => $material
]);