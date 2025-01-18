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

  actualizarTabla();

  cambiarNombreUsuarioSesion();
});

const actualizarTabla = () => {
  const precioDolar = document.getElementById('precio_dolar').value;

  // Genera las columnas dependiendo de la condición
  let columnas = [
    { title: "ID", data: "id", orderable: false },
    { title: "Código", data: "codigo", orderable: false },
    { title: "Nombre", data: "nombre", orderable: false },
    { title: "Descripción", data: "descripcion", orderable: false },
    { title: "Presentación", data: "presentacion", orderable: false },
    { title: "Marca", data: "marca", orderable: false },
    { title: "Categoría", data: "categoriaNombre", orderable: false },
    {
      title: "Precio",
      data: "precio",
      orderable: false,
      render: (data) => {
        return `$${data}`;
      }
    }
  ];

  if (precioDolar !== '' && precioDolar !== '0') {
    columnas.push({
      title: 'Precio (Bs)',
      data: null,
      orderable: false,
      render: function(data, type, row) {
        return `Bs ${(row.precio * precioDolar).toFixed(2)}`;
      }
    });
  }

  columnas.push(
    { title: "Stock", data: "stock", orderable: false },
    {
      title: "Estado",
      data: "estado",
      orderable: false,
      render: (data) => estadoLabel(data)
    },
    {
      title: "Acciones",
      data: "acciones",
      orderable: false,
      render: (data, type, row) => {
        const accionEstado = row.estado === 'activo' ? 'Inactivar' : 'Activar';
        const accionEstadoEstilo = row.estado === 'activo' ? 'btn btn-danger' : 'btn btn-success';

        return `
            <button class="btn btn-primary" onclick="redireccionarEditar(${row.id})">
                <img src="/vanilla-inventario/Assets/iconos/editar.svg" alt="editar.svg"> Editar
            </button>
            <button class="${accionEstadoEstilo}" onclick="cambiarEstado(${row.id})")>
                <img src="/vanilla-inventario/Assets/iconos/switch.svg" alt="switch.svg"> ${accionEstado}
            </button>
          `;
      }
    }
  );

  // Destruye y reinicia la tabla
  if ($.fn.DataTable.isDataTable('#usuarios-table')) {
    $('#usuarios-table').DataTable().destroy();
    $('#usuarios-table').empty();
  }

  $('#usuarios-table').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    scrollX: true,
    ajax: {
      url: "/vanilla-inventario/Controllers/Materiales/GetMaterialesController.php?ordenCampo=estado", // URL de tu endpoint
      type: "GET", // Método para la petición (GET o POST)
      data: (parametros) => {
        const form = document.getElementById('form');
        let filtroEstado = form.estado.value;
        let filtroStockMinimo = false;

        if (filtroEstado === 'stock_minimo') {
          filtroEstado = '';
          filtroStockMinimo = true;
        }

        parametros.id = form.id.value;
        parametros.codigo = form.codigo.value;
        parametros.nombre = form.nombre.value;
        parametros.descripcion = form.descripcion.value;
        parametros.presentacion = form.presentacion.value;
        parametros.marca = form.marca.value;
        parametros.categoria_id = form.categoria_id.value;
        parametros.unidad = form.unidad.value;
        parametros.estado = filtroEstado; // *
        parametros.fecha_desde = form.fecha_desde.value;
        parametros.fecha_hasta = form.fecha_hasta.value;
        parametros.precio = form.precio.value;
        parametros.stock_minimo = filtroStockMinimo;
      }
    },
    paging: true, // Activa la paginación
    pageLength: 10, // Número de filas por página
    lengthChange: false,
    columns: columnas
  });
}

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

  actualizarTabla();
}

const calcularPrecioBolivar = (event) => {
  event.preventDefault();

  actualizarTabla();
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
    "estado": "activo",
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value,
    "precio": busqueda.precio.value
  };

  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/materiales.php?${queryParams}`);
}