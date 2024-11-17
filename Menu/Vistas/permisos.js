const esAdmin = () => {
  const usuario = JSON.parse(window.localStorage.getItem('usuario'));

  return usuario.rol === 'admin';
}