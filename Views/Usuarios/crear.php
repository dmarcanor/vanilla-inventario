<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/usuarios/crear.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php'; ?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Usuarios - Crear</h1>
    </div>
    <form class="crear-usuario-form form" onsubmit="guardar(event)">
        <div class="form-row">
            <div class="form-group">
                <label for="cedula">Cédula *</label>
                <div style="display: flex">
                    <span style="border: ridge">V-</span>
                    <input type="number" id="cedula" placeholder="Cédula" min="1000000" max="99999999" required
                           oninvalid="this.setCustomValidity('La cédula debe tener entre 7 y 8 caracteres.')"
                           oninput="this.setCustomValidity('')">
                </div>
            </div>
            <div class="form-group">
                <label for="nombre_usuario">Nombre de usuario*</label>
                <input type="text" id="nombre_usuario" placeholder="Nombre de usuario"
                       pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$"
                       title="El nombre de usuario debe contener letras y números."
                       maxlength="30"
                       required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" placeholder="Nombre" maxlength="20" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido *</label>
                <input type="text" id="apellido" placeholder="Apellido" maxlength="20" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono *</label>
                <input type="number" id="telefono" placeholder="Teléfono" max="04269999999"
                       required
                       oninvalid="this.setCustomValidity('El número debe iniciar con 0412, 0414, 0416, 0424 o 0426 seguido de 7 dígitos y no puede contener caracteres especiales o letras.')"
                       oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="contrasenia">Contraseña * <button type="button" style="background-color: inherit; font-size: 11pt" class="info-btn" id="contraseniaInfo">ℹ️</button></label>
                <div class="password-container">
                    <input type="password" id="contrasenia" name="contrasenia" placeholder="Contraseña" minlength="8" required>
                    <button type="button" style="background-color: inherit" class="toggle-password" id="mostrarContrasenia">👁️</button>
                    <div class="password-guide" id="contraseniaGuia">
                        <p>La contraseña debe cumplir con los siguientes requisitos:</p>
                        <ul>
                            <li>Al menos 8 caracteres de longitud</li>
                            <li>Al menos una letra mayúscula</li>
                            <li>Al menos una letra minúscula</li>
                            <li>Al menos un número</li>
                            <li>Al menos un carácter especial (ej: !@#$%^&*)</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="form-group password-container">
                <label for="repetir_contrasenia">Repetir contraseña *</label>
                <div class="password-container">
                    <input type="password" id="repetir_contrasenia" placeholder="Repetir contraseña" minlength="8" required>
                    <button type="button" style="background-color: inherit" class="toggle-password" id="mostrarRepetirContrasenia">👁️</button>
                </div>
            </div>
            <div class="form-group">
                <label for="rol">Rol *</label>
                <select name="rol" id="rol" required>
                    <option value="">Seleccione</option>
                    <option value="admin">Administrador</option>
                    <option value="operador">Operador</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="estado">Estado *</label>
                <select name="estado" id="estado" disabled required>
                    <option selected value="activo">Activo</option>
                </select>
            </div>
            <div class="big-form-group">
                <label for="direccion">Dirección *</label>
                <input type="text" id="direccion" placeholder="Dirección" required>
            </div>
            <div class="form-group"></div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <img src="/vanilla-inventario/Assets/iconos/guardar.svg" alt="guardar.svg"> Guardar
                </button>
                <button type="reset" class="btn btn-secondary" onclick="cancelar(event)">
                    <img src="/vanilla-inventario/Assets/iconos/cancelar.svg" alt="cancelar.svg"> Cancelar
                </button>
            </div>
        </div>

        <hr>
        <p>Todos los campos con asterisco (*) son obligatorios.</p>
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/usuarios/main.js"></script>
<script src="/vanilla-inventario/Assets/js/usuarios/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
</body>
</html>