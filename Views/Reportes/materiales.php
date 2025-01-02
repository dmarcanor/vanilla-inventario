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
        <h3>Búsqueda de materiales</h3>
        <hr>
        <div class="form-row">
            <div class="form-group">
                <label for="nombre">ID</label>
                <input type="number" id="id" name="id" placeholder="ID">
            </div>
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" id="codigo" name="codigo" placeholder="Código del material">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre del material">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" placeholder="Descripción del material">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="marca">Marca</label>
                <input type="text" id="marca" name="marca" placeholder="Marca del material">
            </div>
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id">
                    <option value="">Seleccionar categoría</option>
                    <option value="1">categoria de purbea</option>
                </select>
            </div>
            <div class="form-group">
                <label for="unidad">Unidad</label>
                <select id="unidad" name="unidad">
                    <option value="">Seleccionar unidad</option>
                    <option value="kilogramos">Kilogramos (kg)</option>
                    <option value="gramos">Gramos (g)</option>
                    <option value="miligramos">Miligramos (mg)</option>
                    <option value="libras">Libras (lb)</option>
                    <option value="onzas">Onzas (oz)</option>
                    <option value="toneladas">Toneladas métricas (t)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_desde">Fecha de Creación (Desde)</label>
                <input type="datetime-local" id="fecha_desde" name="fecha_desde">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="fecha_hasta">Fecha de Creación (Hasta)</label>
                <input type="datetime-local" id="fecha_hasta" name="fecha_hasta">
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="precio_desde">Precio (Desde)</label>
                    <input type="number" id="precio_desde" name="precio_desde" placeholder="Precio del material (Desde)" min="0.01" step="0.01">
                </div>
            </div>
            <div class="form-group">
                <label for="precio_hasta">Precio (Hasta)</label>
                <input type="number" id="precio_hasta" name="precio_hasta" placeholder="Precio del material (Hasta)" min="0.01" step="0.01">
            </div>
            <div class="form-group">
                <label for="presentacion">Presentación</label>
                <input type="text" id="presentacion" name="presentacion" placeholder="Presentación">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="">Seleccionar estado</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
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
<script src="/vanilla-inventario/Assets/js/reportes/materiales.js"></script>
</body>
</html>