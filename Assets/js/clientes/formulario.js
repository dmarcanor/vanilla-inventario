document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  document.getElementById('nombre').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('nombre').addEventListener('input', soloPermitirLetras);

  document.getElementById('apellido').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('apellido').addEventListener('input', soloPermitirLetras);

  document.getElementById('direccion').addEventListener('blur', primeraLetraMayuscula);
});

const validarTelefono = (telefono) => {
  const regex = /^(0412|0414|0416|0424|0426)\d{7}$/;

  if (!regex.test(telefono)) {
    const campoTelefono = document.getElementById("telefono");

    campoTelefono.setCustomValidity(
      "El número ingresado no es válido. Debe iniciar con 0412, 0414, 0416, 0424 o 0426 seguido de 7 dígitos."
    );
    campoTelefono.reportValidity();

    return false;
  }

  return true;
}

const guardar = (event) => {
  event.preventDefault();

  if (! validarTelefono(event.target.telefono.value)) {
    return;
  }

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
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Clientes/CrearClienteController.php', {
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
      estado,
      usuarioSesion: usuarioSesion.id
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Cliente creado satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Clientes/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const editar = (id, formulario) => {
  const continuarEdicion = confirm('¿Está seguro de editar este cliente?');

  if (continuarEdicion == false) {
    return;
  }

  const nombre = formulario.nombre.value;
  const apellido = formulario.apellido.value;
  const tipo_identificacion = formulario.tipo_identificacion.value;
  const numero_identificacion = formulario.numero_identificacion.value;
  const telefono = formulario.telefono.value;
  const direccion = formulario.direccion.value;
  const estado = formulario.estado.value;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Clientes/EditarClienteController.php', {
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
      estado,
      usuarioSesion: usuarioSesion.id
    })
  }).then(response => response.json())
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      alert('Cliente editado satisfactoriamente.');
      window.location.href = '/vanilla-inventario/Views/Clientes/index.php';
    })
    .catch((mensaje) => {
      alert(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  window.location.href = '/vanilla-inventario/Views/Clientes/index.php';
}