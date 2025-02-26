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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/salidas/crear.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/salidas/formulario.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/js/toastr/build/toastr.min.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/js/select2-4.1.0-rc.0/dist/css/select2.min.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Salidas - Crear</h1>
    </div>
    <form id="formulario-salidas" class="form" onsubmit="guardar(event)" oninput="guardarDatosFormulario(this, 'Salidas')" onchange="guardarDatosFormulario(this, 'Salidas')">
        <div class="form-row">
            <div class="form-group">
                <label for="clienteId">Cliente *</label>
                <select name="clienteId" id="clienteId" required>
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="big-form-group">
                <label for="observacion">Observación</label>
                <input type="text" id="observacion" placeholder="Observacion" maxlength="30">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <table id="salidas-items" class="dynamic-table">
                    <thead>
                    <tr>
                        <th width="350px">Material *</th>
                        <th>Cantidad *</th>
                        <th>Tipo de precio *</th>
                        <th>Precio *</th>
                        <th>Unidad</th>
                        <th>Stock actual</th>
                        <th>Stock posterior</th>
                        <th>Precio total</th>
                        <th>
                            <button type="button" id="addRow" class="add-row-btn">+</button>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="salida-items-body">
                    </tbody>
                    <tfoot id="salida-items-footer">
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="success-button">
                    <img src="/vanilla-inventario/Assets/iconos/guardar.svg" alt="guardar.svg"> Guardar
                </button>
                <button type="reset" class="cancel-button" onclick="cancelar(event)">
                    <img src="/vanilla-inventario/Assets/iconos/cancelar.svg" alt="cancelar.svg"> Cancelar
                </button>
            </div>
        </div>

        <hr>
        <p>Todos los campos con asterisco (*) son obligatorios.</p>
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/salidas/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/salidas/materiales-tabla.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
<script src="/vanilla-inventario/Assets/js/toastr/build/toastr.min.js"></script>
<script src="/vanilla-inventario/Assets/js/select2-4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>