document.addEventListener('DOMContentLoaded', () => {
  const campoCategoria = document.getElementById('categoria_id');

  document.getElementById('codigo').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('nombre').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('descripcion').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('marca').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('presentacion').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('precio').addEventListener('blur', dosDecimales);

  // al editar se deben traer todas las categorias para que no desaparezcan las categorias inactivas
  const ruta = window.location.pathname;
  const estaEditando = ruta.includes('editar.php');
  const filtroCategoriasActivas = estaEditando ?  '' : '&estado=activo';

  fetch(`/vanilla-inventario/Controllers/categorias/GetCategoriasController.php?length=1000&start=0${filtroCategoriasActivas}`, {
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

const dosDecimales = (event) => {
  const campo = event.target;
  const valor = parseFloat(campo.value);

  campo.value = valor.toFixed(2);
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
  const codigo = formulario.codigo.value;
  const nombre = formulario.nombre.value;
  const descripcion = formulario.descripcion.value;
  const marca = formulario.marca.value;
  const categoria_id = formulario.categoria_id.value;
  const unidad = formulario.unidad.value;
  const presentacion = formulario.presentacion.value;
  const estado = formulario.estado.value;
  const precio = formulario.precio.value;
  const stockMinimo = formulario.stock_minimo.value;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Materiales/CrearMaterialController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      codigo,
      nombre,
      descripcion,
      marca,
      categoria_id,
      unidad,
      presentacion,
      estado,
      precio,
      stockMinimo,
      usuarioSesion: usuarioSesion.id
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
  const continuarEdicion = confirm('¿Está seguro de editar este material?');

  if (continuarEdicion == false) {
    return;
  }

  const codigo = formulario.codigo.value;
  const nombre = formulario.nombre.value;
  const descripcion = formulario.descripcion.value;
  const marca = formulario.marca.value;
  const categoria_id = formulario.categoria_id.value;
  const unidad = formulario.unidad.value;
  const presentacion = formulario.presentacion.value;
  const estado = formulario.estado.value;
  const precio = formulario.precio.value;
  const stockMinimo = formulario.stock_minimo.value;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Materiales/EditarMaterialController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id,
      codigo,
      nombre,
      descripcion,
      marca,
      categoria_id,
      unidad,
      presentacion,
      estado,
      precio,
      stockMinimo,
      usuarioSesion: usuarioSesion.id
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