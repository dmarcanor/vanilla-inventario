<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/inicio/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php'; ?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Inicio</h1>
    </div>

    <div class="cuadricula-tablero">
        <a href="/vanilla-inventario/Views/Usuarios/index.php" id="tarjeta_usuario" class="tarjeta-tablero" onload="ocultarSiNoEsAdmin()">
            <div class="emoji-tarjeta" style="background-color: limegreen">👤</div>
            <div class="info-tarjeta">
                <div class="titulo-tarjeta">Usuarios</div>
                <div id="contadorUsuarios" class="contador-tarjeta">0</div>
            </div>
        </a>

        <a href="/vanilla-inventario/Views/Clientes/index.php" id="tarjeta_cliente" class="tarjeta-tablero">
            <div class="emoji-tarjeta" style="background-color: orange">🤝</div>
            <div class="info-tarjeta">
                <div class="titulo-tarjeta">Clientes</div>
                <div id="contadorClientes" class="contador-tarjeta">0</div>
            </div>
        </a>

        <a href="/vanilla-inventario/Views/Materiales/index.php" id="tarjeta_materiales" class="tarjeta-tablero">
            <div class="emoji-tarjeta" style="background-color: deepskyblue">📦</div>
            <div class="info-tarjeta">
                <div class="titulo-tarjeta">Materiales</div>
                <div id="contadorMateriales" class="contador-tarjeta">0</div>
            </div>
        </a>

        <a href="/vanilla-inventario/Views/Categorias/index.php" id="tarjeta_categorias" class="tarjeta-tablero">
            <div class="emoji-tarjeta" style="background-color: yellow">🏷️</div>
            <div class="info-tarjeta">
                <div class="titulo-tarjeta">Categorías</div>
                <div id="contadorCategorias" class="contador-tarjeta">0</div>
            </div>
        </a>
    </div>

    <div class="seccion-tablas">
        <div class="tabla-rapida">
            <h2 class="titulo-tabla">Entradas Recientes</h2>
            <div class="tabla-contenedor">
                <table class="table" id="tabla_entradas_recientes">
                    <thead>
                    <tr>
                        <th>Número de entrada</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tabla-rapida">
            <h2 class="titulo-tabla">Salidas Recientes</h2>
            <div class="tabla-contenedor">
                <table class="table" id="tabla_salidas_recientes">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tabla-rapida">
            <h2 class="titulo-tabla">Materiales en Stock Mínimo</h2>
            <div class="tabla-contenedor">
                <table class="table" id="tabla_materiales_stock_minimo">
                    <thead>
                    <tr>
                        <th>Material</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
<script src="/vanilla-inventario/Assets/js/inicio/main.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
</body>
</html>