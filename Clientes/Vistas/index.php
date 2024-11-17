<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/vanilla-inventario/Menu/Vistas/menu.css">
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

        .search-form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .search-form input, .search-form select {
            margin-right: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-form button {
            padding: 8px 15px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-form button:hover {
            background-color: #27ae60;
        }

        .usuarios-table-seccion {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        table {
            text-align: center;
        }

        .new-user-btn {
            padding: 10px 20px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .new-user-btn:hover {
            background-color: #27ae60;
        }

        .action-btn {
            padding: 5px 10px;
            margin-right: 5px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: var(--hover-color);
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/../../Menu/Vistas/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Clientes</h1>
        <a class="new-user-btn" href="crear.php">Crear nuevo cliente</a>
    </div>
    <form class="search-form" onsubmit="buscar(event)">
        <h3>Búsqueda de clientes</h3>
        <hr>
        <input type="text" id="nombre" placeholder="Nombre">
        <input type="text" id="apellido" placeholder="Apellido">
        <select id="tipo_identificacion">
            <option value="">Tipo de identificación</option>
            <option value="cedula">Cédula</option>
            <option value="rif">Rif</option>
            <option value="pasaporte">Pasaporte</option>
        </select>
        <input type="text" id="numero_identificacion" placeholder="Número de identificación">
        <input type="text" id="telefono" placeholder="Teléfono">
        <input type="text" id="direccion" placeholder="Dirección">
        <div>
            <div>
                <label for="fecha_desde">Fecha creación desde</label>
                <input type="datetime-local" id="fecha_desde">
            </div>
            <div>
                <label for="fecha_hasta">Fecha creación hasta</label>
                <input type="datetime-local" id="fecha_hasta">
            </div>
        </div>
        <select id="estado">
            <option value="">Estado</option>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>
        <button type="submit" id="submit">Buscar</button>
        <button type="reset" id="limpiar"  onclick="limpiarFormulario()">Limpiar</button>
    </form>
    <div class="usuarios-table-seccion">
        <h3>Listado de clientes</h3>
        <hr/>
        <table id="usuarios-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Tipo de identificación</th>
                <th>Número de identificación</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Fecha de creación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="/vanilla-inventario/Clientes/Vistas/main.js"></script>
<script src="/vanilla-inventario/Menu/Vistas/menu.js"></script>
</body>
</html>