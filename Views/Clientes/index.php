<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/clientes/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Clientes</h1>
        <a class="new-user-btn" href="crear.php">Registrar nuevo cliente</a>
    </div>
    <form class="form" onsubmit="buscar(event)">
        <h3>Búsqueda de clientes</h3>
        <hr>
        <div class="form-row">
            <div class="form-group">
                <label for="tipo_identificacion">Tipo de identificación</label>
                <select id="tipo_identificacion">
                    <option value="">Tipo de identificación</option>
                    <option value="cedula">Cédula</option>
                    <option value="rif">Rif</option>
                    <option value="pasaporte">Pasaporte</option>
                </select>
            </div>
            <div class="form-group">
                <label for="numero_identificacion">Número de identificación</label>
                <input type="text" id="numero_identificacion" placeholder="Número de identificación">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Nombre">
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" placeholder="Apellido">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" placeholder="Teléfono">
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado">
                    <option value="">Seleccione</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="big-form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" placeholder="Dirección">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="success-button" id="submit">Buscar</button>
                <button type="reset" class="cancel-button" id="limpiar"  onclick="limpiarFormulario()">Limpiar</button>
            </div>
        </div>
    </form>
    <div class="usuarios-table-seccion">
        <h3>Listado de clientes</h3>
        <hr/>
        <table id="usuarios-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th class="dt-center">ID</th>
                <th class="dt-center">Nombre</th>
                <th class="dt-center">Apellido</th>
                <th class="dt-center">Tipo de identificación</th>
                <th class="dt-center">Número de identificación</th>
                <th class="dt-center">Teléfono</th>
                <th class="dt-center">Estado</th>
                <th class="dt-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="/vanilla-inventario/Assets/js/clientes/main.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
</body>
</html>