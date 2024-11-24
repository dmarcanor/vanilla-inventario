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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/clientes/crear.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Clientes - Crear</h1>
    </div>
    <form class="crear-usuario-form" onsubmit="guardar(event)">
        <div class="grupo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" placeholder="Nombre" required>
        </div>
        <div class="grupo">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" placeholder="Apellido" required>
        </div>
        <div class="grupo">
            <label for="tipo_identificacion">Tipo de identificación</label>
            <select name="tipo_identificacion" id="tipo_identificacion" required>
                <option value="">Seleccione</option>
                <option value="cedula">Cédula</option>
                <option value="rif">Rif</option>
                <option value="pasaporte">Pasaporte</option>
            </select>
        </div>
        <div class="grupo">
            <label for="numero_identificacion">Número de identificación</label>
            <input type="text" id="numero_identificacion" placeholder="Número de identificación" required>
        </div>
        <div class="grupo">
            <label for="telefono">Teléfono</label>
            <input type="tel" id="telefono" placeholder="Teléfono" minlength="11" maxlength="11" required>
        </div>
        <div class="grupo">
            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" placeholder="Dirección" required>
        </div>
        <div class="grupo">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" required>
                <option value="">Seleccione</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
        <div class="grupo-botones">
            <button type="submit">Guardar</button>
            <button onclick="cancelar(event)">Cancelar</button>
        </div>
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/clientes/main.js"></script>
<script src="/vanilla-inventario/Assets/js/clientes/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
</body>
</html>