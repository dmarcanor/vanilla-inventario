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
      document.getElementById('observacion').value = json.entrada.observacion;
      document.getElementById('numero_entrada').value = json.entrada.numeroEntrada;

      lineas = json.entrada.lineas;
    })
    .catch((mensaje) => {
      alert(mensaje);

      // window.location.href = '/vanilla-inventario/Views/Entradas/index.php';
    });
});