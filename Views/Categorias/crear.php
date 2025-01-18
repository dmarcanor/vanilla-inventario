<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/categorias/crear.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Categorías - Crear</h1>
    </div>
    <form class="form" onsubmit="guardar(event)">
        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" placeholder="Nombre" maxlength="20" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción *</label>
                <input type="text" id="descripcion" placeholder="Descripción" maxlength="30" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado *</label>
                <select name="estado" id="estado" disabled required>
                    <option selected value="activo">Activo</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <img src="/vanilla-inventario/Assets/iconos/guardar.svg" alt="guardar.svg"> Guardar</button>
                <button type="reset" class="btn btn-secondary" onclick="cancelar(event)">
                    <img src="/vanilla-inventario/Assets/iconos/cancelar.svg" alt="cancelar.svg">Cancelar
                </button>
            </div>
        </div>
        <hr>
        <p>Todos los campos con asterisco (*) son obligatorios.</p>
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/categorias/main.js"></script>
<script src="/vanilla-inventario/Assets/js/categorias/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
</body>
</html>