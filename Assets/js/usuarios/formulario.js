


const guardar = (event) => {
  event.preventDefault();

  const formulario = event.target;
  const id = formulario.id ? formulario.id.value : '';

  if (!id) {
    crear(formulario);
    return
  }

  editar(id, formulario);
}

const crear = (formulario) => {
  const nombre = formulario.nombre.value;
  const apellido = formulario.apellido.value;
  const cedula = formulario.cedula.value;
  const contrasenia = formulario.contrasenia.value;
  const telefono = formulario.telefono.value;
  const direccion = formulario.direccion.value;
  const rol = formulario.rol.value;
  const estado = formulario.estado.value;

  fetch('/vanilla-inventario/Controllers/Usuarios/CrearUsuarioController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      nombre,
      apellido,
      cedula,
      contrasenia,
      telefono,
      direccion,
      rol,
      estado
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Usuario creado satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Usuarios/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const editar = (id, formulario) => {
  const nombre = formulario.nombre.value;
  const apellido = formulario.apellido.value;
  const cedula = formulario.cedula.value;
  const contrasenia = formulario.contrasenia ? formulario.contrasenia.value : '';
  const telefono = formulario.telefono.value;
  const direccion = formulario.direccion.value;
  const rol = formulario.rol.value;
  const estado = formulario.estado.value;

  fetch('/vanilla-inventario/Controllers/Usuarios/EditarUsuarioController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id,
      nombre,
      apellido,
      cedula,
      contrasenia,
      telefono,
      direccion,
      rol,
      estado
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Usuario editado satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Usuarios/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  window.location.href = '/vanilla-inventario/Views/Usuarios/index.php';
}