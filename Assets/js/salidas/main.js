document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  document.getElementById('observacion').addEventListener('blur', primeraLetraMayuscula);

  document.getElementById('fecha_desde').setAttribute('max', fechaActual());
  document.getElementById('fecha_hasta').setAttribute('max', fechaActual());

  const campoUsuarioRegistrador = document.getElementById('usuarioId');
  const campoCliente = document.getElementById('clienteId');
  const campoMaterial = document.getElementById('material');
  const campoCategoria = document.getElementById('categoria');

  fetch('/vanilla-inventario/Controllers/Usuarios/GetUsuariosController.php?estado=incorporado&length=1000&start=0', {
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
        option.text = `${usuario.nombre} ${usuario.apellido}`;

        campoUsuarioRegistrador.appendChild(option);
      });
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });

  fetch('/vanilla-inventario/Controllers/Clientes/GetClientesController.php?incorporado&length=1000&start=0', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const clientes = json.data;

      clientes.forEach(cliente => {
        const option = document.createElement('option');
        option.value = cliente.id;
        option.text = `${cliente.nombre} ${cliente.apellido}`;

        campoCliente.appendChild(option);
      });
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });

  fetch('/vanilla-inventario/Controllers/Materiales/GetMaterialesController.php?estado=incorporado&length=1000&start=0', {
    method: 'GET', headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const materiales = json.data;

      materiales.forEach(material => {
        const option = document.createElement('option');
        option.value = material.id;
        option.text = `${material.nombre} - ${material.descripcion} - ${material.marca}`;

        campoMaterial.appendChild(option);
      });
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });

  fetch('/vanilla-inventario/Controllers/Categorias/GetCategoriasController.php?estado=incorporado&length=1000&start=0', {
    method: 'GET', headers: {
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

  $('#usuarios-table').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    scrollX: true,
    ajax: {
      url: "/vanilla-inventario/Controllers/Salidas/GetSalidasController.php?orden=DESC", // URL de tu endpoint
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
      { data: "clienteFullNombre", orderable: true },
      {
        data: "cantidadMateriales",
        orderable: true,
        render: (data, type, row) => {
          return row.lineas.length;
        }
      },
      { data: "observacion", orderable: true },
      { data: "usuarioFullNombre", orderable: true },
      { data: "fechaCreacion", orderable: true },
      {
        data: "acciones",
        orderable: false,
        render: (data, type, row) => {
          return `
            <button class="btn btn-primary" onclick="redireccionarEditar(${row.id})">
                <img src="/vanilla-inventario/Assets/iconos/ver.svg" alt="ver.svg"> Ver
            </button>
          `;
        }
      }
    ],
    order: [[0, 'desc']]
  });

  cambiarNombreUsuarioSesion();
});

const redireccionarEditar = (id) => {
  window.location.href = `/vanilla-inventario/Views/Salidas/editar.php?id=${id}`;
}

const buscar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const table = $('#usuarios-table').DataTable();

  const parametros = {
    "id": busqueda.id.value,
    "observacion": busqueda.observacion.value,
    "usuarioId": busqueda.usuarioId.value,
    "clienteId": busqueda.clienteId.value,
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value,
    "material": busqueda.material.value,
    "categoria": busqueda.categoria.value
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
    "observacion": busqueda.observacion.value,
    "usuarioId": busqueda.usuarioId.value,
    "clienteId": busqueda.clienteId.value,
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value,
    "material": busqueda.material.value,
    "categoria": busqueda.categoria.value
  };

  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/salidas.php?${queryParams}`);
}