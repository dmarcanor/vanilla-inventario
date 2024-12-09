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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Usuarios - Editar</h1>
    </div>
    <form class="form" onsubmit="guardar(event)">
        <div class="form-row">
            <div class="form-group">
                <label for="cedula">Cédula</label>
                <input type="number" id="cedula" placeholder="Cédula" min="1000000" max="99999999" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Nombre" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" placeholder="Apellido" required>
            </div>
            <div class="form-group">
                <label for="contrasenia">Contraseña</label>
                <input type="password" id="contrasenia" placeholder="Contraseña" minlength="8" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" placeholder="Teléfono" minlength="11" maxlength="11"
                       pattern="^(0424|0414|0416|0426|0412)\d{7}$"
                       required
                       oninvalid="this.setCustomValidity('Ingrese un número de teléfono válido. Debe iniciar con 0412, 0414, 0416, 0424 o 0426 y tener 11 dígitos.')"
                >
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select name="rol" id="rol" required>
                    <option value="">Seleccione</option>
                    <option value="admin">Administrador</option>
                    <option value="operador">Operador</option>
                </select>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" required>
                    <option value="">Seleccione</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="big-form-group">
                <label for="direccion">Dirección</label>
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

<script src="/vanilla-inventario/Assets/js/usuarios/main.js"></script>
<script src="/vanilla-inventario/Assets/js/usuarios/editar.js"></script>
<script src="/vanilla-inventario/Assets/js/usuarios/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
</body>
</html>