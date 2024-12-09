document.addEventListener('DOMContentLoaded', () => {
  const queryParams = new URLSearchParams(window.location.search);
  const id = queryParams.get('id');

  fetch(`/vanilla-inventario/Controllers/Materiales/GetMaterialController.php?id=${id}`, {
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
      document.getElementById('nombre').value = json.material.nombre;
      document.getElementById('descripcion').value = json.material.descripcion;
      document.getElementById('marca').value = json.material.marca;
      document.getElementById('categoria_id').value = json.material.categoriaId;
      document.getElementById('unidad').value = json.material.unidad;
      document.getElementById('presentacion').value = json.material.presentacion;
      document.getElementById('stock').value = json.material.stock;
      document.getElementById('estado').value = json.material.estado;
    })
    .catch((mensaje) => {
      alert(mensaje);

      // window.location.href = '/vanilla-inventario/Views/Materiales/index.php';
    });
});