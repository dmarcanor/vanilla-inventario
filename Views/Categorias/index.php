<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/categorias/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Categorías</h1>
        <a class="new-user-btn" href="crear.php">Crear nuevo categoría</a>
    </div>
    <form class="form" onsubmit="buscar(event)">
        <h3>Búsqueda de categorías</h3>
        <hr>
        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Nombre">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" placeholder="Descripción">
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
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado">
                    <option value="">Seleccione</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
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
        <h3>Listado de categorías</h3>
        <hr/>
        <table id="usuarios-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Fecha de creación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="/vanilla-inventario/Assets/js/categorias/main.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
</body>
</html>