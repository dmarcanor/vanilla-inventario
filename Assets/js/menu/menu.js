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

  usuarioHTML.textContent = `${sesion.nombre} ${sesion.apellido}`;
}

const esAdmin = () => {
  const usuario = JSON.parse(window.localStorage.getItem('usuario'));

  return usuario.rol === 'admin';
}

const esconderModuloUsuarios = () => {
  const menuUsuarios = document.getElementById('menu-usuarios');

  menuUsuarios.hidden = true;
}