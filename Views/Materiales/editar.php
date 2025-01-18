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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
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
                <input type="number" id="codigo" placeholder="Código" maxlength="20" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" placeholder="Nombre" maxlength="20" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" placeholder="Descripción" maxlength="30">
            </div>
            <div class="form-group">
                <label for="marca">Marca</label>
                <input type="text" id="marca" placeholder="Marca" maxlength="15">
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
                <input type="text" id="presentacion" placeholder="Presentación" maxlength="30" required>
            </div>
            <div class="form-group">
                <label for="unidad">Unidad *</label>
                <select name="unidad" id="unidad" required>
                    <option value="">Seleccione</option>
                    <option value="unidad">Unidad</option>
                    <option value="kilogramos">Kilogramos (kg)</option>
                    <option value="gramos">Gramos (g)</option>
                    <option value="miligramos">Miligramos (mg)</option>
                    <option value="libras">Libras (lb)</option>
                    <option value="onzas">Onzas (oz)</option>
                    <option value="toneladas">Toneladas métricas (t)</option>
                    <option value="metros">Metros (m)</option>
                    <option value="metros_cuadrado">Metros cuadrados(m2)</option>
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
                <button type="submit" class="btn btn-success">
                    <img src="/vanilla-inventario/Assets/iconos/guardar.svg" alt="guardar.svg"> Guardar
                </button>
                <button type="reset" class="btn btn-secondary" onclick="cancelar(event)">
                    <img src="/vanilla-inventario/Assets/iconos/cancelar.svg" alt="cancelar.svg"> Cancelar
                </button>
            </div>
        </div>

        <hr>
        <p>Todos los campos con asterisco (*) son obligatorios.</p>

        <input type="hidden" id="id" name="id" value="">
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/materiales/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/materiales/editar.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
</body>
</html>