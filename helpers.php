<?php

function verificarSesion()
{
    session_start();

    // Duración de la sesión en segundos (90 minutos)
    $session_duration = 90 * 60;

    if (isset($_SESSION['last_activity'])) {
        $inactive_time = time() - $_SESSION['last_activity'];

        if ($inactive_time > $session_duration) {
            // Destruir la sesión si ha expirado
            session_unset();
            session_destroy();

            // Verificar si es una petición AJAX
            throw new Exception('Sesión expirada');
        }
    } else {
        // Si no hay actividad previa
        throw new Exception('Sesión expirada');
    }

    // Actualizar la última actividad
    $_SESSION['last_activity'] = time();
}

function obtenerCampoOrdenEnTabla()
{
    if (!isset($_GET['order'])) {
        return 'id';
    }

    $order_column_index = $_GET['order'][0]['column']; // Índice de la columna ordenada
    $columns = $_GET['columns']; // Lista de columnas

    // Obtener el nombre de la columna a ordenar
    return $columns[$order_column_index]['data'];
}