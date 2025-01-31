document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

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

      const numeroIdentificacionLetra = json.cliente.tipoIdentificacion === 'rif' || json.cliente.tipoIdentificacion === 'cedula'
        ? json.cliente.numeroIdentificacion[0] : '';
      const numeroIdentificacion = json.cliente.tipoIdentificacion === 'rif' || json.cliente.tipoIdentificacion === 'cedula'
        ? json.cliente.numeroIdentificacion.substring(1) : json.cliente.numeroIdentificacion;

      document.getElementById('id').value = id;
      document.getElementById('nombre').value = json.cliente.nombre;
      document.getElementById('apellido').value = json.cliente.apellido;
      document.getElementById('tipo_identificacion').value = json.cliente.tipoIdentificacion;
      document.getElementById('numero_identificacion_letra').value = numeroIdentificacionLetra;
      document.getElementById('numero_identificacion').value = numeroIdentificacion;
      document.getElementById('telefono').value = json.cliente.telefono;
      document.getElementById('direccion').value = json.cliente.direccion;
      document.getElementById('estado').value = json.cliente.estado;

      if (numeroIdentificacionLetra) {
        document.getElementById('numero_identificacion_letra').hidden = false;
      }
    })
    .catch((mensaje) => {
      alert(mensaje);

      window.location.href = '/vanilla-inventario/Views/Clientes/index.php';
    });
});