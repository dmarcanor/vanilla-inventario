document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  const queryParams = new URLSearchParams(window.location.search);
  const id = queryParams.get('id');

  fetch(`/vanilla-inventario/Controllers/Usuarios/GetUsuarioController.php?id=${id}`, {
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
      document.getElementById('nombre_usuario').value = json.usuario.nombreUsuario;
      document.getElementById('contrasenia').value = json.usuario.contrasenia;
      document.getElementById('repetir_contrasenia').value = json.usuario.contrasenia;
      document.getElementById('nombre').value = json.usuario.nombre;
      document.getElementById('apellido').value = json.usuario.apellido;
      document.getElementById('cedula').value = json.usuario.cedula;
      document.getElementById('telefono').value = json.usuario.telefono;
      document.getElementById('direccion').value = json.usuario.direccion;
      document.getElementById('rol').value = json.usuario.rol;
      document.getElementById('estado').value = json.usuario.estado;
    })
    .catch((mensaje) => {
      alert(mensaje);

      // window.location.href = '/vanilla-inventario/Views/Usuarios/index.php';
    });
});