document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

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

      setTimeout(() => {
        const inputs = document.querySelectorAll('input');
        const selects = document.querySelectorAll('select');
        const botonesEliminarLinea = document.querySelectorAll('.delete-row-btn');
        const botonAgregarLina = document.getElementById('addRow');

        inputs.forEach(input => input.disabled = true);
        selects.forEach(select => select.disabled = true);
        botonesEliminarLinea.forEach(botonEliminarLinea => botonEliminarLinea.hidden = true);
        botonAgregarLina.hidden = true;
      }, 1000);
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
});