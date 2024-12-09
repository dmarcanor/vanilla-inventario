document.addEventListener('DOMContentLoaded', () => {
  const queryParams = new URLSearchParams(window.location.search);
  const id = queryParams.get('id');

  fetch(`/vanilla-inventario/Controllers/Salidas/GetSalidaController.php?id=${id}`, {
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
      document.getElementById('observacion').value = json.salida.observacion;
      document.getElementById('clienteId').value = json.salida.clienteId;

      lineas = json.salida.lineas;
      vista = 'editar';
    })
    .catch((mensaje) => {
      alert(mensaje);

      // window.location.href = '/vanilla-inventario/Views/Salidas/index.php';
    });
});