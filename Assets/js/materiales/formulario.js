document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  document.getElementById('codigo').addEventListener('blur', primeraLetraMayuscula);

  document.getElementById('nombre').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('nombre').addEventListener('input', soloPermitirLetras);

  document.getElementById('descripcion').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('descripcion').addEventListener('input', soloPermitirLetras);

  document.getElementById('presentacion').addEventListener('input', soloPermitirNumerosYCaracteresAdicionales);

  document.getElementById('precio').addEventListener('blur', dosDecimales);
  document.getElementById('precio_mayor').addEventListener('blur', dosDecimales);

  /* Eventos para la modal de crear marca */
  document.getElementById('nombre-marca').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('nombre-marca').addEventListener('input', soloPermitirLetras);

  document.getElementById('agregar-marca').addEventListener('click', mostrarModalCreacionMarca);
  document.getElementById('cerrar-modal').addEventListener('click', cerrarModalCreacionMarca);
  document.getElementById('marca-form').addEventListener('submit', crearMarca);
  /* Eventos para la modal de crear marca */

  const campoCategoria = document.getElementById('categoria_id');
  const campoMarca = document.getElementById('marca');

  // al editar se deben traer todas las categorias para que no desaparezcan las categorias inactivas
  const ruta = window.location.pathname;
  const estaEditando = ruta.includes('editar.php');
  const filtroCategoriasActivas = estaEditando ?  '' : '&estado=incorporado';

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
      toastr.error(mensaje);
    });

  fetch(`/vanilla-inventario/Controllers/Marcas/GetMarcasController.php`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const marcas = json.data;

      marcas.forEach(marca => {
        const option = document.createElement('option');
        option.value = marca.id;
        option.text = marca.nombre;

        campoMarca.appendChild(option);
      });
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });

  if (!estaEditando) {
    const formulario = document.getElementById('formulario-materiales');
    setTimeout(() => {
      cargarDatosFormulario(formulario, 'Materiales');
    }, 400);
  }
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
  const precioDetal = formulario.precio.value;
  const precioMayor = formulario.precio_mayor.value;
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
      precioDetal,
      precioMayor,
      stockMinimo,
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

      borrarDatosFormulario('Materiales');

      toastr.success('Material creado satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Materiales/index.php';
      }, 1500);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
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
  const precioDetal = formulario.precio.value;
  const precioMayor = formulario.precio_mayor.value;
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
      precioDetal,
      precioMayor,
      stockMinimo,
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

      borrarDatosFormulario('Materiales');

      toastr.success('Material editado satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Materiales/index.php';
      }, 1500);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  borrarDatosFormulario('Materiales');

  window.location.href = '/vanilla-inventario/Views/Materiales/index.php';
}

const mostrarModalCreacionMarca = (e) => {
  e.preventDefault();

  const modal = document.getElementById('modal')
  modal.style.display = 'flex';
}

const cerrarModalCreacionMarca = (e) => {
  e.preventDefault();

  const modal = document.getElementById('modal')
  modal.style.display = 'none';
}

const crearMarca = (e) => {
  e.preventDefault();

  const campoMarca = document.getElementById('marca');
  const modal = document.getElementById('modal')
  const nombre = document.getElementById('nombre-marca').value;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch(`/vanilla-inventario/Controllers/Marcas/CrearMarcaController.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      nombre,
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

      toastr.success('Marca creada satisfactoriamente.');

      const option = document.createElement('option');
      option.value = json.marca.id;
      option.text = json.marca.nombre;
      option.selected = true;

      campoMarca.appendChild(option);

      // Limpiar el formulario y cerrar el modal
      e.target.reset();
      modal.style.display = 'none';
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}