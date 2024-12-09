document.addEventListener('DOMContentLoaded', () => {
  const campoObservacion = document.getElementById('observacion');

  campoObservacion.addEventListener('input', (event) => {
    const observacion = event.target.value;

    if (!observacion) {
      return "";
    }

    campoObservacion.value = observacion.charAt(0).toUpperCase() + observacion.slice(1);
  });
});

const guardar = (event) => {
  event.preventDefault();

  const formulario = event.target;
  const id = formulario.id ? formulario.id.value : '';


  if (!id) {
    crear(formulario);
  }
}

const crear = (formulario) => {
  const observacion = formulario.observacion.value;
  const usuario = JSON.parse(window.localStorage.getItem('usuario'));
  const usuario_id = usuario.id;

  fetch('/vanilla-inventario/Controllers/Entradas/CrearEntradaController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      observacion,
      usuario_id,
      lineas
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Entrada creada satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Entradas/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  window.location.href = '/vanilla-inventario/Views/Entradas/index.php';
}