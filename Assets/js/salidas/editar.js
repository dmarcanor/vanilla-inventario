let materialesEnBaseDatos = [];

document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  buscarMaterialesBaseDatos()
    .then(() => {
      buscarDatosSalida();
    });
});

const buscarDatosSalida = () => {
  const queryParams = new URLSearchParams(window.location.search);
  const id = queryParams.get('id');

  fetch(`/vanilla-inventario/Controllers/Salidas/GetSalidaController.php?id=${id}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      document.getElementById('id').value = id;
      document.getElementById('observacion').value = json.salida.observacion;
      document.getElementById('clienteId').value = json.salida.clienteId;

      const lineas = json.salida.lineas;

      cargarTablaItems(json.salida, lineas);

      setTimeout(() => {
        const inputs = document.querySelectorAll('input');
        const selects = document.querySelectorAll('select');

        inputs.forEach(input => input.disabled = true);
        selects.forEach(select => select.disabled = true);
      }, 1000);
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const buscarMaterialesBaseDatos = () => {
  return fetch(`/vanilla-inventario/Controllers/Materiales/GetMaterialesController.php?length=1000&start=0`)
    .then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      materialesEnBaseDatos = json.data;
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cargarTablaItems = (salida, lineas) => {
  const tbody = document.getElementById('salida-items-body');
  const tfooter = document.getElementById('salida-items-footer');
  const footerPrecioTotal = document.getElementById('footer-precio=total');

  lineas.forEach(linea => {
    const material = materialesEnBaseDatos.find(material => material.id == linea.materialId);

    const tr = document.createElement('tr');
    const tdMaterial = document.createElement('td');
    const tdCantidad = document.createElement('td');
    const tdPrecio = document.createElement('td');
    const tdTipoPrecio = document.createElement('td');
    const tdUnidad = document.createElement('td');
    const tdPrecioTotal = document.createElement('td');

    tdMaterial.textContent = `${material.nombre} - ${material.descripcion} - ${material.marca}`;
    tdCantidad.textContent = linea.cantidad;
    tdTipoPrecio.textContent = tipoPrecio(linea.tipoPrecio);
    tdPrecio.textContent = linea.precio;
    tdUnidad.textContent = material.unidad;
    tdPrecioTotal.textContent = linea.precioTotal;

    tr.appendChild(tdMaterial);
    tr.appendChild(tdCantidad);
    tr.appendChild(tdTipoPrecio);
    tr.appendChild(tdPrecio);
    tr.appendChild(tdUnidad);
    tr.appendChild(tdPrecioTotal);

    tbody.appendChild(tr);
  });

  footerPrecioTotal.value = salida.precioTotal;
}

const tipoPrecio = (tipoPrecio) => {
  if (tipoPrecio == 'precio_detal') {
    return 'Precio al detal';
  }

  if (tipoPrecio == 'precio_mayor') {
    return 'Precio al mayor';
  }

  return ';'
}