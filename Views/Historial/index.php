<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/usuarios/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php'; ?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Historial de usuarios</h1>
    </div>
    <form class="form" onsubmit="buscar(event)">
        <h3>Búsqueda de historial de usuarios</h3>
        <hr>
        <div class="form-row">
            <div class="form-group">
                <label for="usuarios">Usuario</label>
                <select name="usuarios" id="usuarios">
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="form-group"></div>
            <div class="form-group"></div>
            <div class="form-group"></div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="success-button" id="submit">Buscar</button>
                <button type="reset" class="cancel-button" id="limpiar"  onclick="limpiarFormulario()">Limpiar</button>
            </div>
        </div>
    </form>
    <div class="usuarios-table-seccion">
        <h3>Listado de historial de usuarios</h3>
        <hr/>
        <table id="usuarios-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th class="dt-center">ID</th>
                <th class="dt-center">Usuario</th>
                <th class="dt-center">Tipo de acción</th>
                <th class="dt-center">Tipo de entidad</th>
                <th class="dt-center">Entidad</th>
                <th class="dt-center">Cambio</th>
                <th class="dt-center">Fecha</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="/vanilla-inventario/Assets/js/historial/main.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
</body>
</html>