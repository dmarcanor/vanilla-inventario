document.addEventListener('DOMContentLoaded', () => {
  const queryParams = new URLSearchParams(window.location.search);
  const id = queryParams.get('id');

  fetch(`/vanilla-inventario/Controllers/Entradas/GetEntradaController.php?id=${id}`, {
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
      document.getElementById('descripcion').value = json.entrada.descripcion;
      document.getElementById('usuarioId').value = json.entrada.usuarioId;

      lineas = json.entrada.lineas;
    })
    .catch((mensaje) => {
      alert(mensaje);

      // window.location.href = '/vanilla-inventario/Views/Entradas/index.php';
    });
});