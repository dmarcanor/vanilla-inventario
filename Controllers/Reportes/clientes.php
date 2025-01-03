<?php

require_once __DIR__ . '/../../Models/Clientes/Cliente.php';
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';

// Crear nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 11); // Establecer fuente

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$html = '
<h1>Reporte de usuarios</h1>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center;">
    <tr>
        <th width="11%">Nombre</th>
        <th width="11%">Apellido</th>
        <th width="10%">Tipo de identificación</th>
        <th width="14%">Número de identificación</th>
        <th width="14%">Teléfono</th>
        <th width="26%">Dirección</th>
        <th width="14%">Fecha y hora</th>
    </tr>
';

// buscando los registros
$filtros = [
    'nombre' => !empty($_GET['nombre']) ? "%{$_GET['nombre']}%" : '',
    'apellido' => !empty($_GET['apellido']) ? "%{$_GET['apellido']}%" : '',
    'tipo_identificacion' => !empty($_GET['tipo_identificacion']) ? $_GET['tipo_identificacion'] : '',
    'numero_identificacion' => !empty($_GET['numero_identificacion']) ? "%{$_GET['numero_identificacion']}%" : '',
    'telefono' => !empty($_GET['telefono']) ? "%{$_GET['telefono']}%" : '',
    'direccion' => !empty($_GET['direccion']) ? "%{$_GET['direccion']}%" : '',
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $clientes = Cliente::getClientes($filtros, $order);

    foreach ($clientes as $cliente) {
        $html .= '
            <tr>
                <td>' . $cliente->nombre() . '</td>
                <td>' . $cliente->apellido() . '</td>
                <td>' . $cliente->tipoIdentificacion() . '</td>
                <td>' . $cliente->numeroIdentificacion() . '</td>
                <td>' . $cliente->telefono() . '</td>
                <td>' . $cliente->direccion() . '</td>
                <td>' . DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $cliente->fechaCreacion())->format('d/m/Y H:i:s') . '</td>
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