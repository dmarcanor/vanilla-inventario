document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  document.getElementById('nombre').addEventListener('blur', primeraLetraMayuscula);

  document.getElementById('apellido').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('apellido').addEventListener('input', soloPermitirLetras);

  document.getElementById('direccion').addEventListener('blur', primeraLetraMayuscula);

  document.getElementById('numero_identificacion').addEventListener('input', soloPermitirNumerosParaNumeroIdentificacion);

  const ruta = window.location.pathname;
  const estaEditando = ruta.includes('editar.php');

  if (!estaEditando) {
    const formulario = document.getElementById('formulario-clientes');
    cargarDatosFormulario(formulario, 'Clientes');
    mostrarNumeroIdentificacionLetra(JSON.parse(localStorage.getItem('datosFormularioClientes')).tipo_identificacion);
  }
});

const numeroIdentificacionInfo = document.getElementById('numeroIdentificacionInfo');
const numeroIdentificacionGuia = document.getElementById('numeroIdentificacionGuia');
const tipoIdentificacion = document.getElementById('tipo_identificacion');
let guideVisible = false;

// Mostrar u ocultar la guía de numero de identificacion
numeroIdentificacionInfo.addEventListener('click', function(e) {
  e.preventDefault();
  guideVisible = !guideVisible;
  numeroIdentificacionGuia.style.display = guideVisible ? 'block' : 'none';
});

// Ocultar la guía de numero de identificacion al hacer clic fuera de ella
document.addEventListener('click', function(e) {
  if (!numeroIdentificacionInfo.contains(e.target) && !numeroIdentificacionInfo.contains(e.target)) {
    guideVisible = false;
    numeroIdentificacionGuia.style.display = 'none';
  }
});

tipoIdentificacion.addEventListener('change', function (e) {
  mostrarNumeroIdentificacionLetra(e.target.value);
});

const mostrarNumeroIdentificacionLetra = (tipoIdentificacion) => {
  if (tipoIdentificacion == 'rif' || tipoIdentificacion == 'cedula') {
    document.getElementById('numero_identificacion_letra').hidden = false;
  }

  if (tipoIdentificacion === 'pasaporte') {
    document.getElementById('numero_identificacion_letra').hidden = true;
  }
}

const validarTelefono = (telefono) => {
  const regex = /^(0000|0294|0212|0293|0281|0412|0414|0416|0424|0426)\d{7}$/;

  if (!regex.test(telefono)) {
    const campoTelefono = document.getElementById("telefono");

    campoTelefono.setCustomValidity(
      "El número ingresado no es válido. Debe iniciar con 0000, 0294, 0212, 0293, 0281, 0412, 0414, 0416, 0424 o 0426 seguido de 7 dígitos."
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
  const tipoIdentificacionLetra = formulario.tipo_identificacion.value === 'rif' || formulario.tipo_identificacion.value === 'cedula'
    ? document.getElementById('numero_identificacion_letra').value
    : '';

  const nombre = formulario.nombre.value;
  const apellido = formulario.apellido.value;
  const tipo_identificacion = formulario.tipo_identificacion.value;
  const numero_identificacion = `${tipoIdentificacionLetra}${formulario.numero_identificacion.value}`;
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
  })
    .then((response) => {
      if (response.status === 401) {
        // Manejar sesión expirada
        window.location.href = '/vanilla-inventario/Views/Login/index.php';
        return Promise.reject('Sesión expirada');
      }

      return response.json()
    })
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      borrarDatosFormulario('Clientes');

      toastr.success('Cliente creado satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Clientes/index.php';
      }, 1500);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}

const editar = (id, formulario) => {
  const continuarEdicion = confirm('¿Está seguro de editar este cliente?');

  if (continuarEdicion == false) {
    return;
  }

  const tipoIdentificacionLetra = formulario.tipo_identificacion.value === 'rif' || formulario.tipo_identificacion.value === 'cedula'
    ? document.getElementById('numero_identificacion_letra').value
    : '';

  const nombre = formulario.nombre.value;
  const apellido = formulario.apellido.value;
  const tipo_identificacion = formulario.tipo_identificacion.value;
  const numero_identificacion = `${tipoIdentificacionLetra}${formulario.numero_identificacion.value}`;
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
  })
    .then((response) => {
      if (response.status === 401) {
        // Manejar sesión expirada
        window.location.href = '/vanilla-inventario/Views/Login/index.php';
        return Promise.reject('Sesión expirada');
      }

      return response.json()
    })
    .then(json => {
      if (json.ok === false) {
        throw new Error(json.mensaje);
      }

      borrarDatosFormulario('Clientes');

      toastr.success('Cliente editado satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Clientes/index.php';
      }, 1500);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  borrarDatosFormulario('Clientes');

  window.location.href = '/vanilla-inventario/Views/Clientes/index.php';
}

const soloPermitirNumerosParaNumeroIdentificacion = (event) => {
  const campo = event.target;

  const tipoIdentificacion = document.getElementById('tipo_identificacion').value;

  if (tipoIdentificacion === 'pasaporte') {
    return;
  }

  campo.value = campo.value.replace(/[^0-9-]/g, '');
}