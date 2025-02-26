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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/salidas/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/js/toastr/build/toastr.min.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/js/select2-4.1.0-rc.0/dist/css/select2.min.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Salidas</h1>
        <div class="module-actions">
            <a class="btn btn-success" href="crear.php">
                <img src="/vanilla-inventario/Assets/iconos/crear.svg" alt="crear.svg"> Crear nueva salida
            </a>
            <button type="button" id="imprimir" class="btn btn-primary" onclick="imprimir(event)">
                <img src="/vanilla-inventario/Assets/iconos/imprimir.svg" alt="imprimir.svg"> Reporte
            </button>
        </div>
    </div>
    <form class="form" onsubmit="buscar(event)">
        <h3>Búsqueda de salidas</h3>
        <hr>
        <div class="form-row">
            <div class="form-group">
                <label for="id">ID</label>
                <input type="number" id="id" placeholder="ID">
            </div>
            <div class="form-group">
                <label for="observacion">Observación</label>
                <input type="text" id="observacion" placeholder="Observacion">
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
                <input type="date" id="fecha_desde">
            </div>
            <div class="form-group">
                <label for="fecha_hasta">Fecha creación hasta</label>
                <input type="date" id="fecha_hasta">
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="material">Material</label>
                    <select name="material" id="material">
                        <option value="">Seleccione un material</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="categoria">Categoría</label>
                    <select name="categoria" id="categoria">
                        <option value="">Seleccione una categoría</option>
                    </select>
                </div>
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
        <h3>Listado de salidas</h3>
        <hr/>
        <table id="usuarios-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th class="dt-center">ID</th>
                <th class="dt-center">Cliente</th>
                <th class="dt-center">Cantidad de materiales</th>
                <th class="dt-center">Observación</th>
                <th class="dt-center">Usuario registrador</th>
                <th class="dt-center">Fecha de salida</th>
                <th class="dt-center">Acción</th>
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
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
<script src="/vanilla-inventario/Assets/js/toastr/build/toastr.min.js"></script>
<script src="/vanilla-inventario/Assets/js/select2-4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>