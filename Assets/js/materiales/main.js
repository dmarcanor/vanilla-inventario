document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  const campoCategoria = document.getElementById('categoria_id');

  document.getElementById('codigo').addEventListener('blur', primeraLetraMayuscula);

  document.getElementById('nombre').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('nombre').addEventListener('input', soloPermitirLetras);

  document.getElementById('descripcion').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('descripcion').addEventListener('input', soloPermitirLetras);

  document.getElementById('marca').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('marca').addEventListener('input', soloPermitirLetras);

  document.getElementById('presentacion').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('presentacion').addEventListener('input', soloPermitirLetras);

  fetch(`/vanilla-inventario/Controllers/categorias/GetCategoriasController.php?length=1000&start=0&estado=activo`, {
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

  $('#usuarios-table').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    scrollX: true,
    ajax: {
      url: "/vanilla-inventario/Controllers/Materiales/GetMaterialesController.php?ordenCampo=estado", // URL de tu endpoint
      type: "GET", // Método para la petición (GET o POST)
    },
    paging: true, // Activa la paginación
    pageLength: 10, // Número de filas por página
    lengthChange: false,
    columns: [
      { data: "id", orderable: false },
      { data: "codigo", orderable: false },
      { data: "nombre", orderable: false },
      { data: "descripcion", orderable: false },
      { data: "presentacion", orderable: false },
      { data: "marca", orderable: false },
      { data: "categoriaNombre", orderable: false },
      {
        data: "precio",
        orderable: false,
        render: (data) => {
          return `$${data}`;
        }
      },
      { data: "stock", orderable: false },
      {
        data: "estado",
        orderable: false,
        render: (data) => estadoLabel(data)
      },
      {
        data: "acciones",
        orderable: false,
        render: (data, type, row) => {
          const accionEstado = row.estado === 'activo' ? 'Inactivar' : 'Activar';
          const accionEstadoEstilo = row.estado === 'activo' ? 'btn btn-danger' : 'btn btn-success';

          return `
            <button class="btn btn-primary" onclick="redireccionarEditar(${row.id})">Editar</button>
            <button class="${accionEstadoEstilo}" onclick="cambiarEstado(${row.id})")>${accionEstado}</button>
          `;
        }
      }
    ]
  });

  cambiarNombreUsuarioSesion();
});

const estadoLabel = (estado) => {
  if (estado === 'activo') {
    return `<span>Activo</span>`;
  }

  if (estado === 'inactivo') {
    return `<span>Inactivo</span>`;
  }
}

const cambiarEstado = (id) => {
  const confirmacion = confirm('¿Está seguro de cambiar el estado del material?');
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  if (confirmacion == false) {
    return;
  }

  fetch(`/vanilla-inventario/Controllers/Materiales/CambiarEstadoMaterialController.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id,
      usuarioSesion: usuarioSesion.id
    }),
  })
    .then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Material editado satisfactoriamente.');

      const table = $('#usuarios-table').DataTable();
      table.ajax.reload();
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const eliminar = (id) => {
  fetch(`/vanilla-inventario/Controllers/Materiales/EliminarMaterialController.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id
    }),
  })
    .then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Material eliminado satisfactoriamente.');

      const table = $('#usuarios-table').DataTable();
      table.ajax.reload();
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const redireccionarEditar = (id) => {
  window.location.href = `/vanilla-inventario/Views/Materiales/editar.php?id=${id}`;
}

const buscar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const table = $('#usuarios-table').DataTable();

  let filtroEstado = busqueda.estado.value;
  let filtroStockMinimo = false;

  if (filtroEstado === 'stock_minimo') {
    filtroEstado = '';
    filtroStockMinimo = true;
  }

  const parametros = {
    "id": busqueda.id.value,
    "codigo": busqueda.codigo.value,
    "nombre": busqueda.nombre.value,
    "descripcion": busqueda.descripcion.value,
    "presentacion": busqueda.presentacion.value,
    "marca": busqueda.marca.value,
    "categoria_id": busqueda.categoria_id.value,
    "unidad": busqueda.unidad.value,
    "estado": filtroEstado,
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value,
    "precio": busqueda.precio.value,
    "stock_minimo": filtroStockMinimo
  };

  table.settings()[0].ajax.data = (data) => ({...data, ...parametros})

  table.ajax.reload();
}

const limpiarFormulario = () => {
  const table = $('#usuarios-table').DataTable();

  table.settings()[0].ajax.data = (data) => ({...data, ...{}})

  table.ajax.reload();
}

const imprimir = (event) => {
  event.preventDefault();

  const busqueda = document.getElementsByTagName('form')[0];

  const parametros = {
    "id": busqueda.id.value,
    "codigo": busqueda.codigo.value,
    "nombre": busqueda.nombre.value,
    "descripcion": busqueda.descripcion.value,
    "presentacion": busqueda.presentacion.value,
    "marca": busqueda.marca.value,
    "categoria_id": busqueda.categoria_id.value,
    "unidad": busqueda.unidad.value,
    "estado": busqueda.estado.value,
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value,
    "precio": busqueda.precio.value
  };

  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/materiales.php?${queryParams}`);
}