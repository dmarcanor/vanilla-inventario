const generar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const parametros = {
    "nombre": busqueda.nombre.value,
    "descripcion": busqueda.descripcion.value,
    "estado": busqueda.estado.value,
  };
  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/categorias.php?${queryParams}`);
};