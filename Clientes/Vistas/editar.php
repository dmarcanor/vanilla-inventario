<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/Menu/Vistas/menu.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --background-color: #ecf0f1;
            --text-color: #34495e;
            --sidebar-color: #2c3e50;
            --hover-color: #2980b9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        #content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .module-title {
            font-size: 28px;
            color: var(--primary-color);
            margin: 0;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../../Menu/Vistas/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Clientes - Editar</h1>
    </div>
    <form class="usuario-form" onsubmit="guardar(event)">
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

        <input type="hidden" id="id" name="id" value="">

        <div class="grupo-botones">
            <button type="submit">Guardar</button>
            <button onclick="cancelar(event)">Cancelar</button>
        </div>
    </form>
</div>

<script src="main.js"></script>
<script src="editar.js"></script>
<script src="formulario.js"></script>
<script src="/Menu/Vistas/menu.js"></script>
</body>
</html>