<!--<!DOCTYPE html>-->
<!--<html lang="es">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<!--    <title>Sistema de Inventario</title>-->
<!--    <link rel="stylesheet" href="/Layout/styles.css">-->
<!--</head>-->
<!--<body>-->
<!--<div class="app-container">-->
<!--    <aside class="sidebar">-->
<!--        <h1 class="sidebar-title">Sistema de Inventario</h1>-->
<!--        <nav>-->
<!--            <ul>-->
<!--                <li><a href="#" data-module="usuarios"><span class="icon">👥</span> Usuarios</a></li>-->
<!--                <li><a href="#" data-module="clientes"><span class="icon">🏢</span> Clientes</a></li>-->
<!--                <li><a href="#" data-module="categorias"><span class="icon">📁</span> Categorías</a></li>-->
<!--                <li><a href="#" data-module="materiales"><span class="icon">📦</span> Materiales</a></li>-->
<!--                <li><a href="#" data-module="entradas"><span class="icon">⬇️</span> Entradas</a></li>-->
<!--                <li><a href="#" data-module="salidas"><span class="icon">⬆️</span> Salidas</a></li>-->
<!--            </ul>-->
<!--        </nav>-->
<!--        <div class="sidebar-footer">-->
<!--            <div class="user-info">-->
<!--                <span class="icon">👤</span>-->
<!--                <span id="user-name">Nombre de Usuario</span>-->
<!--            </div>-->
<!--            <button id="logout-btn"><span class="icon">🚪</span> Cerrar sesión</button>-->
<!--        </div>-->
<!--    </aside>-->
<!--</div>-->
<!--</body>-->
<!--</html>-->


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

        /*table {*/
        /*    width: 100%;*/
        /*    border-collapse: separate;*/
        /*    border-spacing: 0;*/
        /*    background-color: white;*/
        /*    border-radius: 8px;*/
        /*    overflow: hidden;*/
        /*    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);*/
        /*}*/

        /*th, td {*/
        /*    border: none;*/
        /*    padding: 12px;*/
        /*    text-align: left;*/
        /*}*/

        /*th {*/
        /*    background-color: var(--primary-color);*/
        /*    color: white;*/
        /*    font-weight: normal;*/
        /*}*/

        /*tr:nth-child(even) {*/
        /*    background-color: #f8f9fa;*/
        /*}*/

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
        <li><a href="#usuarios"><span class="icon">👥</span> Usuarios</a></li>
        <li><a href="#clientes"><span class="icon">🏢</span> Clientes</a></li>
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
            <label for="cedula">Cédula</label>
            <input type="text" id="cedula" placeholder="Cedula" required>
        </div>
        <div class="grupo">
            <label for="contrasenia">Contraseña</label>
            <input type="password" id="contrasenia" placeholder="Contraseña" required>
        </div>
        <div class="grupo">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" placeholder="Teléfono" required>
        </div>
        <div class="grupo">
            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" placeholder="Dirección" required>
        </div>
        <div class="grupo">
            <label for="rol">Rol</label>
            <select name="rol" id="rol" required>
                <option value="">Seleccione</option>
                <option value="admin">Administrador</option>
                <option value="operador">Operador</option>
            </select>
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

<script src="main.js"></script>
<script src="formulario.js"></script>
</body>
</html>