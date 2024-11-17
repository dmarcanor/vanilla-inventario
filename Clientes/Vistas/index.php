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

        #menu-lateral {
            width: 250px;
            height: 100vh;
            background-color: var(--sidebar-color);
            padding: 20px;
            box-sizing: border-box;
            color: white;
        }

        #contenido {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .titulo-app {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: var(--primary-color);
        }

        .menu {
            list-style-type: none;
            padding: 0;
        }

        .menu li {
            margin-bottom: 15px;
        }

        .menu a {
            text-decoration: none;
            color: #ecf0f1;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .menu a:hover {
            background-color: var(--hover-color);
        }

        .usuario-sesion-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cerrar-sesion-btn {
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

        .cerrar-sesion-btn:hover {
            background-color: #c0392b;
        }

        .cabecera {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .cabecera-titulo {
            font-size: 28px;
            color: var(--primary-color);
            margin: 0;
        }

        .formulario-busqueda {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .formulario-busqueda input, .search-form select {
            margin-right: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .formulario-busqueda button {
            padding: 8px 15px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .formulario-busqueda button:hover {
            background-color: #27ae60;
        }

        .usuarios-table-seccion {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .crear-cliente-btn {
            padding: 10px 20px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .crear-cliente-btn:hover {
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
<div id="menu-lateral">
    <div class="titulo-app">Sistema de inventario</div>
    <ul class="menu">
        <li><a href="#inicio"><span class="icon">🏠</span> Inicio</a></li>
        <li><a href="/Usuarios/Vistas/index.php"><span class="icon">👥</span> Usuarios</a></li>
        <li><a href="#"><span class="icon">🏢</span> Clientes</a></li>
        <li><a href="/Categorias/Vistas/index.php"><span class="icon">📁</span> Categorías</a></li>
        <li><a href="/Materiales/Vistas/index.php"><span class="icon">📦</span> Materiales</a></li>
        <li><a href="/Entradas/Vistas/index.php"><span class="icon">⬇️</span> Entradas</a></li>
        <li><a href="/Salidas/Vistas/index.php"><span class="icon">⬆️</span> Salidas</a></li>
    </ul>
    <div class="usuario-sesion-info">
        <p id="usuario">Usuario</p>
        <button class="cerrar-sesion-btn">Cerrar Sesión</button>
    </div>
</div>
<div id="contenido">
    <div class="cabecera">
        <h1 class="cabecera-titulo">Clientes</h1>
        <a class="crear-cliente-btn" href="crear.php">Crear nuevo cliente</a>
    </div>
    <form class="formulario-busqueda">
        <h3>Busqueda de clientes</h3>
        <hr>
        <input type="text" placeholder="Nombre">
        <select>
            <option value="">Tipo de identificación</option>
            <option value="cedula">Cédula</option>
            <option value="pasaporte">Pasaporte</option>
        </select>
        <input type="text" placeholder="Número de identificación">
        <select>
            <option value="">Estado</option>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>
        <button type="submit">Buscar</button>
    </form>
    <div class="usuarios-table-seccion">
        <h3>Listado de clientes</h3>
        <hr/>
        <table id="clientes-table" class="display nowrap" style="width:80%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo de identificación</th>
                <th>Número de identificación</th>
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