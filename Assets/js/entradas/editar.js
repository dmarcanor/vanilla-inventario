document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

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

      setTimeout(() => {
        const inputs = document.querySelectorAll('input');
        const selects = document.querySelectorAll('select');
        inputs.forEach(input => input.disabled = true);
        selects.forEach(select => select.disabled = true);
      }, 1000);
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
});