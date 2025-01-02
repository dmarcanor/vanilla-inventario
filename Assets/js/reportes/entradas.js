const generar = (event) => {
  event.preventDefault();

  const busqueda = event.target;
  const parametros = {
    "id": busqueda.id.value,
    "numeroEntrada": busqueda.numero_entrada.value,
    "material": busqueda.material.value,
    "fecha_desde": busqueda.fecha_desde.value,
    "fecha_hasta": busqueda.fecha_hasta.value
  };
  const queryParams = new URLSearchParams(parametros).toString();

  // abrir enlace en otra pestaña
  window.open(`/vanilla-inventario/Controllers/Reportes/entradas.php?${queryParams}`);
};