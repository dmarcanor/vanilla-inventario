document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  document.getElementById('observacion').addEventListener('blur', primeraLetraMayuscula);

  const campoCliente = document.getElementById('clienteId');

  fetch('/vanilla-inventario/Controllers/Clientes/GetClientesController.php?estado=incorporado&length=1000&start=0', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  })
    .then((response) => {
      if (response.status === 401) {
        // Manejar sesión expirada
        window.location.href = '/vanilla-inventario/Views/Login/index.php';
        return Promise.reject('Sesión expirada');
      }

      return response.json()
    })
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
      toastr.error(mensaje);
    });

  const formulario = document.getElementById('formulario-salidas');
  setTimeout(() => {
    cargarDatosFormulario(formulario, 'Salidas');
  }, 500);

  vista = 'crear';
});

const guardar = (event) => {
  event.preventDefault();

  const formulario = event.target;
  const id = formulario.id ? formulario.id.value : '';

  if (lineas.some(linea => linea.stockPosterior < 0)) {
    toastr.error('La cantidad de salida no puede ser mayor al stock actual.');
    return
  }

  if (!id) {
    crear(formulario);
  }
}

const crear = (formulario) => {
  const clienteId = formulario.clienteId.value;
  const observacion = formulario.observacion.value;
  const usuario = JSON.parse(window.localStorage.getItem('usuario'));
  const usuario_id = usuario.id;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Salidas/CrearSalidaController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      clienteId,
      observacion,
      usuario_id,
      lineas,
      usuarioSesion: usuarioSesion.id
    })
  })
    .then((response) => {
      if (response.status === 401) {
        // Manejar sesión expirada
        window.location.href = '/vanilla-inventario/Views/Login/index.php';
        return Promise.reject('Sesión expirada');
      }

      return response.json()
    })
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      borrarDatosFormulario('Salidas');

      if (json.mensaje) {
        toastr.warning(json.mensaje);
      }

      toastr.success('Salida creada satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Salidas/index.php';
      }, 2000);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}


const cancelar = (event) => {
  event.preventDefault();

  borrarDatosFormulario('Salidas');

  window.location.href = '/vanilla-inventario/Views/Salidas/index.php';
}