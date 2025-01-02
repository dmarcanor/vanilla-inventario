document.addEventListener('DOMContentLoaded', () => {
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
    },
    paging: true, // Activa la paginación
    pageLength: 10, // Número de filas por página
    lengthChange: false,
    columns: [
      { data: "id" },
      { data: "usuario", orderable: false },
      { data: "tipoAccion", orderable: false },
      { data: "tipoEntidad", orderable: false },
      { data: "entidad", orderable: false },
      { data: "cambio", orderable: false },
      { data: "fecha", orderable: false }
    ]
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