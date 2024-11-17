<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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

        #sidebar {
            width: 250px;
            height: 100vh;
            background-color: var(--sidebar-color);
            padding: 20px;
            box-sizing: border-box;
            color: white;
        }

        #content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .app-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: var(--primary-color);
        }

        .module-list {
            list-style-type: none;
            padding: 0;
        }

        .module-list li {
            margin-bottom: 15px;
        }

        .module-list a {
            text-decoration: none;
            color: #ecf0f1;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .module-list a:hover {
            background-color: var(--hover-color);
        }

        .user-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b;
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
<div id="sidebar">
    <div class="app-name">Sistema de inventario</div>
    <ul class="module-list">
        <li><a href="#inicio"><span class="icon">🏠</span> Inicio</a></li>
        <li><a href="/Usuarios/Vistas/index.php"><span class="icon">👥</span> Usuarios</a></li>
        <li><a href="/Clientes/Vistas/index.php"><span class="icon">🏢</span> Clientes</a></li>
        <li><a href="#categorias"><span class="icon">📁</span> Categorías</a></li>
        <li><a href="#articulos"><span class="icon">📦</span> Materiales</a></li>
        <li><a href="#entradas"><span class="icon">⬇️</span> Entradas</a></li>
        <li><a href="#salidas"><span class="icon">⬆️</span> Salidas</a></li>
    </ul>
    <div class="user-info">
        <p id="usuario">Usuario</p>
        <button class="logout-btn">Cerrar Sesión</button>
    </div>
</div>
<div id="content">
    <div class="module-header">
        <h1 class="module-title">Usuarios</h1>
        <a class="new-user-btn" href="crear.php">Crear Nuevo Usuario</a>
    </div>
    <form class="search-form" onsubmit="buscar(event)">
        <h3>Búsqueda de usuarios</h3>
        <hr>
        <input type="text" id="nombre" placeholder="Nombre">
        <input type="text" id="apellido" placeholder="Apellido">
        <input type="text" id="cedula" placeholder="Cédula">
        <input type="text" id="telefono" placeholder="Teléfono">
        <input type="text" id="direccion" placeholder="Dirección">
        <select id="rol">
            <option value="">Rol</option>
            <option value="admin">Administrador</option>
            <option value="operador">Operador</option>
        </select>
        <select id="estado">
            <option value="">Estado</option>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>
        <button type="submit" id="submit">Buscar</button>
        <button type="reset" id="limpiar"  onclick="limpiarFormulario()">Limpiar</button>
    </form>
    <div class="usuarios-table-seccion">
        <h3>Listado de usuarios</h3>
        <hr/>
        <table id="usuarios-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="main.js"></script>

</body>
</html>