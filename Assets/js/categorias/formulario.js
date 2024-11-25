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

  fetch('/vanilla-inventario/Controllers/Categorias/CrearCategoriaController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      nombre,
      descripcion,
      estado
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Categoría creada satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Categorias/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const editar = (id, formulario) => {
  const nombre = formulario.nombre.value;
  const descripcion = formulario.descripcion.value;
  const estado = formulario.estado.value;

  fetch('/vanilla-inventario/Controllers/Categorias/EditarCategoriaController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id,
      nombre,
      descripcion,
      estado
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Categoría editada satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Categorias/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  window.location.href = '/vanilla-inventario/Views/Categorias/index.php';
}