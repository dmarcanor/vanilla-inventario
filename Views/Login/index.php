<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Inventario</title>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/login/login.css">
</head>
<body>
<div class="login-container">
    <div class="nombre-empresa">Comercializadora GYS C.A.</div>
    <hr class="separador">
    <h1>Iniciar Sesión</h1>
    <form id="login-form" onsubmit="login(event)">
        <div class="form-group">
            <label for="usuario">Nombre de usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Usuario" required aria-label="Usuario">
        </div>
        <div class="form-group">
            <label for="contrasenia">Contraseña</label>
            <input type="password" id="contrasenia" name="contrasenia" placeholder="Contraseña" required>
        </div>
        <button type="submit">Iniciar sesión</button>
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/login/auth.js"></script>
</body>
</html>