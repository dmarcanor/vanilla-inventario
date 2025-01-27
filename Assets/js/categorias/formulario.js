document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  const ruta = window.location.pathname;
  const estaEditando = ruta.includes('editar.php');

  if (!estaEditando) {
    const formulario = document.getElementById('formulario-categorias');
    cargarDatosFormulario(formulario, 'Categorias');
  }

  document.getElementById('nombre').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('nombre').addEventListener('input', soloPermitirLetras);

  document.getElementById('descripcion').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('descripcion').addEventListener('input', soloPermitirLetras);
});

const guardar = (event) => {
  event.preventDefault();

  const formulario = event.target;
  const id = formulario.id ? formulario.id.value : '';

  if (!id) {
    crear(formulario);
    return
  }

  editar(id, formulario);
}

const crear = (formulario) => {
  const nombre = formulario.nombre.value;
  const descripcion = formulario.descripcion.value;
  const estado = formulario.estado.value;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Categorias/CrearCategoriaController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      nombre,
      descripcion,
      estado,
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

      borrarDatosFormulario('Categorias');

      toastr.success('Categoría creada satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Categorias/index.php';
      }, 1500);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}

const editar = (id, formulario) => {
  const continuarEdicion = confirm('¿Está seguro de editar esta categoría?');

  if (continuarEdicion == false) {
    return;
  }

  const nombre = formulario.nombre.value;
  const descripcion = formulario.descripcion.value;
  const estado = formulario.estado.value;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Categorias/EditarCategoriaController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id,
      nombre,
      descripcion,
      estado,
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

      borrarDatosFormulario('Categorias');

      toastr.success('Categoría editada satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Categorias/index.php';
      }, 1500);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  borrarDatosFormulario('Categorias');

  window.location.href = '/vanilla-inventario/Views/Categorias/index.php';
}