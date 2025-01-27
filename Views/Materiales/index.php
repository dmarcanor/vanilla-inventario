<?php
require_once '../../helpers.php';
try {
    verificarSesion();
} catch (\Exception $exception) {
    header('Location: /vanilla-inventario/Views/Login/index.php');
    exit();
}

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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/materiales/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/js/toastr/build/toastr.min.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Materiales</h1>
        <div class="module-actions">
            <a class="btn btn-success" href="crear.php">
                <img src="/vanilla-inventario/Assets/iconos/crear.svg" alt="crear.svg"> Crear nuevo material
            </a>
            <button type="button" id="imprimir" class="btn btn-primary" onclick="imprimir(event)">
                <img src="/vanilla-inventario/Assets/iconos/imprimir.svg" alt="imprimir.svg"> Reporte
            </button>
        </div>
    </div>
    <form class="form" id="form" onsubmit="buscar(event)">
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
                <select name="marca" id="marca">
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id">
                    <option value="">Seleccionar categoría</option>
                </select>
            </div>
            <div class="form-group">
                <label for="unidad">Unidad</label>
                <select id="unidad" name="unidad">
                    <option value="">Seleccionar unidad</option>
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
                <label for="fecha_desde">Fecha de Creación (Desde)</label>
                <input type="date" id="fecha_desde" name="fecha_desde">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="fecha_hasta">Fecha de Creación (Hasta)</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta">
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio" placeholder="Precio del material" min="0.01" step="0.01">
                </div>
            </div>
            <div class="form-group">
                <label for="presentacion">Presentación</label>
                <input type="text" id="presentacion" name="presentacion" placeholder="Presentación">
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="">Seleccionar estado</option>
                    <option value="activo">Activo</option>
                    <option value="desincorporado">Desincorporado</option>
                    <option value="stock_minimo">En stock mínimo</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="btn btn-success" id="submit">
                    <img src="/vanilla-inventario/Assets/iconos/buscar.svg" alt="buscar.svg"> Buscar
                </button>
                <button type="reset" class="btn btn-secondary" id="limpiar"  onclick="limpiarFormulario()">
                    <img src="/vanilla-inventario/Assets/iconos/limpiar.svg" alt="limpiar.svg"> Limpiar
                </button>
            </div>
        </div>
    </form>
    <div class="usuarios-table-seccion">
        <h3>Listado de materiales</h3>
        <hr/>
        <form class="form_dolar" onsubmit="calcularPrecioBolivar(event)">
            <input type="number" id="precio_dolar" name="precio_dolar" placeholder="Precio del dolar" min="0" step="0.01" required>
            <button type="submit" class="btn btn-success" id="submit">
                <img src="/vanilla-inventario/Assets/iconos/calcular.svg" alt="calcular.svg"> Calcular
            </button>
        </form>
        <table id="usuarios-table" class="table table-bordered" style="width:100%">
        </table>
    </div>
</div>

<script src="/vanilla-inventario/Assets/js/materiales/main.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
<script src="/vanilla-inventario/Assets/js/toastr/build/toastr.min.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
</body>
</html>