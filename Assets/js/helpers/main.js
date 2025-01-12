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

const noPermitirCaracteresEspeciales = (event) => {
  const campo = event.target;

  campo.value = campo.value.replace(/[^a-zA-Z0-9]/g, '');
}

const formatearTelefono = (telefono) => {
  return telefono.replace(/(\d{4})(\d{3})(\d{2})(\d{2})/, '$1-$2-$3-$4');
}