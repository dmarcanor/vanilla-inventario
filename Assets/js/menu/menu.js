document.addEventListener('DOMContentLoaded', () => {
  marcarSeleccionadoMenu();
  cambiarNombreUsuarioSesion();

  if (!esAdmin()) {
    esconderModuloUsuarios();
  }
});

const marcarSeleccionadoMenu = () => {
  const menu = document.getElementById('sidebar');
  const items = menu.querySelectorAll('li');

  items.forEach(item => {
    const id = item.id;
    const idSplit = id.split('menu-')[1].toUpperCase();
    const ruta = window.location.href.toUpperCase();

    if (ruta.includes(idSplit)) {
      item.classList.add('seleccionado');
    }
  });
}


const logout = () => {
  window.localStorage.removeItem('usuario');
  window.location.href = '/vanilla-inventario/Views/Login/index.php';
}

const cambiarNombreUsuarioSesion = () => {
  const sesion = JSON.parse(localStorage.getItem('usuario'));
  const usuarioHTML = document.getElementById('usuario');

  if (!sesion) {
    return;
  }

  usuarioHTML.textContent = `${sesion.nombre} ${sesion.apellido}`;
}

const esconderModuloUsuarios = () => {
  const menuUsuarios = document.getElementById('menu-usuarios');
  const menuHistorialUsuarios = document.getElementById('menu-historial');

  menuUsuarios.hidden = true;
  menuHistorialUsuarios.hidden = true;
}