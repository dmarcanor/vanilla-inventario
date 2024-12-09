const login = (event) => {
  event.preventDefault();

  const loginForm = document.getElementById('login-form');
  const usuario = loginForm.usuario.value;
  const contrasenia = loginForm.contrasenia.value;

  fetch('/vanilla-inventario/Controllers/Login/LoginController.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      usuario,
      contrasenia
    })
  })
    .then(response => response.json())
    .then(response => {
      if (response.ok === false) {
        alert("Usuario o contraseña incorrectos");
        return;
      }

      window.localStorage.setItem('usuario', JSON.stringify(response.usuario));

      if (response.usuario.rol === 'operador') {
        window.location.href = '/vanilla-inventario/Views/Salidas/index.php';
        return;
      }

      window.location.href = '/vanilla-inventario/Views/Inicio/index.php';
    });
}