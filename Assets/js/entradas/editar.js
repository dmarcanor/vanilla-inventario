let materialesEnBaseDatos = [];

document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  buscarMaterialesBaseDatos()
    .then(() => {
      buscarDatosEntrada();
    });
});

const buscarDatosEntrada = () => {
  const queryParams = new URLSearchParams(window.location.search);
  const id = queryParams.get('id');

  fetch(`/vanilla-inventario/Controllers/Entradas/GetEntradaController.php?id=${id}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      // Convertir fecha de dia/mes/anio a anio-mes-dia que es el formato que acepta el input type date
      const [dia, mes, anio] = json.entrada.fechaCreacionSinHora.split('/');
      const fechaCreacionEntrada = `${anio}-${mes}-${dia}`;

      document.getElementById('id').value = id;
      document.getElementById('observacion').value = json.entrada.observacion;
      document.getElementById('numero_entrada').value = json.entrada.numeroEntrada;
      document.getElementById('fecha').value = fechaCreacionEntrada;

      const lineas = json.entrada.lineas;

      cargarTablaItems(lineas);

      setTimeout(() => {
        const inputs = document.querySelectorAll('input');
        const selects = document.querySelectorAll('select');

        inputs.forEach(input => input.disabled = true);
        selects.forEach(select => select.disabled = true);
      }, 800);
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

const cargarTablaItems = (lineas) => {
  const tbody = document.getElementById('entrada-items-body');

  lineas.forEach(linea => {
    const material = materialesEnBaseDatos.find(material => material.id == linea.materialId);

    const tr = document.createElement('tr');
    const tdMaterial = document.createElement('td');
    const tdCantidad = document.createElement('td');
    const tdPrecio = document.createElement('td');
    const tdUnidad = document.createElement('td');

    tdMaterial.textContent = `${material.nombre} - ${material.descripcion} - ${material.marca}`;
    tdCantidad.textContent = linea.cantidad;
    tdPrecio.textContent = linea.precio;
    tdUnidad.textContent = material.unidad;

    tr.appendChild(tdMaterial);
    tr.appendChild(tdCantidad);
    tr.appendChild(tdPrecio);
    tr.appendChild(tdUnidad);

    tbody.appendChild(tr);
  });
}