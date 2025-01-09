<?php

require_once __DIR__ . '/../../Models/Salidas/Salida.php';
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';

// Crear nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 11); // Establecer fuente

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$html = '
<h1>Reporte de salidas</h1>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
    <tr>
        <th width="20%">Cliente</th>
        <th width="20%">Material</th>
        <th width="28%">Observaciòn</th>
        <th width="12%">Cantidad</th>
        <th width="20%">Fecha</th>
    </tr>
';

// buscando los registros
$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : '',
    'observacion' => !empty($_GET['observacion']) ? "%{$_GET['observacion']}%" : '',
    'usuario_id' => !empty($_GET['usuarioId']) ? $_GET['usuarioId'] : '',
    'cliente_id' => !empty($_GET['clienteId']) ? $_GET['clienteId'] : '',
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['limit']) ? (int)$_GET['limit'] : 0;
$length = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $salidas = Salida::getSalidas($filtros, $order, $limit);

    foreach ($salidas as $salida) {
        foreach ($salida->lineas() as $salidaLinea) {
            $html .= '
            <tr>
                <td>' . $salida->cliente()->nombre() . " " . $salida->cliente()->apellido() . '</td>
                <td>' . $salidaLinea->material()->nombre() . " - " . $salidaLinea->material()->presentacion() . '</td>
                <td>' . $salida->observacion() . '</td>
                <td>' . $salidaLinea->cantidad() . '</td>
                <td>' . $salida->fechaCreacion() . '</td>
            </tr>
        ';
        }
    }

    $html .= '
        </table>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('reporte_salidas.pdf', 'I');
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}