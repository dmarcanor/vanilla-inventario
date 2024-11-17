// const DataTable = require( 'datatables.net' );

document.addEventListener('DOMContentLoaded', () => {
  const cambiarNombreUsuarioSesion = () => {
    const sesion = JSON.parse(localStorage.getItem('usuario'));
    const usuarioHTML = document.getElementById('usuario');

    usuarioHTML.textContent = sesion.nombre;
  }

  $('#usuarios-table').DataTable({
    "processing": true, // Muestra un indicador de carga mientras se procesan los datos
    "serverSide": true, // Permite el procesamiento en el servidor
    "searching": false,
    "ajax": {
      "url": "/Usuarios/Controladores/GetUsuariosController.php", // URL de tu endpoint
      "type": "GET" // Método para la petición (GET o POST)
    },
    "paging": true, // Activa la paginación
    "pageLength": 10, // Número de filas por página
    "columns": [
      { "data": "id" },
      { "data": "nombre", "ordenable": false },
      { "data": "correo", "ordenable": false },
      {
        "data": "rol" ,
        "ordenable": false,
        "render": (data) => data === 'admin' ? 'Administrador' : 'Operador'
      },
      {
        "data": "estado",
        "ordenable": false,
        "render": (data) => data === 'activo' ? 'Activo' : 'Inactivoxxx'
      },
      {
        "data": "acciones",
        "ordenable": false,
        "render": (data, type, row) => {
          return `
            <button class="btn btn-primary" onclick="redireccionar_editar(${row.id})">Editar</button>
            <button class="btn btn-primary">${row.estado === 'activo' ? 'Inactivar' : 'Activar'}</button>
            <button class="btn btn-danger">Eliminar</button>
          `;
        }
      }
    ]
  });

  cambiarNombreUsuarioSesion();
});

const redireccionar_editar = (id) => {
  window.location.href = `/Usuarios/Vistas/editar.php?id=${id}`;
}