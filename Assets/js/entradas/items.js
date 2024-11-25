const materialLinea = {
  material_id: '',
  cantidad: '',
  unidad: ''
};

let items = [];
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

const agregarLinea = () => {
  console.log("agregarLinea", items);
  items.push(materialLinea);

  actualizarTablaDeMateriales(items);
}

const actualizarTablaDeMateriales = (items) => {
  document.getElementById('entrada-items-body').innerHTML = '';
console.log("actualizarTablaDeMateriales", items);
  items.forEach((item, index) => {
    actualizarLineaDeTablaMateriales(item, index);
  });
}

const actualizarLineaDeTablaMateriales = (item, index) => {
  const entradaItemsBody = document.getElementById('entrada-items-body');
  const tr = document.createElement('tr');

  const selectMaterial = document.createElement('select');
  selectMaterial.value = item.material_id;
  selectMaterial.name = `materiales[${index}][material_id]`;
  selectMaterial.required = true;
  selectMaterial.onchange = (event) => {
    const materialSeleccionado = event.target.value;

    if (materialSeleccionado === '') {
      return;
    }

    const material = materialesEnBaseDeDatos.find((material) => material.id === materialSeleccionado);

    item.material_id = material.id;
    item.unidad = material.unidad;

    document.getElementById(`materiales[${index}][material_id]`).value = item.unidad;
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

  const cantidadTd = document.createElement('td');
  const cantidadInput = document.createElement('input');
  cantidadInput.type = 'number';
  cantidadInput.placeholder = 'Cantidad';
  cantidadInput.name = `materiales[${index}][cantidad]`;
  cantidadInput.required = true;
  cantidadInput.min = '0.01';
  cantidadInput.value = item.cantidad;
  cantidadTd.appendChild(cantidadInput);

  const unidadTd = document.createElement('td');
  const unidadInput = document.createElement('input');
  unidadInput.type = 'text';
  unidadInput.placeholder = 'Unidad';
  unidadInput.id = `materiales[${index}][material_id]`;
  unidadInput.required = true;
  unidadInput.disabled = true;
  unidadTd.appendChild(unidadInput);

  const eliminarTd = document.createElement('td');
  const eliminarButton = document.createElement('button');
  eliminarButton.type = 'button';
  eliminarButton.className = 'btn btn-danger';
  eliminarButton.textContent = '-';
  eliminarButton.onclick = () => {
    console.log("eliminarButton", index);
    items = items.filter((item, key) => key !== index);

    actualizarTablaDeMateriales(items);
  };
  eliminarTd.appendChild(eliminarButton);

  tr.appendChild(materialTd);
  tr.appendChild(cantidadTd);
  tr.appendChild(unidadTd);
  tr.appendChild(eliminarTd);

  entradaItemsBody.appendChild(tr);
}