const esAdmin = () => {
  const usuario = JSON.parse(window.localStorage.getItem('usuario'));

  if (!usuario) {
    return false;
  }

  return usuario.rol === 'admin';
}

const usuarioSesion = () => {
  return JSON.parse(localStorage.getItem('usuario'));
}

const salirDelSistema = () => {
  window.location.href = '/vanilla-inventario/Views/Login/index.php';
}

const primeraLetraMayuscula = (event) => {
  const campo = event.target;
  const valor = campo.value

  campo.value = valor.charAt(0).toUpperCase() + valor.slice(1).toLowerCase();
}

const soloPermitirLetras = (event) => {
  const campo = event.target;

  campo.value = campo.value.replace(/[^a-zA-Z ]/g, '');
}

const soloPermitirNumerosYCaracterDivision = (event) => {
  const campo = event.target;

  campo.value = campo.value.replace(/[^0-9\/]/g, '');
}

const noPermitirCaracteresEspeciales = (event) => {
  const campo = event.target;

  campo.value = campo.value.replace(/[^a-zA-Z0-9]/g, '');
}

const formatearTelefono = (telefono) => {
  return telefono.replace(/(\d{4})(\d{3})(\d{2})(\d{2})/, '$1-$2-$3-$4');
}

const fechaActual = () => {
  const ahora = new Date();
  const formatoVenezuela = new Intl.DateTimeFormat('es-VE', {
    timeZone: 'America/Caracas',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
  });

  const [day, month, year] = formatoVenezuela.format(ahora).split('/');
  return `${year}-${month}-${day}`;
}

// const formulario = document.getElementById('formMaterial');
// const campos = formulario.elements;

// Función para guardar los datos del formulario en localStorage
const guardarDatosFormulario = (formulario, modulo) => {
  const campos = formulario.elements;
  const llave = `datosFormulario${modulo}`;
  const datosFormulario = {};

  for (let campo of campos) {
    if (campo.id) { // Ignorar campos que no tienen atributo "name"
      datosFormulario[campo.id] = campo.value; // Guardar el nombre y el valor del campo
    }

    if (typeof lineas !== 'undefined') {
      datosFormulario.lineas = lineas;
    }
  }
  // Guardar los datos en localStorage como un string JSON
  localStorage.setItem(llave, JSON.stringify(datosFormulario));
}

// Función para cargar los datos guardados en localStorage al formulario
const cargarDatosFormulario = (formulario, modulo) => {
  const campos = formulario.elements;
  const llave = `datosFormulario${modulo}`;
  const datosGuardados = localStorage.getItem(llave);

  if (datosGuardados) {
    const datosFormulario = JSON.parse(datosGuardados); // Convertir el string JSON en objeto
    for (let campo of campos) {
      if (campo.id && datosFormulario[campo.id]) { // Verificar si el campo tiene un valor guardado
        campo.value = datosFormulario[campo.id]; // Asignar el valor al campo
      }
    }

    if (datosFormulario.lineas) {
      lineas = datosFormulario.lineas;
    }
  }
}

const borrarDatosFormulario = (modulo) => {
  localStorage.removeItem(`datosFormulario${modulo}`)
}