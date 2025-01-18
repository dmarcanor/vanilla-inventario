document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  const campoMaterial = document.getElementById('material');

  fetch('/vanilla-inventario/Controllers/Materiales/GetMaterialesController.php?estado=activo&length=1000&start=0', {
    method: 'GET',
    headers: {
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
      alert(mensaje);
    });

  $('#usuarios-table').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    scrollX: true,
    ajax: {
      url: "/vanilla-inventario/Controllers/Entradas/GetEntradasController.php", // URL de tu endpoint
      type: "GET", // Método para la petición (GET o POST)
    },
    paging: true, // Activa la paginación
    pageLength: 10, // Número de filas por página
    lengthChange: false,
    columns: [
      { data: "id", orderable: false },
      { data: "numeroEntrada", orderable: false },
      { data: "observacion", orderable: false },
      { data: "fechaCreacionSinHora", orderable: false },
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
    ]
  });
});

const redireccionarEditar = (id) => {
  window.location.href = `/vanilla-inventario/Views/Entradas/editar.php?id=${id}`;
}

const buscar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const table = $('#usuarios-table').DataTable();

  const parametros = {
    "id": busqueda.id.value,
    "numeroEntrada": busqueda.numero_entrada.value,
    "material": busqueda.material.value,
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

const imprimir = (event) => {
  event.preventDefault();

  const busqueda = document.getElementsByTagName('form')[0];

  const parametros = {
    "id": busqueda.id.value,
    "numeroEntrada": busqueda.numero_entrada.value,
    "material": busqueda.material.value,
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value
  };

  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/entradas.php?${queryParams}`);
}