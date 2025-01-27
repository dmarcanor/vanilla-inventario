document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  document.getElementById('observacion').addEventListener('blur', primeraLetraMayuscula);

  const formulario = document.getElementById('formulario-entradas');
  cargarDatosFormulario(formulario, 'Entradas');
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
  const numeroEntrada = formulario.numero_entrada.value;
  const usuario = JSON.parse(window.localStorage.getItem('usuario'));
  const usuario_id = usuario.id;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Entradas/CrearEntradaController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      observacion,
      numeroEntrada,
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

      borrarDatosFormulario('Entradas');

      toastr.success('Entrada creada satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Entradas/index.php';
      }, 1500);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  borrarDatosFormulario('Entradas');

  window.location.href = '/vanilla-inventario/Views/Entradas/index.php';
}