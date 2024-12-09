document.addEventListener('DOMContentLoaded', () => {
  const campoCliente = document.getElementById('clienteId');

  fetch('/vanilla-inventario/Controllers/Clientes/GetClientesController.php?length=1000&start=0', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const clientes = json.data;

      clientes.forEach(cliente => {
        const option = document.createElement('option');
        option.value = cliente.id;
        option.text = `${cliente.nombre} ${cliente.apellido}`;

        campoCliente.appendChild(option);
      });
    })
    .catch((mensaje) => {
      alert(mensaje);
    });

  vista = 'crear';
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
  const clienteId = formulario.clienteId.value;
  const observacion = formulario.observacion.value;
  const usuario = JSON.parse(window.localStorage.getItem('usuario'));
  const usuario_id = usuario.id;

  fetch('/vanilla-inventario/Controllers/Salidas/CrearSalidaController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      clienteId,
      observacion,
      usuario_id,
      lineas
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Salida creada satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Salidas/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}


const cancelar = (event) => {
  event.preventDefault();

  window.location.href = '/vanilla-inventario/Views/Salidas/index.php';
}