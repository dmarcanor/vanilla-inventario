document.addEventListener('DOMContentLoaded', () => {
  const campoCategoria = document.getElementById('categoria_id');

  document.getElementById('nombre').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('descripcion').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('marca').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('presentacion').addEventListener('blur', primeraLetraMayuscula);

  fetch('/vanilla-inventario/Controllers/categorias/GetCategoriasController.php?length=1000&start=0&estado=activo', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const categorias = json.data;

      categorias.forEach(categoria => {
        const option = document.createElement('option');
        option.value = categoria.id;
        option.text = categoria.nombre;

        campoCategoria.appendChild(option);
      });
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
});

const primeraLetraMayuscula = (event) => {
  const campo = event.target;
  const valor = campo.value;

  campo.value = valor.charAt(0).toUpperCase() + valor.slice(1);
}

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
  const presentacion = formulario.presentacion.value;
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
      presentacion,
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
  const presentacion = formulario.presentacion.value;
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
      presentacion,
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