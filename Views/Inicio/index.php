<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
        <a href="/vanilla-inventario/Views/Usuarios/index.php" class="tarjeta-tablero">
            <div class="emoji-tarjeta" style="background-color: limegreen">👤</div>
            <div class="info-tarjeta">
                <div class="titulo-tarjeta">Usuarios</div>
                <div id="contadorUsuarios" class="contador-tarjeta">0</div>
            </div>
        </a>

        <a href="/vanilla-inventario/Views/Clientes/index.php" class="tarjeta-tablero">
            <div class="emoji-tarjeta" style="background-color: orange">🤝</div>
            <div class="info-tarjeta">
                <div class="titulo-tarjeta">Clientes</div>
                <div id="contadorClientes" class="contador-tarjeta">0</div>
            </div>
        </a>

        <a href="/vanilla-inventario/Views/Materiales/index.php" class="tarjeta-tablero">
            <div class="emoji-tarjeta" style="background-color: deepskyblue">📦</div>
            <div class="info-tarjeta">
                <div class="titulo-tarjeta">Materiales</div>
                <div id="contadorMateriales" class="contador-tarjeta">0</div>
            </div>
        </a>

        <a href="/vanilla-inventario/Views/Categorias/index.php" class="tarjeta-tablero">
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
                <table>
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Material</th>
                        <th>Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>2023-05-01</td>
                        <td>Tornillos</td>
                        <td>1000</td>
                    </tr>
                    <tr>
                        <td>2023-04-30</td>
                        <td>Madera</td>
                        <td>50</td>
                    </tr>
                    <tr>
                        <td>2023-04-29</td>
                        <td>Pintura</td>
                        <td>25</td>
                    </tr>
                    <tr>
                        <td>2023-04-28</td>
                        <td>Clavos</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>2023-04-27</td>
                        <td>Cemento</td>
                        <td>100</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tabla-rapida">
            <h2 class="titulo-tabla">Salidas Recientes</h2>
            <div class="tabla-contenedor">
                <table>
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Material</th>
                        <th>Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>2023-05-02</td>
                        <td>Tornillos</td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>2023-05-01</td>
                        <td>Madera</td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td>2023-04-30</td>
                        <td>Pintura</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>2023-04-29</td>
                        <td>Clavos</td>
                        <td>100</td>
                    </tr>
                    <tr>
                        <td>2023-04-28</td>
                        <td>Cemento</td>
                        <td>20</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tabla-rapida">
            <h2 class="titulo-tabla">Materiales en Stock Mínimo</h2>
            <div class="tabla-contenedor">
                <table>
                    <thead>
                    <tr>
                        <th>Material</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Tornillos</td>
                        <td>100</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Madera</td>
                        <td>5</td>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td>Pintura</td>
                        <td>2</td>
                        <td>10</td>
                    </tr>
                    <tr>
                        <td>Clavos</td>
                        <td>50</td>
                        <td>200</td>
                    </tr>
                    <tr>
                        <td>Cemento</td>
                        <td>10</td>
                        <td>50</td>
                    </tr>
                    <tr>
                        <td>Ladrillos</td>
                        <td>100</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td>Tubos PVC</td>
                        <td>15</td>
                        <td>30</td>
                    </tr>
                    <tr>
                        <td>Arena</td>
                        <td>1000</td>
                        <td>5000</td>
                    </tr>
                    <tr>
                        <td>Yeso</td>
                        <td>20</td>
                        <td>100</td>
                    </tr>
                    <tr>
                        <td>Azulejos</td>
                        <td>50</td>
                        <td>200</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="/vanilla-inventario/Assets/js/inicio/main.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
</body>
</html>