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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/usuarios/editar.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Usuarios - Editar</h1>
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
            <input type="number" id="cedula" name="cedula" placeholder="Cédula" minlength="6" maxlength="8" required>
        </div>
        <div class="grupo">
            <label for="contrasenia">Contraseña <small>No llene este campo si no quiere editar la contraseña del usuario</small></label>
            <input type="password" id="contrasenia" name="contrasenia" placeholder="Contraseña" minlength="8">
        </div>
        <div class="grupo">
            <label for="telefono">Teléfono</label>
            <input type="tel" id="telefono" placeholder="Teléfono" minlength="11" required>
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

<script src="/vanilla-inventario/Assets/js/usuarios/main.js"></script>
<script src="/vanilla-inventario/Assets/js/usuarios/editar.js"></script>
<script src="/vanilla-inventario/Assets/js/usuarios/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
</body>
</html>