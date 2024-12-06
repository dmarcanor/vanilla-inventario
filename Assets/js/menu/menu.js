document.addEventListener('DOMContentLoaded', () => {
  marcarSeleccionadoMenu();
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