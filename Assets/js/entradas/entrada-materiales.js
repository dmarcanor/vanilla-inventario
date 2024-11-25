let lineas = [];
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
});

// Elementos del DOM
const tbody = document.querySelector("#entrada-items tbody");
const addRowButton = document.querySelector("#addRow");

// Función para renderizar la tabla
function renderTable() {
  tbody.innerHTML = ""; // Limpiar el tbody antes de renderizar
  lineas.forEach((linea, index) => {
    const tr = document.createElement("tr");

    const selectMaterial = document.createElement('select');
    selectMaterial.value = linea.material_id;
    selectMaterial.name = `materiales[${index}][material_id]`;
    selectMaterial.required = true;
    selectMaterial.onchange = (event) => {
      // const materialSeleccionado = event.target.value;
      //
      // if (materialSeleccionado === '') {
      //   return;
      // }
      //
      // const material = materialesEnBaseDeDatos.find((material) => material.id === materialSeleccionado);
      //
      // linea.material_id = material.id;
      // linea.unidad = material.unidad;
      //
      // document.getElementById(`materiales[${index}][material_id]`).value = linea.unidad;
    };

    const seleccioneOption = document.createElement('option');
    seleccioneOption.value = '';
    seleccioneOption.text = 'Seleccione';
    selectMaterial.appendChild(seleccioneOption);

    materialesEnBaseDeDatos.forEach(materialEnBaseDeDatos => {
      const option = document.createElement('option');
      option.value = materialEnBaseDeDatos.id;
      option.text = materialEnBaseDeDatos.nombre;

      selectMaterial.appendChild(option);
    });

    const materialTd = document.createElement('td');
    materialTd.appendChild(selectMaterial);
    tr.appendChild(materialTd);

    const cantidadTd = document.createElement('td');
    const cantidadInput = document.createElement('input');
    cantidadInput.type = 'number';
    cantidadInput.placeholder = 'Cantidad';
    cantidadInput.name = `materiales[${index}][cantidad]`;
    cantidadInput.required = true;
    cantidadInput.min = '0.01';
    cantidadInput.value = linea.cantidad;
    cantidadTd.appendChild(cantidadInput);
    tr.appendChild(cantidadTd);

    const unidadTd = document.createElement('td');
    const unidadInput = document.createElement('input');
    unidadInput.type = 'text';
    unidadInput.placeholder = 'Unidad';
    unidadInput.id = `materiales[${index}][material_id]`;
    unidadInput.required = true;
    unidadInput.disabled = true;
    unidadTd.appendChild(unidadInput);
    tr.appendChild(unidadTd);

    // Columna de acción (- botón)
    const actionTd = document.createElement("td");
    const deleteButton = document.createElement("button");
    deleteButton.textContent = "-";
    deleteButton.addEventListener("click", () => deleteRow(index)); // Asignar evento para borrar
    actionTd.appendChild(deleteButton);
    tr.appendChild(actionTd);

    tbody.appendChild(tr);
  });
}

// Función para agregar una línea
function addRow() {
  const materialLinea = {
    material_id: '',
    cantidad: '',
    unidad: ''
  };
  lineas.push(materialLinea); // Agregar al arreglo
  renderTable(); // Actualizar tabla
}

// Función para borrar una línea
function deleteRow(index) {
  lineas.splice(index, 1); // Eliminar del arreglo
  renderTable(); // Actualizar tabla
}

// Simulación de la búsqueda de datos en el modo edición
function loadDataForEdit() {
  // Ejemplo: Datos obtenidos de una petición HTTP
  const fetchedData = [
    { descripcion: "Producto 1", cantidad: 10 },
    { descripcion: "Producto 2", cantidad: 20 },
    { descripcion: "Producto 3", cantidad: 30 }
  ];
  lineas = fetchedData; // Asignar datos al arreglo
  renderTable(); // Actualizar tabla
}

// Eventos
addRowButton.addEventListener("click", addRow);

// Ejemplo: Cargar datos para edición (puedes conectarlo a un evento o llamada HTTP)
// loadDataForEdit(); // Llamada simulada para modo edición