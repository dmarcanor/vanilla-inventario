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
    <link rel="stylesheet" href="/vanilla-inventario/Assets/css/salidas/crear.css">
</head>
<body>

<?php require_once __DIR__ . '/../../Views/Menu/menu.php';?>

<div id="content">
    <div class="module-header">
        <h1 class="module-title">Salidas - Crear</h1>
    </div>
    <form class="crear-usuario-form" onsubmit="guardar(event)">
        <div class="grupo">
            <label for="cliente_id">Cliente</label>
            <select name="cliente_id" id="cliente_id" required>
                <option value="">Seleccione</option>
            </select>
        </div>
        <div class="grupo">
            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" placeholder="Descripción" required>
        </div>
        <div class="grupo">
            <label for="usuario_id">Usuario registrador</label>
            <select name="usuario_id" id="usuario_id" required>
                <option value="">Seleccione</option>
            </select>
        </div>
        <div class="grupo">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" disabled required>
                <option value="aprobado">Aprobado</option>
            </select>
        </div>

        <table id="salidas-items">
            <thead>
            <tr>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Unidad</th>
                <th>Stock actual</th>
                <th>Stock posterior</th>
                <th>
                    <button id="addRow">+</button>
                </th>
            </tr>
            </thead>
            <tbody id="salida-items-body">
            </tbody>
        </table>

        <div class="grupo-botones">
            <button type="submit">Guardar</button>
            <button onclick="cancelar(event)">Cancelar</button>
        </div>
    </form>
</div>

<script src="/vanilla-inventario/Assets/js/salidas/main.js"></script>
<script src="/vanilla-inventario/Assets/js/salidas/formulario.js"></script>
<script src="/vanilla-inventario/Assets/js/salidas/materiales-tabla.js"></script>
<script src="/vanilla-inventario/Assets/js/menu/menu.js"></script>
</body>
</html>