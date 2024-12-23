document.addEventListener('DOMContentLoaded', () => {
  const campoUsuarioRegistrador = document.getElementById('usuarioId');

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

        campoUsuarioRegistrador.appendChild(option);
      });
    })
    .catch((mensaje) => {
      alert(mensaje);
    });

  $('#usuarios-table').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    ajax: {
      url: "/vanilla-inventario/Controllers/Entradas/GetEntradasController.php", // URL de tu endpoint
      type: "GET", // Método para la petición (GET o POST)
    },
    paging: true, // Activa la paginación
    pageLength: 10, // Número de filas por página
    lengthChange: false,
    columns: [
      { data: "id" },
      { data: "numeroEntrada", orderable: false },
      { data: "observacion", orderable: false },
      { data: "fechaCreacionSinHora", orderable: false },
      {
        data: "acciones",
        orderable: false,
        render: (data, type, row) => {
          let botonEliminar = `<button class="btn btn-danger" onclick="eliminar(${row.id})">Eliminar</button>`;

          if (esAdmin() == false) {
            botonEliminar = '';
          }

          return `
            <button class="btn btn-primary" onclick="redireccionarEditar(${row.id})">Ver</button>
            ${botonEliminar}
          `;
        }
      }
    ]
  });
});

const redireccionarEditar = (id) => {
  window.location.href = `/vanilla-inventario/Views/Entradas/editar.php?id=${id}`;
}

const eliminar = (id) => {
  const confirmar = confirm('¿Está seguro de eliminar este registro?');

  if (confirmar == false) {
    return;
  }

  fetch(`/vanilla-inventario/Controllers/Entradas/EliminarEntradaController.php`, {
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

      alert('Entrada eliminada satisfactoriamente.');

      const table = $('#usuarios-table').DataTable();
      table.ajax.reload();
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const buscar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const table = $('#usuarios-table').DataTable();

  const parametros = {
    "id": busqueda.id.value,
    "numeroEntrada": busqueda.numero_entrada.value,
    "observacion": busqueda.observacion.value,
    "usuarioId": busqueda.usuarioId.value,
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