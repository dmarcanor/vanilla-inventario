const primeraLetraMayuscula = (event) => {
  const campo = event.target;
  const valor = campo.value;

  campo.value = valor.toLowerCase()
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
}