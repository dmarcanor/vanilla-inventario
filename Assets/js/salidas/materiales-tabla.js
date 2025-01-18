let lineas = [
  {
    salidaId: '',
    materialId: '',
    cantidad: 0,
    precio: 0,
    unidad: '',
    stockActual: 0,
    stockPosterior: 0
  }
];
let materialesEnBaseDeDatos = [];

document.addEventListener('DOMContentLoaded', () => {
  const ruta = window.location.pathname;
  const estaEditando = ruta.includes('editar.php');
  const filtroMaterialesActivas = estaEditando ?  '' : '&estado=activo';

  fetch(`/vanilla-inventario/Controllers/Materiales/GetMaterialesController.php?length=1000&start=0${filtroMaterialesActivas}`)
    .then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      materialesEnBaseDeDatos = json.data;
    })
    .catch((mensaje) => {
      alert(mensaje);
    });

  setTimeout(() => {
    renderTabla();
  }, 500);
});

// Elementos del DOM
const tbody = document.querySelector("#salidas-items tbody");
const addRowButton = document.querySelector("#addRow");

// Función para renderizar la tabla
function renderTabla() {
  tbody.innerHTML = ""; // Limpiar el tbody antes de renderizar

  lineas.forEach((linea, index) => {
    const tr = document.createElement("tr");

    // Columna de selección de item
    const itemTd = document.createElement("td");
    const itemSelect = document.createElement("select");

    const seleccioneOption = document.createElement('option');
    seleccioneOption.value = '';
    seleccioneOption.text = 'Seleccione';
    itemSelect.appendChild(seleccioneOption);

    materialesEnBaseDeDatos.forEach(materialEnBaseDeDatos => {
      const option = document.createElement("option");
      option.value = materialEnBaseDeDatos.id;
      option.textContent = `${materialEnBaseDeDatos.nombre} - ${materialEnBaseDeDatos.descripcion} - ${materialEnBaseDeDatos.marca}`;

      if (linea.materialId == materialEnBaseDeDatos.id) {
        option.selected = true;
      }
      itemSelect.appendChild(option);
    });

    // Actualizar unidad cuando cambia el item seleccionado
    itemSelect.addEventListener("change", (e) => actualizarLinea(index, e.target.value));
    itemTd.appendChild(itemSelect);
    tr.appendChild(itemTd);

    // Columna de cantidad
    const cantidadTd = document.createElement("td");
    const cantidadInput = document.createElement("input");
    cantidadInput.type = "number";
    cantidadInput.value = linea.cantidad;
    cantidadInput.min = '0.01';
    cantidadInput.step = '0.01';
    cantidadInput.addEventListener("focus", (e) => vaciarContenidoSiEsCero(index, e.target));
    cantidadInput.addEventListener("keyup", (e) => actualizarCantidad(index, e.target.value));
    cantidadTd.appendChild(cantidadInput);
    tr.appendChild(cantidadTd);

    // Columna de precio
    const precioTd = document.createElement("td");
    const precioInput = document.createElement("input");
    precioInput.type = "number";
    precioInput.value = precioPorMaterialId(linea.materialId);
    precioInput.min = '0.01';
    precioInput.step = '0.01';
    precioInput.disabled = true;
    lineas[index].precio = precioPorMaterialId(linea.materialId); // Actualizar precio en el arreglo
    precioTd.appendChild(precioInput);
    tr.appendChild(precioTd);

    // Columna de unidad
    const unidadTd = document.createElement("td");
    const unidadInput = document.createElement("input");
    unidadInput.type = "text";
    unidadInput.disabled = true;
    unidadInput.value = unidadPorMaterialId(linea.materialId); // Obtener unidad del catálogo
    unidadTd.appendChild(unidadInput);
    tr.appendChild(unidadTd);

    // Columna de stock actual
    if (vista != 'editar') {
      const stockActualTd = document.createElement("td");
      const stockInput = document.createElement("input");
      stockInput.type = "text";
      stockInput.disabled = true;
      stockInput.value = linea.stockActual;
      stockActualTd.appendChild(stockInput);
      tr.appendChild(stockActualTd);
    }


    // Columna de stock posterior
    if (vista != 'editar') {
      const stockPosteriorTd = document.createElement("td");
      const stockPosteriorInput = document.createElement("input");
      stockPosteriorInput.type = "text";
      stockPosteriorInput.disabled = true;
      stockPosteriorInput.value = linea.stockPosterior;
      stockPosteriorTd.appendChild(stockPosteriorInput);
      tr.appendChild(stockPosteriorTd);
    }

    // Columna de acción (- botón)
    const eliminarTd = document.createElement("td");
    const eliminarButton = document.createElement("button");
    eliminarButton.textContent = "-";
    eliminarButton.classList.add("delete-row-btn");
    eliminarButton.addEventListener("click", () => borrarLinea(index)); // Asignar evento para borrar
    eliminarTd.appendChild(eliminarButton);
    tr.appendChild(eliminarTd);

    tbody.appendChild(tr);
  });
}

// Función para agregar una línea
function agregarLinea() {
  const nuevaLinea = {
    salidaId: '',
    materialId: '',
    cantidad: 0,
    precio: 0,
    unidad: '',
    stockActual: 0,
    stockPosterior: 0
  };
  
  lineas.push(nuevaLinea); // Agregar al arreglo
  
  renderTabla(); // Actualizar tabla
}

// Función para borrar una línea
function borrarLinea(index) {
  lineas.splice(index, 1); // Eliminar del arreglo
  
  renderTabla(); // Actualizar tabla
}

const actualizarLinea = (index, materialId) => {
  actualizarUnidad(index, materialId);
  lineas[index].stockActual = stockPorMaterialId(materialId) // Actualizar stock actual
  lineas[index].stockPosterior = stockPorMaterialId(materialId) - lineas[index].cantidad // Actualizar el stock posterior
  renderTabla();
}

// Función para actualizar la unidad según el item seleccionado
function actualizarUnidad(index, materialId) {
  lineas[index].materialId = parseInt(materialId); // Actualizar el materialId en el arreglo
  renderTabla(); // Volver a renderizar para actualizar la unidad
}

// Funciones para vaciar contenido si es cero
function vaciarContenidoSiEsCero(index, campo) {
  if (campo.value == 0) {
    campo.value = "";
  }
}

// Función para actualizar la cantidad
function actualizarCantidad(index, cantidad) {
  lineas[index].cantidad = parseInt(cantidad); // Actualizar cantidad, por defecto 1
  lineas[index].stockActual = stockPorMaterialId(lineas[index].materialId) // Actualizar stock actual
  lineas[index].stockPosterior = parseFloat(stockPorMaterialId(lineas[index].materialId) - parseFloat(lineas[index].cantidad)) // Actualizar el stock posterior
console.log("gg", index, cantidad, lineas);
  setTimeout(() => {
    renderTabla(); // Volver a renderizar para actualizar la unidad
  }, 1000);

}

function actualizarPrecio(index, precio) {
  lineas[index].precio = parseInt(precio) || 1; // Actualizar precio, por defecto 1
}

function formatearPrecio(index, precio) {
  lineas[index].precio = parseFloat(precio).toFixed(2); // Formatear precio a 2 decimales

  renderTabla(); // Actualizar tabla
}

// Obtener la unidad correspondiente al item
function unidadPorMaterialId(materialId) {
  const item = materialesEnBaseDeDatos.find(item => item.id == parseInt(materialId));
  return item ? item.unidad : "";
}

const stockPorMaterialId = (materialId) => {
  const item = materialesEnBaseDeDatos.find(item => item.id == parseInt(materialId));
  return item ? item.stock : 0;
}

const precioPorMaterialId = (materialId) => {
  const item = materialesEnBaseDeDatos.find(item => item.id == parseInt(materialId));
  return item ? item.precio : "";
}

// Eventos
addRowButton.addEventListener("click", agregarLinea);