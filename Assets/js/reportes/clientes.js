const generar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const parametros = {
    "nombre": busqueda.nombre.value,
    "apellido": busqueda.apellido.value,
    "tipo_identificacion": busqueda.tipo_identificacion.value,
    "numero_identificacion": busqueda.numero_identificacion.value,
    "telefono": busqueda.telefono.value,
    "direccion": busqueda.direccion.value,
    "estado": busqueda.estado.value,
  };
  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/clientes.php?${queryParams}`);
};