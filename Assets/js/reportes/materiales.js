const generar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const parametros = {
    "id": busqueda.id.value,
    "codigo": busqueda.codigo.value,
    "nombre": busqueda.nombre.value,
    "descripcion": busqueda.descripcion.value,
    "presentacion": busqueda.presentacion.value,
    "marca": busqueda.marca.value,
    "categoria_id": busqueda.categoria_id.value,
    "unidad": busqueda.unidad.value,
    "estado": busqueda.estado.value,
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value,
    "precio_desde": busqueda.precio_desde.value,
    "precio_hasta": busqueda.precio_hasta.value
  };
  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/materiales.php?${queryParams}`);
};