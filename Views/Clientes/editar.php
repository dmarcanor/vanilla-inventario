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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/clientes/editar.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Clientes - Editar</h1>
    </div>
    <form class="form" onsubmit="guardar(event)">
        <div class="form-row">
            <div class="form-group">
                <label for="tipo_identificacion">Tipo de identificación *</label>
                <select name="tipo_identificacion" id="tipo_identificacion" required>
                    <option value="">Seleccione</option>
                    <option value="cedula">Cédula</option>
                    <option value="rif">Rif</option>
                    <option value="pasaporte">Pasaporte</option>
                </select>
            </div>
            <div class="form-group">
                <label for="numero_identificacion">Número de identificación *</label>
                <input type="text" id="numero_identificacion" placeholder="Número de identificación" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" placeholder="Nombre" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido *</label>
                <input type="text" id="apellido" placeholder="Apellido" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono *</label>
                <input type="number" id="telefono" placeholder="Teléfono" max="04269999999"
                       required
                       oninvalid="this.setCustomValidity('El número debe iniciar con 0412, 0414, 0416, 0424 o 0426 seguido de 7 dígitos y no puede contener caracteres especiales o letras.')"
                       oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="estado">Estado *</label>
                <select name="estado" id="estado" required>
                    <option value="">Seleccione</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="big-form-group">
                <label for="direccion">Dirección *</label>
                <input type="text" id="direccion" placeholder="Dirección" required>
            </div>
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

<script src="/vanilla-inventario/Assets/js/clientes/main.js"></script>
<script src="/vanilla-inventario/Assets/js/clientes/editar.js"></script>
<script src="/vanilla-inventario/Assets/js/clientes/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
</body>
</html>