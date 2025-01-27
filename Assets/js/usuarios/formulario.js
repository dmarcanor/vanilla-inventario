document.addEventListener('DOMContentLoaded', () => {
  if (!usuarioSesion()) {
    salirDelSistema();
    return;
  }

  document.getElementById('nombre_usuario').addEventListener('input', noPermitirCaracteresEspeciales);

  document.getElementById('nombre').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('nombre').addEventListener('input', soloPermitirLetras);

  document.getElementById('apellido').addEventListener('blur', primeraLetraMayuscula);
  document.getElementById('apellido').addEventListener('input', soloPermitirLetras);

  document.getElementById('direccion').addEventListener('blur', primeraLetraMayuscula);

  const ruta = window.location.pathname;
  const estaEditando = ruta.includes('editar.php');

  if (!estaEditando) {
    const formulario = document.getElementById('formulario-usuarios');
    cargarDatosFormulario(formulario, 'Usuarios');
  }
});

const passwordInfo = document.getElementById('contraseniaInfo');
const passwordGuide = document.getElementById('contraseniaGuia');
const mostrarContrasenia = document.getElementById('mostrarContrasenia');
const campoContrasenia = document.getElementById('contrasenia');
const mostrarRepetirContrasenia = document.getElementById('mostrarRepetirContrasenia');
const campoRepetirContrasenia = document.getElementById('repetir_contrasenia');
let guideVisible = false;

// Mostrar u ocultar la guía de contraseñas
passwordInfo.addEventListener('click', function(e) {
  e.preventDefault();
  guideVisible = !guideVisible;
  passwordGuide.style.display = guideVisible ? 'block' : 'none';
});

// Mostrar u ocultar la contraseña
mostrarContrasenia.addEventListener('click', function(e) {
  e.preventDefault();
  const type = campoContrasenia.getAttribute('type') === 'password' ? 'text' : 'password';
  campoContrasenia.setAttribute('type', type);
  this.textContent = type === 'password' ? '👁️' : '🔒';
});

// Mostrar u ocultar la repetición de la contraseña
mostrarRepetirContrasenia.addEventListener('click', function(e) {
  e.preventDefault();
  const type = campoRepetirContrasenia.getAttribute('type') === 'password' ? 'text' : 'password';
  campoRepetirContrasenia.setAttribute('type', type);
  this.textContent = type === 'password' ? '👁️' : '🔒';
});

// Ocultar la guía de contraseñas al hacer clic fuera de ella
document.addEventListener('click', function(e) {
  if (!passwordInfo.contains(e.target) && !passwordGuide.contains(e.target)) {
    guideVisible = false;
    passwordGuide.style.display = 'none';
  }
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

const validarContrasenia = (contrasenia, repetirContrasenia, esEditar) => {
  const minLength = 8;
  const tieneMayuscula = /[A-Z]/.test(contrasenia);
  const tieneMinuscula = /[a-z]/.test(contrasenia);
  const tieneNumero = /\d/.test(contrasenia);
  const tieneCaracterEspecial = /[!@#$%^&*]/.test(contrasenia);

  // Si es una edición y no se ingresó una nueva contraseña, se asume que no se desea cambiarla
  if (esEditar && contrasenia == '' && repetirContrasenia == '') {
    return true;
  }

  if (contrasenia.length < minLength) {
    toastr.error('La contraseña debe tener al menos 8 caracteres.');
    return false;
  }
  if (!tieneMayuscula) {
    toastr.error('La contraseña debe contener al menos una letra mayúscula.');
    return false;
  }
  if (!tieneMinuscula) {
    toastr.error('La contraseña debe contener al menos una letra minúscula.');
    return false;
  }
  if (!tieneNumero) {
    toastr.error('La contraseña debe contener al menos un número.');
    return false;
  }
  if (!tieneCaracterEspecial) {
    toastr.error('La contraseña debe contener al menos un carácter especial (!@#$%^&*).');
    return false;
  }

  if (contrasenia !== repetirContrasenia) {
    toastr.error('Las contraseñas no coinciden');
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
  const esEditar = id !== '';

  if (! validarContrasenia(event.target.contrasenia.value, event.target.repetir_contrasenia.value, esEditar)) {
    return;
  }

  if (!id) {
    crear(formulario);
    return
  }

  editar(id, formulario);
}

const crear = (formulario) => {
  const nombreUsuario = formulario.nombre_usuario.value;
  const nombre = formulario.nombre.value;
  const apellido = formulario.apellido.value;
  const cedula = formulario.cedula.value;
  const contrasenia = formulario.contrasenia.value;
  const telefono = formulario.telefono.value;
  const direccion = formulario.direccion.value;
  const rol = formulario.rol.value;
  const estado = formulario.estado.value;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Usuarios/CrearUsuarioController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      nombreUsuario,
      nombre,
      apellido,
      cedula,
      contrasenia,
      telefono,
      direccion,
      rol,
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

      borrarDatosFormulario('Usuarios');

      toastr.success('Usuario creado satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Usuarios/index.php';
      }, 1500);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}

const editar = (id, formulario) => {
  const continuarEdicion = confirm('¿Está seguro de editar este usuario?');

  if (continuarEdicion == false) {
    return;
  }

  const nombreUsuario = formulario.nombre_usuario.value;
  const nombre = formulario.nombre.value;
  const apellido = formulario.apellido.value;
  const cedula = formulario.cedula.value;
  const contrasenia = formulario.contrasenia ? formulario.contrasenia.value : '';
  const telefono = formulario.telefono.value;
  const direccion = formulario.direccion.value;
  const rol = formulario.rol.value;
  const estado = formulario.estado.value;
  const usuarioSesion = JSON.parse(localStorage.getItem('usuario'));

  fetch('/vanilla-inventario/Controllers/Usuarios/EditarUsuarioController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id,
      nombreUsuario,
      nombre,
      apellido,
      cedula,
      contrasenia,
      telefono,
      direccion,
      rol,
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

      borrarDatosFormulario('Usuarios');

      toastr.success('Usuario editado satisfactoriamente.');
      setTimeout(() => {
        window.location.href = '/vanilla-inventario/Views/Usuarios/index.php';
      }, 1500);
    })
    .catch((mensaje) => {
      toastr.error(mensaje);
    });
}

const cancelar = (event) => {
  event.preventDefault();

  borrarDatosFormulario('Usuarios');

  window.location.href = '/vanilla-inventario/Views/Usuarios/index.php';
}