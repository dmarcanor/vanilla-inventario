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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/materiales/crear.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Materiales - Crear</h1>
    </div>
    <form class="form" onsubmit="guardar(event)">
        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Nombre" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" placeholder="Descripción">
            </div>
            <div class="form-group">
                <label for="marca">Marca</label>
                <input type="text" id="marca" placeholder="Marca">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select name="categoria_id" id="categoria_id" required>
                    <option value="">Seleccione</option>
                    <option value="1">Test</option>
                </select>
            </div>
            <div class="form-group">
                <label for="unidad">Unidad</label>
                <select name="unidad" id="unidad" required>
                    <option value="">Seleccione</option>
                    <option value="kilogramos">Kilogramos (kg)</option>
                    <option value="gramos">Gramos (g)</option>
                    <option value="miligramos">Miligramos (mg)</option>
                    <option value="libras">Libras (lb)</option>
                    <option value="onzas">Onzas (oz)</option>
                    <option value="toneladas">Toneladas métricas (t)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="peso">Peso</label>
                <input type="number" id="peso" placeholder="Peso" min="0.01" step="0.01" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" id="precio" placeholder="Precio" min="0.01" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" required>
                    <option value="">Seleccione</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="success-button">Guardar</button>
                <button type="reset" class="cancel-button" onclick="cancelar(event)">Cancelar</button>
            </div>
        </div>
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/materiales/main.js"></script>
<script src="/vanilla-inventario/Assets/js/materiales/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
</body>
</html>