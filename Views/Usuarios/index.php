<?php
require_once '../../helpers.php';
try {
    verificarSesion();
} catch (\Exception $exception) {
    header('Location: /vanilla-inventario/Views/Login/index.php');
    exit();
}

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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/usuarios/main.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/compartido/formulario.css">
    <link rel="stylesheet" href="/vanilla-inventario/Assets/js/toastr/build/toastr.min.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php'; ?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Usuarios</h1>
        <div class="module-actions">
            <a class="btn btn-success" href="/vanilla-inventario/Views/Usuarios/crear.php">
                <img src="/vanilla-inventario/Assets/iconos/crear.svg" alt="crear.svg"> Crear nuevo usuario
            </a>
            <button type="button" id="imprimir" class="btn btn-primary" onclick="imprimir(event)">
                <img src="/vanilla-inventario/Assets/iconos/imprimir.svg" alt="imprimir.svg"> Reporte
            </button>
        </div>
    </div>
    <form class="form" onsubmit="buscar(event)">
        <h3>Búsqueda de usuarios</h3>
        <hr>
        <div class="form-row">
            <div class="form-group">
                <label for="cedula">Cédula</label>
                <input type="number" id="cedula" name="cedula" placeholder="Cédula">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre del material">
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" placeholder="Apellido">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="number" id="telefono" name="telefono" placeholder="Teléfono">
            </div>
        </div>
        <div class="form-row">
            <div class="big-form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" placeholder="Dirección">
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol">
                    <option value="">Rol</option>
                    <option value="admin">Administrador</option>
                    <option value="operador">Operador</option>
                </select>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="">Estado</option>
                    <option value="incorporado">Incorporado</option>
                    <option value="desincorporado">Desincorporado</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Nombre de usuario">
            </div>
            <div class="form-group"></div>
            <div class="form-group"></div>
            <div class="form-group"></div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <button type="submit" class="btn btn-success" id="submit">
                    <img src="/vanilla-inventario/Assets/iconos/buscar.svg" alt="buscar.svg"> Buscar
                </button>
                <button type="reset" class="btn btn-secondary" id="limpiar"  onclick="limpiarFormulario()">
                    <img src="/vanilla-inventario/Assets/iconos/limpiar.svg" alt="limpiar.svg"> Limpiar
                </button>
            </div>
        </div>
    </form>
    <div class="usuarios-table-seccion">
        <h3>Listado de usuarios</h3>
        <hr/>
        <table id="usuarios-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th class="dt-center">ID</th>
                <th class="dt-center">Nombre de usuario</th>
                <th class="dt-center">Nombre</th>
                <th class="dt-center">Apellido</th>
                <th class="dt-center">Cédula</th>
                <th class="dt-center">Teléfono</th>
                <th class="dt-center">Dirección</th>
                <th class="dt-center">Rol</th>
                <th class="dt-center">Estado</th>
                <th class="dt-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="/vanilla-inventario/Assets/js/usuarios/main.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
<script src="/vanilla-inventario/Assets/js/datatables.min.js"></script>
<script src="/vanilla-inventario/Assets/js/helpers/main.js"></script>
<script src="/vanilla-inventario/Assets/js/toastr/build/toastr.min.js"></script>
</body>
</html>