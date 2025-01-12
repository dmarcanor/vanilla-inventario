document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  if (!esAdmin()) {
    document.getElementById('tarjeta_usuario').hidden = true;
  }

  const contadorUsuarios = document.getElementById('contadorUsuarios');
  const contadorClientes = document.getElementById('contadorClientes');
  const contadorMateriales = document.getElementById('contadorMateriales');
  const contadorCategorias = document.getElementById('contadorCategorias');

  cambiarContadorUsuarios(contadorUsuarios);
  cambiarContadorClientes(contadorClientes);
  cambiarContadorMateriales(contadorMateriales);
  cambiarContadorCategorias(contadorCategorias);

  cargarTablaEntradas();
  cargarTablaSalidas();
  cargarTablaMaterialesStockMinimo();
});

const cambiarContadorUsuarios = (contadorUsuarios) => {
  fetch('/vanilla-inventario/Controllers/Usuarios/GetUsuariosController.php?length=100000', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const usuariosTotales = json.data.length;

      contadorUsuarios.textContent = usuariosTotales;
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cambiarContadorClientes = (contadorClientes) => {
  fetch('/vanilla-inventario/Controllers/Clientes/GetClientesController.php?length=100000', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const clientesTotales = json.data.length;

      contadorClientes.textContent = clientesTotales;
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cambiarContadorMateriales = (contadorMateriales) => {
  fetch('/vanilla-inventario/Controllers/Materiales/GetMaterialesController.php?length=100000', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const materialesTotales = json.data.length;

      contadorMateriales.textContent = materialesTotales;
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cambiarContadorCategorias = (contadorCategorias) => {
  fetch('/vanilla-inventario/Controllers/Categorias/GetCategoriasController.php?length=100000', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      const categoriasTotales = json.data.length;

      contadorCategorias.textContent = categoriasTotales;
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cargarTablaEntradas = () => {
  $('#tabla_entradas_recientes').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    ajax: {
      url: "/vanilla-inventario/Controllers/Entradas/GetEntradasController.php?length=5&orden=DESC&limit=5", // URL de tu endpoint
      type: "GET", // Método para la petición (GET o POST)
    },
    paging: true, // Activa la paginación
    pageLength: 5, // Número de filas por página
    lengthChange: false,
    columns: [
      {
        data: "numeroEntrada",
        className: 'text-center align-middle',
        orderable: false,
        render: (data, type, row) => {
          return `<a href="/vanilla-inventario/Views/Entradas/editar.php?id=${row.id}">${row.numeroEntrada}</a>`
        }
      },
      {data: "fechaCreacionSinHora", className: 'text-center align-middle', orderable: false}
    ]
  });
}

const cargarTablaSalidas = () => {
  $('#tabla_salidas_recientes').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    ajax: {
      url: "/vanilla-inventario/Controllers/Salidas/GetSalidasController.php?length=5&orden=DESC&limit=5", // URL de tu endpoint
      type: "GET", // Método para la petición (GET o POST)
    },
    paging: true, // Activa la paginación
    pageLength: 5, // Número de filas por página
    lengthChange: false,
    columns: [
      {
        data: "id",
        className: 'text-center align-middle',
        orderable: false,
        render: (data, type, row) => {
          return `<a href="/vanilla-inventario/Views/Salidas/editar.php?id=${row.id}">${row.id}</a>`
        }
      },
      {data: "fechaCreacionSinHora", className: 'text-center align-middle', orderable: false}
    ]
  });
}

const cargarTablaMaterialesStockMinimo = () => {
  $('#tabla_materiales_stock_minimo').DataTable({
    processing: true, // Muestra un indicador de carga mientras se procesan los datos
    serverSide: true, // Permite el procesamiento en el servidor
    searching: false,
    ajax: {
      url: "/vanilla-inventario/Controllers/Materiales/GetMaterialesController.php?length=10&orden=ASC&stock_minimo=true", // URL de tu endpoint
      type: "GET", // Método para la petición (GET o POST)
    },
    paging: true, // Activa la paginación
    pageLength: 10, // Número de filas por página
    lengthChange: false,
    columns: [
      {
        data: "nombre",
        className: 'text-center align-middle',
        orderable: false,
        render: (data, type, row) => {
          return `<a href="/vanilla-inventario/Views/Materiales/editar.php?id=${row.id}">${row.nombre}</a>`
        }
      },
      {data: "stock", className: 'text-center align-middle', orderable: false},
      {data: "stockMinimo", className: 'text-center align-middle', orderable: false}
    ]
  });
}