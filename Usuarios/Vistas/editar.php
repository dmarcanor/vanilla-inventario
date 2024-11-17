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
    <form class="usuario-form" onsubmit="guardar(event)">
        <div class="grupo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
        </div>
        <div class="grupo">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" placeholder="Apellido" required>
        </div>
        <div class="grupo">
            <label for="correo">Cédula</label>
            <input type="text" id="cedula" name="cedula" placeholder="Cédula" required>
        </div>
        <div class="grupo">
            <label for="contrasenia">Contraseña <small>No llene este campo si no quiere editar la contraseña del usuario</small></label>
            <input type="password" id="contrasenia" name="contrasenia" placeholder="Contraseña">
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
            <select id="rol" name="rol" required>
                <option value="">Seleccione</option>
                <option value="admin">Administrador</option>
                <option value="operador">Operador</option>
            </select>
        </div>
        <div class="grupo">
            <label for="estado">Estado</label>
            <select id="estado" name="estado" required>
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
</body>
</html>