<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/materiales/editar.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Materiales - Editar</h1>
    </div>
    <form class="form" onsubmit="guardar(event)">
        <div class="form-row">
            <div class="form-group">
                <label for="codigo">Código *</label>
                <input type="text" id="codigo" placeholder="Código" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre *</label>
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
                <label for="categoria_id">Categoría *</label>
                <select name="categoria_id" id="categoria_id" required>
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="form-group">
                <label for="presentacion">Presentación *</label>
                <input type="text" id="presentacion" placeholder="Presentación" required>
            </div>
            <div class="form-group">
                <label for="unidad">Unidad *</label>
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
                <label for="precio">Precio *</label>
                <div style="display: flex">
                    <span style="border: ridge">$</span>
                    <input type="number" id="precio" name="precio" min="0.01" step="0.01" placeholder="Precio" style="padding-left: 20px;" required>
                </div>
            </div>
            <div class="form-group">
                <label for="stock_minimo">Stock mínimo *</label>
                <input type="number" id="stock_minimo" name="stock_minimo" min="0" placeholder="Stock mínimo" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" placeholder="Stock" min="0" step="0.01" disabled>
            </div>
            <div class="form-group"></div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="success-button">Guardar</button>
                <button type="reset" class="cancel-button" onclick="cancelar(event)">Cancelar</button>
            </div>
        </div>

        <input type="hidden" id="id" name="id" value="">
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/materiales/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/materiales/editar.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
</body>
</html>