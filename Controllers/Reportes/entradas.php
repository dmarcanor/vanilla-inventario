<?php

require_once __DIR__ . '/../../Models/Entradas/Entrada.php';
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';

// Crear nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 11); // Establecer fuente

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$html = '
<h1>Reporte de entradas</h1>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
    <tr>
        <th width="20%">Número de entrada</th>
        <th width="20%">Material</th>
        <th width="28%">Observación</th>
        <th width="12%">Cantidad</th>
        <th width="20%">Fecha</th>
    </tr>
';

// buscando los registros
$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : '',
    'numero_entrada' => !empty($_GET['numeroEntrada']) ? "%{$_GET['numeroEntrada']}%" : '',
    'material' => !empty($_GET['material']) ? $_GET['material'] : '',
    'fecha_desde' => !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '',
    'fecha_hasta' => !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

try {
    $entradas = Entrada::getEntradas($filtros, $order);

    foreach ($entradas as $entrada) {
        foreach ($entrada->lineas() as $entradaLinea) {
            $html .= '
                <tr>
                    <td>' . $entrada->numeroEntrada() . '</td>
                    <td>' . $entradaLinea->material()->nombre() . " - " . $entradaLinea->material()->presentacion() . '</td>
                    <td>' . $entrada->observacion() . '</td>
                    <td>' . $entradaLinea->cantidad() . '</td>
                    <td>' . DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entrada->fechacreacion() )->format('d/m/Y H:i:s') . '</td>
                </tr>
            ';
        }
    }

    $html .= '
        </table>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('reporte_entradas.pdf', 'I');
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}