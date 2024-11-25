document.addEventListener('DOMContentLoaded', () => {
  const cambiarNombreUsuarioSesion = () => {
    const sesion = JSON.parse(localStorage.getItem('usuario'));
    const usuarioHTML = document.getElementById('usuario');

    usuarioHTML.textContent = sesion.nombre;
  }

  $('#usuarios-table').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    ajax: {
      url: "/vanilla-inventario/Controllers/Categorias/GetCategoriasController.php", // URL de tu endpoint
      type: "GET", // Método para la petición (GET o POST)
    },
    paging: true, // Activa la paginación
    pageLength: 10, // Número de filas por página
    lengthChange: false,
    columns: [
      { data: "id" },
      { data: "nombre", orderable: false },
      { data: "descripcion", orderable: false },
      { data: "fechaCreacion", orderable: false },
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

          return `
            <button class="btn btn-primary" onclick="redireccionarEditar(${row.id})">Editar</button>
            <button class="btn btn-primary" onclick="cambiarEstado(${row.id})")>${accionEstado}</button>
            <button class="btn btn-danger" onclick="eliminar(${row.id})">Eliminar</button>
          `;
        }
      }
    ]
  });

  cambiarNombreUsuarioSesion();
});

const estadoLabel = (estado) => {
  if (estado === 'activo') {
    return `<span class="badge badge-success">Activo</span>`;
  }

  if (estado === 'inactivo') {
    return `<span class="badge badge-danger">Inactivo</span>`;
  }
}

const cambiarEstado = (id) => {
  fetch(`/vanilla-inventario/Controllers/Categorias/CambiarEstadoCategoriaController.php`, {
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

      alert('Categoría editado satisfactoriamente.');

      const table = $('#usuarios-table').DataTable();
      table.ajax.reload();
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const eliminar = (id) => {
  fetch(`/vanilla-inventario/Controllers/Categorias/EliminarCategoriaController.php`, {
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

      alert('Categoría eliminada satisfactoriamente.');

      const table = $('#usuarios-table').DataTable();
      table.ajax.reload();
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const redireccionarEditar = (id) => {
  window.location.href = `/vanilla-inventario/Views/Categorias/editar.php?id=${id}`;
}

const buscar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const table = $('#usuarios-table').DataTable();

  const parametros = {
    "nombre": busqueda.nombre.value,
    "descripcion": busqueda.descripcion.value,
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value,
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