<?php

require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';

// Crear nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 11); // Establecer fuente

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$html = '
<h1>Reporte de usuarios</h1>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
    <tr>
        <th width="8%">ID</th>
        <th width="14%">Nombre de usuario</th>
        <th width="13%">Cédula</th>
        <th width="16%">Teléfono</th>
        <th width="30%">Dirección</th>
        <th width="10%">Rol</th>
        <th width="10%">Estado</th>
    </tr>
';

// buscando los registros
$filtros = [
    'nombre' => !empty($_GET['nombre']) ? "%{$_GET['nombre']}%" : '',
    'apellido' => !empty($_GET['apellido']) ? "%{$_GET['apellido']}%" : '',
    'cedula' => !empty($_GET['cedula']) ? "%{$_GET['cedula']}%" : '',
    'telefono' => !empty($_GET['telefono']) ? "%{$_GET['telefono']}%" : '',
    'direccion' => !empty($_GET['direccion']) ? "%{$_GET['direccion']}%" : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : '',
    'rol' => !empty($_GET['rol']) ? $_GET['rol'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $usuarios = Usuario::getUsuarios($filtros, $order);

    foreach ($usuarios as $usuario) {
        $html .= '
            <tr>
                <td>' . $usuario->id() . '</td>
                <td>' . $usuario->nombreUsuario() . '</td>
                <td>' . "V-{$usuario->cedula()}" . '</td>
                <td>' . preg_replace('/(\d{4})(\d{3})(\d{2})(\d{2})/', '$1-$2-$3-$4', $usuario->telefono()) . '</td>
                <td>' . $usuario->direccion() . '</td>
                <td>' . $usuario->rol() . '</td>
                <td>' . $usuario->estado() . '</td>
            </tr>
        ';
    }

    $html .= '
        </table>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('reporte_usuarios.pdf', 'I');
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}