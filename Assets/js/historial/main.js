document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  document.getElementById('fecha_desde').setAttribute('max', fechaActual());
  document.getElementById('fecha_hasta').setAttribute('max', fechaActual());

  const campoUsuarios = document.getElementById('usuarios');

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
        option.text = `${usuario.nombre} ${usuario.apellido}`;

        campoUsuarios.appendChild(option);
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
      url: "/vanilla-inventario/Controllers/Historial/GetHistorialController.php", // URL de tu endpoint
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
      { data: "usuario", orderable: true },
      { data: "tipoAccion", orderable: true },
      { data: "tipoEntidad", orderable: true },
      { data: "entidad", orderable: false },
      { data: "cambio", orderable: true },
      { data: "fecha", orderable: true }
    ],
    order: [[0, 'asc']]
  });

  cambiarNombreUsuarioSesion();
});

const buscar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const table = $('#usuarios-table').DataTable();
  //
  // const fechaDesde =  new Date(busqueda.fecha_desde.value);
  // fechaDesde.setHours(0, 0, 0, 0);
  //
  // const fechaHasta =  new Date(busqueda.fecha_hasta.value);
  // fechaDesde.setHours(23, 59, 59, 999);

  const parametros = {
    "usuarios": busqueda.usuarios.value,
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value
  };

  table.settings()[0].ajax.data = (data) => ({...data, ...parametros})

  table.ajax.reload();
}

const limpiarFormulario = () => {
  const table = $('#usuarios-table').DataTable();

  table.settings()[0].ajax.data = (data) => ({...data, ...{}})

  table.ajax.reload();
}