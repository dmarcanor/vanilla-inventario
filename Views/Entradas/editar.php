<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/entradas/editar.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/entradas/formulario.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Entradas - Editar</h1>
    </div>
    <form class="form" onsubmit="guardar(event)">
        <div class="form-row">
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" placeholder="Descripción" required>
            </div>
            <div class="form-group">
                <label for="usuarioId">Usuario registrador</label>
                <select name="usuario_id" id="usuarioId" required>
                    <option value="">Seleccione</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <table id="entrada-items" class="dynamic-table">
                    <thead>
                    <tr>
                        <th>Material</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Unidad</th>
                        <th>
                            <button type="button" id="addRow" class="add-row-btn">+</button>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="entrada-items-body">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
<!--                <button type="submit" class="success-button">Guardar</button>-->
                <button type="reset" class="cancel-button" onclick="cancelar(event)">Cancelar</button>
            </div>
        </div>

        <input type="hidden" id="id" name="id" value="">
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/entradas/editar.js"></script>
<script src="/vanilla-inventario/Assets/js/entradas/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/entradas/materiales-tabla.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
</body>
</html>