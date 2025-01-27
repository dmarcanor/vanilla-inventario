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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/entradas/editar.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/entradas/formulario.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/js/toastr/build/toastr.min.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Entradas - Observar</h1>
    </div>
    <form class="form" onsubmit="guardar(event)">
        <div class="form-row">
            <div class="form-group">
                <label for="numero_entrada">Número de entrada *</label>
                <input type="number" id="numero_entrada" placeholder="Número de entrada" required>
            </div>
            <div class="big-form-group">
                <label for="observacion">Observación *</label>
                <input type="text" id="observacion" placeholder="Observación" maxlength="30">
            </div>
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <table id="entrada-items" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Material</th>
                        <th>Cantidad</th>
                        <th>Precio de costo ($)</th>
                        <th>Unidad</th>
                    </tr>
                    </thead>
                    <tbody id="entrada-items-body">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <button type="reset" class="cancel-button" onclick="cancelar(event)">
                    <img src="/vanilla-inventario/Assets/iconos/cancelar.svg" alt="cancelar.svg"> Cancelar
                </button>
            </div>
        </div>

        <input type="hidden" id="id" name="id" value="">
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/entradas/editar.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
<script src="/vanilla-inventario/Assets/js/toastr/build/toastr.min.js"></script>
</body>
</html>