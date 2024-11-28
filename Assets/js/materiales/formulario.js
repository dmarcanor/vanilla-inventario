document.addEventListener('DOMContentLoaded', () => {
  const campoPeso = document.getElementById('peso');
  const campoPrecio = document.getElementById('precio');

  campoPeso.addEventListener('blur', () => {
    campoPeso.value = parseFloat(campoPeso.value).toFixed(2);
  });

  campoPrecio.addEventListener('blur', () => {
    campoPrecio.value = parseFloat(campoPrecio.value).toFixed(2);
  })
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
  const marca = formulario.marca.value;
  const categoria_id = formulario.categoria_id.value;
  const unidad = formulario.unidad.value;
  const peso = formulario.peso.value;
  const precio = formulario.precio.value;
  const estado = formulario.estado.value;

  fetch('/vanilla-inventario/Controllers/Materiales/CrearMaterialController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      nombre,
      descripcion,
      marca,
      categoria_id,
      unidad,
      peso,
      precio,
      estado
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Material creado satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Materiales/index.php';
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
  const estado = formulario.estado.value;

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
      estado
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Material editado satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Materiales/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  window.location.href = '/vanilla-inventario/Views/Materiales/index.php';
}