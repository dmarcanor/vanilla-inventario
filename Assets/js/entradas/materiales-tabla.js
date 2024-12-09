let lineas = [
  {
    entradaId: '',
    materialId: '',
    cantidad: 0,
    precio: 0,
    unidad: ''
  }
];
let materialesEnBaseDeDatos = [];

document.addEventListener('DOMContentLoaded', () => {
  fetch('/vanilla-inventario/Controllers/Materiales/GetMaterialesController.php?length=1000&start=0')
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
const tbody = document.querySelector("#entrada-items tbody");
const addRowButton = document.querySelector("#addRow");

// Función para renderizar la tabla
function renderTabla() {
  tbody.innerHTML = ""; // Limpiar el tbody antes de renderizar

  if (lineas.length == 0) {
    lineas = [
      {
        entradaId: '',
        materialId: '',
        cantidad: 0,
        precio: 0,
        unidad: ''
      }
    ];
  }

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
      option.textContent = `${materialEnBaseDeDatos.nombre} - ${materialEnBaseDeDatos.presentacion}`;

      if (linea.materialId == materialEnBaseDeDatos.id) {
        option.selected = true;
      }
      itemSelect.appendChild(option);
    });

    // Actualizar unidad cuando cambia el item seleccionado
    itemSelect.addEventListener("change", (e) => actualizarUnidad(index, e.target.value));
    itemTd.appendChild(itemSelect);
    tr.appendChild(itemTd);

    // Columna de cantidad
    const cantidadTd = document.createElement("td");
    const cantidadInput = document.createElement("input");
    cantidadInput.type = "number";
    cantidadInput.value = linea.cantidad;
    cantidadInput.min = '0.01';
    cantidadInput.step = '0.01';
    cantidadInput.addEventListener("input", (e) => actualizarCantidad(index, e.target.value));
    cantidadInput.addEventListener("blur", (e) => formatearCantidad(index, e.target.value));
    cantidadTd.appendChild(cantidadInput);
    tr.appendChild(cantidadTd);

    // Columna de precio
    const precioTd = document.createElement("td");
    const precioInput = document.createElement("input");
    precioInput.type = "number";
    precioInput.value = linea.precio;
    precioInput.min = '0.01';
    precioInput.step = '0.01';
    precioInput.addEventListener("input", (e) => actualizarPrecio(index, e.target.value));
    precioInput.addEventListener("blur", (e) => formatearPrecio(index, e.target.value));
    precioTd.appendChild(precioInput);
    tr.appendChild(precioTd);

    // Columna de unidad
    const unidadTd = document.createElement("td");
    unidadTd.textContent = unidadPorMaterialId(linea.materialId); // Obtener unidad del catálogo
    tr.appendChild(unidadTd);

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
    entradaId: '',
    materialId: '',
    cantidad: 0,
    precio: 0,
    unidad: ''
  };

  lineas.push(nuevaLinea); // Agregar al arreglo

  renderTabla(); // Actualizar tabla
}

// Función para borrar una línea
function borrarLinea(index) {
  lineas.splice(index, 1); // Eliminar del arreglo

  renderTabla(); // Actualizar tabla
}

// Función para actualizar la unidad según el item seleccionado
function actualizarUnidad(index, materialId) {
  lineas[index].materialId = parseInt(materialId); // Actualizar el materialId en el arreglo
  renderTabla(); // Volver a renderizar para actualizar la unidad
}

// Función para actualizar la cantidad
function actualizarCantidad(index, cantidad) {
  lineas[index].cantidad = parseInt(cantidad) || 1; // Actualizar cantidad, por defecto 1
}

function formatearCantidad(index, cantidad) {
  console.log("cantidad, blur", cantidad, parseInt(cantidad))
  lineas[index].cantidad = parseInt(cantidad); // Formatear cantidad

  renderTabla(); // Actualizar tabla
}

function actualizarPrecio(index, precio) {
  lineas[index].precio = parseFloat(precio) || 1; // Actualizar cantidad, por defecto 1
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

// Eventos
addRowButton.addEventListener("click", agregarLinea);