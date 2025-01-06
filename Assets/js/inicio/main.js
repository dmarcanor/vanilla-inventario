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

document.addEventListener('DOMContentLoaded', () => {
  const contadorUsuarios = document.getElementById('contadorUsuarios');
  const contadorClientes = document.getElementById('contadorClientes');
  const contadorMateriales = document.getElementById('contadorMateriales');
  const contadorCategorias = document.getElementById('contadorCategorias');

  cambiarContadorUsuarios(contadorUsuarios);
  cambiarContadorClientes(contadorClientes);
  cambiarContadorMateriales(contadorMateriales);
  cambiarContadorCategorias(contadorCategorias);
});