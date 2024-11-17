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
  const tipo_identificacion = formulario.tipo_identificacion.value;
  const numero_identificacion = formulario.numero_identificacion.value;
  const telefono = formulario.telefono.value;
  const direccion = formulario.direccion.value;
  const estado = formulario.estado.value;

  fetch('/vanilla-inventario/Clientes/Controladores/CrearClienteController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      nombre,
      apellido,
      tipo_identificacion,
      numero_identificacion,
      telefono,
      direccion,
      estado
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Cliente creado satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Clientes/Vistas/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const editar = (id, formulario) => {
  const nombre = formulario.nombre.value;
  const apellido = formulario.apellido.value;
  const tipo_identificacion = formulario.tipo_identificacion.value;
  const numero_identificacion = formulario.numero_identificacion.value;
  const telefono = formulario.telefono.value;
  const direccion = formulario.direccion.value;
  const estado = formulario.estado.value;

  fetch('/vanilla-inventario/Clientes/Controladores/EditarClienteController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id,
      nombre,
      apellido,
      tipo_identificacion,
      numero_identificacion,
      telefono,
      direccion,
      estado
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Cliente editado satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Clientes/Vistas/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  window.location.href = '/vanilla-inventario/Clientes/Vistas/index.php';
}