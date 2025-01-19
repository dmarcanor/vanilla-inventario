<?php

require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';

// Crear nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 11); // Establecer fuente

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$html = '
<table width="100%">
<tbody>
    <tr>
        <td><h1>Comercializadora G&S C.A.</h1></td>
        <td>Fecha de reporte: ' . (new DateTimeImmutable("now", new DateTimeZone("America/Caracas")))->format('d/m/Y h:i:sA') . '</td>
    </tr>
    <tr>
        <td>Rif:50105235-2</td>
    </tr>
    <tr>
        <td>Teléfono: 0412-1848791</td>
    </tr>
</tbody>
</table>
<h2>Reporte de usuarios</h2>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
    <tr>
        <th width="16%">Nombre de usuario</th>
        <th width="14%">Cédula</th>
        <th width="17%">Teléfono</th>
        <th width="30%">Dirección</th>
        <th width="12%">Rol</th>
        <th width="12%">Estado</th>
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
    'rol' => !empty($_GET['rol']) ? $_GET['rol'] : '',
    'nombre_usuario' => !empty($_GET['nombre_usuario']) ? "%{$_GET['nombre_usuario']}%" : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['orden']) ? $_GET['orden'] : 'ASC';
$ordenCampo = !empty($_GET['ordenCampo']) ? $_GET['ordenCampo'] : 'id';

try {
    $usuarios = Usuario::getUsuarios($filtros, $order, $ordenCampo);

    foreach ($usuarios as $usuario) {
        $html .= '
            <tr>
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