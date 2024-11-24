document.addEventListener('DOMContentLoaded', () => {
  const queryParams = new URLSearchParams(window.location.search);
  const id = queryParams.get('id');

  fetch(`/vanilla-inventario/Controllers/Clientes/GetClienteController.php?id=${id}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }
console.log(
  json,
  document.getElementById('id')
);
      document.getElementById('id').value = id;
      document.getElementById('nombre').value = json.cliente.nombre;
      document.getElementById('apellido').value = json.cliente.apellido;
      document.getElementById('tipo_identificacion').value = json.cliente.tipoIdentificacion;
      document.getElementById('numero_identificacion').value = json.cliente.numeroIdentificacion;
      document.getElementById('telefono').value = json.cliente.telefono;
      document.getElementById('direccion').value = json.cliente.direccion;
      document.getElementById('estado').value = json.cliente.estado;
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
});