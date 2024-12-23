<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/entradas/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Entradas</h1>
        <a class="new-user-btn" href="crear.php">Registrar nueva entrada</a>
    </div>
    <form class="form" onsubmit="buscar(event)">
        <h3>Búsqueda de entradas</h3>
        <hr>
        <div class="form-row">
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" id="id" placeholder="ID">
            </div>
            <div class="form-group">
                <label for="numero_entrada">Número de entrada</label>
                <input type="text" id="numero_entrada" placeholder="Número de entrada">
            </div>
            <div class="form-group">
                <label for="fecha_desde">Fecha creación desde</label>
                <input type="date" id="fecha_desde">
            </div>
            <div class="form-group">
                <label for="fecha_hasta">Fecha creación hasta</label>
                <input type="date" id="fecha_hasta">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="material">Material</label>
                <select name="material" id="material">
                    <option value="">Seleccione un material</option>
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
        <h3>Listado de entradas</h3>
        <hr/>
        <table id="usuarios-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th class="dt-center">ID</th>
                <th class="dt-center">Número de entrada</th>
                <th class="dt-center">Observación</th>
                <th class="dt-center">Fecha de entrada</th>
                <th class="dt-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="/vanilla-inventario/Assets/js/entradas/main.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
</body>
</html>