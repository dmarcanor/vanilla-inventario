<?php

require_once __DIR__ . '/../../Models/Clientes/Cliente.php';
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
    'tipo_identificacion' => !empty($_GET['tipo_identificacion']) ? $_GET['tipo_identificacion'] : '',
    'numero_identificacion' => !empty($_GET['numero_identificacion']) ? "%{$_GET['numero_identificacion']}%" : '',
    'telefono' => !empty($_GET['telefono']) ? "%{$_GET['telefono']}%" : '',
    'direccion' => !empty($_GET['direccion']) ? "%{$_GET['direccion']}%" : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';
$ordenCampo = obtenerCampoOrdenEnTabla();


if ($ordenCampo === 'tipoIdentificacion') {
    $ordenCampo = 'tipo_identificacion';
}

if ($ordenCampo === 'numeroIdentificacion') {
    $ordenCampo = 'numero_identificacion';
}

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$tipoIdentificacion = str_replace('%', '', $filtros['tipo_identificacion'] ?? '');
$numeroIdentificacion = str_replace('%', '', $filtros['numero_identificacion'] ?? '');
$nombre = str_replace('%', '', $filtros['nombre'] ?? '');
$apellido = str_replace('%', '', $filtros['apellido'] ?? '');
$telefono = str_replace('%', '', $filtros['telefono'] ?? '');
$estado = str_replace('%', '', $filtros['estado'] ?? '');
$direccion = str_replace('%', '', $filtros['direccion'] ?? '');

if ($tipoIdentificacion === 'cedula') {
    $tipoIdentificacion = 'cédula';
}

$filtrosTitulo = [];

if (!empty($tipoIdentificacion)) {
    $filtrosTitulo[] = "Tipo de identificación: {$tipoIdentificacion}";
}

if (!empty($numeroIdentificacion)) {
    $filtrosTitulo[] = "Número de identificación: {$numeroIdentificacion}";
}

if (!empty($nombre)) {
    $filtrosTitulo[] = "Nombre: {$nombre}";
}

if (!empty($apellido)) {
    $filtrosTitulo[] = "Apellido: {$apellido}";
}

if (!empty($telefono)) {
    $filtrosTitulo[] = "Teléfono: {$telefono}";
}

if (!empty($direccion)) {
    $filtrosTitulo[] = "Dirección: {$direccion}";
}

if (!empty($estado)) {
    $filtrosTitulo[] = "Estado: {$estado}";
}

$filtrosTexto = !empty($filtrosTitulo) ? "Filtros: " . implode(', ', $filtrosTitulo) : '';

$titulo = "Reporte de clientes. {$filtrosTexto}";

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
<h2>' . $titulo . '</h2>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center;">
    <tr>
        <th width="22%">Nombre</th>
        <th width="18%">Tipo de identificación</th>
        <th width="18%">Número de identificación</th>
        <th width="18%">Teléfono</th>
        <th width="24%">Dirección</th>
    </tr>
';

try {
    $clientes = Cliente::getClientes($filtros, $order, $ordenCampo);

    foreach ($clientes as $cliente) {
        $html .= '
            <tr>
                <td>' . $cliente->nombre() . " " .  $cliente->apellido() . '</td>
                <td>' . $cliente->tipoIdentificacion() . '</td>
                <td>' . $cliente->numeroIdentificacion() . '</td>
                <td>' . preg_replace('/(\d{4})(\d{3})(\d{2})(\d{2})/', '$1-$2-$3-$4', $cliente->telefono()) . '</td>
                <td>' . $cliente->direccion() . '</td>
            </tr>
        ';
    }

    $html .= '
        </table>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('reporte_clientes.pdf', 'I');
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}