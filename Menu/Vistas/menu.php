<div id="sidebar">
    <div class="app-name">Sistema de inventario</div>
    <ul class="module-list">
        <li id="menu-inicio"><a href="#inicio"><span class="icon">🏠</span> Inicio</a></li>
        <li id="menu-usuarios"><a href="/Usuarios/Vistas/index.php"><span class="icon">👥</span> Usuarios</a></li>
        <li id="menu-clientes"><a href="/Clientes/Vistas/index.php"><span class="icon">🏢</span> Clientes</a></li>
        <li id="menu-categorias"><a href="#categorias"><span class="icon">📁</span> Categorías</a></li>
        <li id="menu-materiales"><a href="#materiales"><span class="icon">📦</span> Materiales</a></li>
        <li id="menu-entradas"><a href="#entradas"><span class="icon">⬇️</span> Entradas</a></li>
        <li id="menu-salidas"><a href="#salidas"><span class="icon">⬆️</span> Salidas</a></li>
    </ul>
    <div class="user-info">
        <p id="usuario">Usuario</p>
        <button class="logout-btn" onclick="logout()">Cerrar Sesión</button>
    </div>
</div>
<script src="/General/Vistas/Menu/menu.js"></script>