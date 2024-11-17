<?php

declare(strict_types=1);

echo json_encode([
    'success' => true,
    'get' => $_GET,
    'articulos' => [
        [
            'id' => 1,
            'nombre' => 'Articulo 1',
            'precio' => 1000
        ],
        [
            'id' => 2,
            'nombre' => 'Articulo 2',
            'precio' => 2000
        ],
        [
            'id' => 3,
            'nombre' => 'Articulo 3',
            'precio' => 3000
        ]
    ]
]);