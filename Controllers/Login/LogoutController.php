<?php


session_start();

// Destruir todas las variables de sesión
$_SESSION = [];

// Destruir la sesión por completo
session_destroy();

echo json_encode(['ok' => true]);
exit();
