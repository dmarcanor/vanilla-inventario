document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  document.getElementById('nombre').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('nombre').addEventListener('input', soloPermitirLetras);

  document.getElementById('apellido').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('apellido').addEventListener('input', soloPermitirLetras);

  document.getElementById('direccion').addEventListener('blur', primeraLetraMayuscula);

  $('#usuarios-table').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    scrollX: true,
    ajax: {
      url: "/vanilla-inventario/Controllers/Clientes/GetClientesController.php", // URL de tu endpoint
      type: "GET", // Método para la petición (GET o POST)
      error: function (xhr) {
        // Si el servidor responde con un 401 o un error general
        if (xhr.status === 401) {
          alert('Sesión expirada.');
          window.location.href = '/vanilla-inventario/Views/Login/index.php';
        } else {
          alert('Ocurrió un error al cargar los datos. Por favor, inténtalo de nuevo.');
        }
      }
    },
    paging: true, // Activa la paginación
    pageLength: 10, // Número de filas por página
    lengthChange: false,
    columns: [
      { data: "id", orderable: true },
      { data: "nombre", orderable: true },
      { data: "apellido", orderable: true },
      { data: "tipoIdentificacion", orderable: true },
      { data: "numeroIdentificacion", orderable: true },
      {
        data: "telefono",
        orderable: true,
        render: (data) => {
          return `${formatearTelefono(data)}`;
        }
      },
      { data: "direccion", orderable: true },
      {
        data: "estado",
        orderable: true,
        render: (data) => estadoLabel(data)
      },
      {
        data: "acciones",
        orderable: false,
        render: (data, type, row) => {
          const accionEstado = row.estado === 'incorporado' ? 'Desincorporar' : 'Incorporar';
          const accionEstadoEstilo = row.estado === 'incorporado' ? 'btn btn-success' : 'btn btn-danger';

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
    ],
    order: [[7, 'asc']]
  });
});

const estadoLabel = (estado) => {
  if (estado === 'incorporado') {
    return `<span>Incorporado</span>`;
  }

  if (estado === 'desincorporado') {
    return `<span>Desincorporado</span>`;
  }
}

const cambiarEstado = (id) => {
  const confirmacion = confirm('¿Está seguro de cambiar el estado del cliente?');
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  if (confirmacion == false) {
    return;
  }

  fetch(`/vanilla-inventario/Controllers/Clientes/CambiarEstadoClienteController.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id,
      usuarioSesion: usuarioSesion.id
    }),
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

      toastr.success('Cliente editado satisfactoriamente.');

      const table = $('#usuarios-table').DataTable();
      table.ajax.reload();
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}

const redireccionarEditar = (id) => {
  window.location.href = `/vanilla-inventario/Views/Clientes/editar.php?id=${id}`;
}

const buscar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const table = $('#usuarios-table').DataTable();

  const parametros = {
    "nombre": busqueda.nombre.value,
    "apellido": busqueda.apellido.value,
    "tipo_identificacion": busqueda.tipo_identificacion.value,
    "numero_identificacion": busqueda.numero_identificacion.value,
    "telefono": busqueda.telefono.value,
    "direccion": busqueda.direccion.value,
    "estado": busqueda.estado.value,
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
    "nombre": busqueda.nombre.value,
    "apellido": busqueda.apellido.value,
    "tipo_identificacion": busqueda.tipo_identificacion.value,
    "numero_identificacion": busqueda.numero_identificacion.value,
    "telefono": busqueda.telefono.value,
    "direccion": busqueda.direccion.value,
    "estado": busqueda.estado.value,
  };

  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/clientes.php?${queryParams}`);
}