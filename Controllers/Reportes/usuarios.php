<?php

require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';
require_once '../../helpers.php';

try {
    verificarSesion();
} catch (\Exception $exception) {
    header('Location: /vanilla-inventario/Views/Login/index.php');
    exit();
}

// Crear nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 11); // Establecer fuente

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
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';
$ordenCampo = obtenerCampoOrdenEnTabla();


if ($ordenCampo === 'nombreUsuario') {
    $ordenCampo = 'nombre_usuario';
}

if ($ordenCampo === 'numeroIdentificacion') {
    $ordenCampo = 'numero_identificacion';
}

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$cedula = str_replace('%', '', $filtros['cedula'] ?? '');
$nombre = str_replace('%', '', $filtros['nombre'] ?? '');
$apellido = str_replace('%', '', $filtros['apellido'] ?? '');
$telefono = str_replace('%', '', $filtros['telefono'] ?? '');
$estado = str_replace('%', '', $filtros['estado'] ?? '');
$direccion = str_replace('%', '', $filtros['direccion'] ?? '');
$rol = str_replace('%', '', $filtros['rol'] ?? '');
$nombre_usuario = str_replace('%', '', $filtros['nombre_usuario'] ?? '');

$filtrosHtml = "
    <tr>
        <td>Cédula</td>
        <td>{$cedula}</td>
    </tr>
    <tr>
        <td>Nombre</td>
        <td>{$nombre}</td>
    </tr>
    <tr>
        <td>Apellido</td>
        <td>{$apellido}</td>
    </tr>
    <tr>
        <td>Teléfono</td>
        <td>{$telefono}</td>
    </tr>
    <tr>
        <td>Dirección</td>
        <td>{$direccion}</td>
    </tr>
    <tr>
        <td>Rol</td>
        <td>{$rol}</td>
    </tr>
    <tr>
        <td>Estado</td>
        <td>{$estado}</td>
    </tr>
    <tr>
        <td>Nombre de usuario</td>
        <td>{$nombre_usuario}</td>
    </tr>
";

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
<h4>Filtros:</h4>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center; width: 40%">
    ' . $filtrosHtml . '
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