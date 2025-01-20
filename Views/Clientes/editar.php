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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/datatables.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/menu/menu.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/clientes/editar.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Clientes - Editar</h1>
    </div>
    <form class="form" onsubmit="guardar(event)">
        <div class="form-row">
            <div class="form-group">
                <label for="tipo_identificacion">Tipo de identificación *</label>
                <select name="tipo_identificacion" id="tipo_identificacion" required>
                    <option value="">Seleccione</option>
                    <option value="cedula">Cédula</option>
                    <option value="rif">Rif</option>
                    <option value="pasaporte">Pasaporte</option>
                </select>
            </div>
            <div class="form-group">
                <label for="numero_identificacion">Número de identificación * <button type="button" style="background-color: inherit; font-size: 11pt" class="info-btn" id="numeroIdentificacionInfo">ℹ️</button></label>
                <div class="numero-identificacion-container">
                    <select name="numero_identificacion_letra" id="numero_identificacion_letra" style="width: 25%" hidden>
                        <option value="V" selected>V</option>
                        <option value="E">E</option>
                        <option value=J>J</option>
                        <option value=G>G</option>
                        <option value="P">P</option>
                        <option value="C">C</option>
                    </select>
                    <input type="text" id="numero_identificacion" placeholder="Número de identificación" required>
                    <div class="numero-identificacion-guide" id="numeroIdentificacionGuia">
                        <p>El número de identificación debe cumplir con los siguientes requisitos:</p>
                        <ul>
                            <li>Cédula: Debe contener de 6 a 8 dígios numéricos.</li>
                            <li>Rif:
                                <ul>
                                    <li>Debe iniciar con una letra, que puede ser únicamente V, E, J, G, P o C.</li>
                                    <li>Debe continuar con 8 dígitos numéricos, luego un guión (-) y por último otro dígito numérico.</li>
                                    <li>Ejemplo de un RIF válido: J1234678-9</li>
                                </ul>
                            </li>
                            <li>Pasaporte: Debe contener de 6 a 9 caracteres.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" placeholder="Nombre" maxlength="20" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" placeholder="Apellido" maxlength="20">
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
                <label for="estado">Estado *</label>
                <select name="estado" id="estado" required>
                    <option value="">Seleccione</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="big-form-group">
                <label for="direccion">Dirección *</label>
                <input type="text" id="direccion" placeholder="Dirección" maxlength="20" required>
            </div>
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

        <input type="hidden" id="id" name="id" value="">
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/clientes/editar.js"></script>
<script src="/vanilla-inventario/Assets/js/clientes/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
</body>
</html>