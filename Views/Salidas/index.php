<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/salidas/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Salidas</h1>
        <a class="new-user-btn" href="crear.php">Crear nueva salida</a>
    </div>
    <form class="form" onsubmit="buscar(event)">
        <h3>Búsqueda de entradas</h3>
        <hr>
        <div class="form-row">
            <div class="form-group">
                <label for="descripcion">Descripcion</label>
                <input type="text" id="descripcion" placeholder="Descripcion">
            </div>
            <div class="form-group">
                <label for="usuarioId">Usuario registrador</label>
                <select id="usuarioId">
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="form-group">
                <label for="clienteId">Cliente</label>
                <select id="clienteId">
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="fecha_desde">Fecha creación desde</label>
                <input type="datetime-local" id="fecha_desde">
            </div>
            <div class="form-group">
                <label for="fecha_hasta">Fecha creación hasta</label>
                <input type="datetime-local" id="fecha_hasta">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="success-button" id="submit">Buscar</button>
                <button type="reset" class="cancel-button" id="limpiar"  onclick="limpiarFormulario()">Limpiar</button>
            </div>
        </div>
    </form>
    <div class="usuarios-table-seccion">
        <h3>Listado de salidas</h3>
        <hr/>
        <table id="usuarios-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th class="dt-center">ID</th>
                <th class="dt-center">Descripción</th>
                <th class="dt-center">Cliente</th>
                <th class="dt-center">Usuario registrador</th>
                <th class="dt-center">Fecha de salida</th>
                <th class="dt-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="/vanilla-inventario/Assets/js/salidas/main.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
</body>
</html>