<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Inventario</title>
    <link rel="stylesheet" href="/Login/Vistas/styles.css">
</head>
<body>
<div class="login-container">
    <h1>Login</h1>
    <form id="login-form" onsubmit="login(event)">
        <input type="text" id="usuario" name="usuario" placeholder="Usuario" required aria-label="Usuario">
        <input type="password" id="contrasenia" name="contrasenia" placeholder="Contraseña" required aria-label="Contraseña">
        <button type="submit">Iniciar sesión</button>
    </form>
</div>

<script src="auth.js"></script>
</body>
</html>