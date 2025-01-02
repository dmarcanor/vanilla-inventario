const generar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const parametros = {
    "nombre": busqueda.nombre.value,
    "apellido": busqueda.apellido.value,
    "cedula": busqueda.cedula.value,
    "telefono": busqueda.telefono.value,
    "direccion": busqueda.direccion.value,
    "estado": busqueda.estado.value,
    "rol": busqueda.rol.value,
  };
  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/usuarios.php?${queryParams}`);
};