<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/reportes/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/usuarios/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php'; ?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Reportes</h1>
    </div>

    <div class="cuadricula-reportes">
        <div class="tarjeta-reporte">
            <a href="/vanilla-inventario/Views/Reportes/usuarios.php">
                <div class="encabezado-tarjeta">Usuarios</div>
                <div class="cuerpo-tarjeta">
                    <div class="descripcion-reporte">
                        Reporte detallado de todos los usuarios registrados en el sistema.
                    </div>
                </div>
            </a>
        </div>
        <div class="tarjeta-reporte">
            <a href="/vanilla-inventario/Views/Reportes/clientes.php">
                <div class="encabezado-tarjeta">Clientes</div>
                <div class="cuerpo-tarjeta">
                    <div class="descripcion-reporte">
                        Reporte detallado de todos los clientes registrados en el sistema.
                    </div>
                </div>
            </a>
        </div>
        <div class="tarjeta-reporte">
            <a href="/vanilla-inventario/Views/Reportes/materiales.php">
                <div class="encabezado-tarjeta">Materiales</div>
                <div class="cuerpo-tarjeta">
                    <div class="descripcion-reporte">
                        Reporte detallado de todos los materiales registrados en el sistema.
                    </div>
                </div>
            </a>
        </div>
        <div class="tarjeta-reporte">
            <a href="/vanilla-inventario/Views/Reportes/categorias.php">
                <div class="encabezado-tarjeta">Categorías</div>
                <div class="cuerpo-tarjeta">
                    <div class="descripcion-reporte">
                        Reporte detallado de todas las categorías registradas en el sistema.
                    </div>
                </div>
            </a>
        </div>
        <div class="tarjeta-reporte">
            <a href="/vanilla-inventario/Views/Reportes/entradas.php">
                <div class="encabezado-tarjeta">Entradas</div>
                <div class="cuerpo-tarjeta">
                    <div class="descripcion-reporte">
                        Reporte detallado de todas las entradas registradas en el sistema.
                    </div>
                </div>
            </a>
        </div>
        <div class="tarjeta-reporte">
            <a href="/vanilla-inventario/Views/Reportes/salidas.php">
                <div class="encabezado-tarjeta">Salidas</div>
                <div class="cuerpo-tarjeta">
                    <div class="descripcion-reporte">
                        Reporte detallado de todas las salidas registradas en el sistema.
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!--<script src="/vanilla-inventario/Assets/js/historial/main.js"></script>-->
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
</body>
</html>