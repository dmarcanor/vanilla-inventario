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

    <form class="form" onsubmit="generar(event)">
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
                <button type="submit" class="success-button" id="submit">Generar</button>
                <button type="reset" class="cancel-button" id="limpiar">Limpiar</button>
            </div>
        </div>
    </form>
</div>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/reportes/entradas.js"></script>
</body>
</html>