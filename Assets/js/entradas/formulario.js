document.addEventListener('DOMContentLoaded', () => {
  const campoUsuarioRegistrador = document.getElementById('usuarioId');

  fetch('/vanilla-inventario/Controllers/Usuarios/GetUsuariosController.php?length=1000&start=0', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const usuarios = json.data;

      usuarios.forEach(usuario => {
        const option = document.createElement('option');
        option.value = usuario.id;
        option.text = usuario.nombre;

        campoUsuarioRegistrador.appendChild(option);
      });
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
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
  const descripcion = formulario.descripcion.value;
  const usuario_id = formulario.usuario_id.value;

  fetch('/vanilla-inventario/Controllers/Entradas/CrearEntradaController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      descripcion,
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

const editar = (id, formulario) => {
  const nombre = formulario.nombre.value;
  const descripcion = formulario.descripcion.value;
  const marca = formulario.marca.value;
  const categoria_id = formulario.categoria_id.value;
  const unidad = formulario.unidad.value;
  const peso = formulario.peso.value;
  const precio = formulario.precio.value;

  fetch('/vanilla-inventario/Controllers/Materiales/EditarMaterialController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id,
      nombre,
      descripcion,
      marca,
      categoria_id,
      unidad,
      peso,
      precio,
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Material editado satisfactoriamente.');
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