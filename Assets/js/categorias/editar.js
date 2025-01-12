document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }


  const queryParams = new URLSearchParams(window.location.search);
  const id = queryParams.get('id');

  fetch(`/vanilla-inventario/Controllers/Categorias/GetCategoriaController.php?id=${id}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      document.getElementById('id').value = id;
      document.getElementById('nombre').value = json.categoria.nombre;
      document.getElementById('descripcion').value = json.categoria.descripcion;
      document.getElementById('estado').value = json.categoria.estado;
    })
    .catch((mensaje) => {
      alert(mensaje);

      window.location.href = '/vanilla-inventario/Views/Categorias/index.php';
    });
});