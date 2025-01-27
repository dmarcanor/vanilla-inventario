<?php

require_once __DIR__ . '/../../Models/Salidas/Salida.php';
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
$fechaDesde = !empty($_GET['fecha_desde']) ? (new DateTimeImmutable($_GET['fecha_desde']))->format('Y-m-d 00:00:00') : '';
$fechaHasta = !empty($_GET['fecha_hasta']) ? (new DateTimeImmutable($_GET['fecha_hasta']))->format('Y-m-d 23:59:59') : '';

$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : '',
    'observacion' => !empty($_GET['observacion']) ? "%{$_GET['observacion']}%" : '',
    'usuario_id' => !empty($_GET['usuarioId']) ? $_GET['usuarioId'] : '',
    'cliente_id' => !empty($_GET['clienteId']) ? $_GET['clienteId'] : '',
    'fecha_desde' => $fechaDesde,
    'fecha_hasta' => $fechaHasta,
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : '',
    'material' => !empty($_GET['material']) ? $_GET['material'] : '',
    'categoria' => !empty($_GET['categoria']) ? $_GET['categoria'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['limit']) ? (int)$_GET['limit'] : 0;
$length = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';
$ordenCampo = obtenerCampoOrdenEnTabla();


if ($ordenCampo === 'categoriaNombre') {
    $ordenCampo = 'categoria_id';
}

if ($ordenCampo === 'stockMinimo') {
    $ordenCampo = 'stock_minimo';
}

try {
    $salidas = Salida::getSalidas($filtros, $order, $limit, $ordenCampo);
    $esSoloUnaSalida = count($salidas) === 1;
    $buscandoPorId = !empty($filtros['id']);

    $titulo = $buscandoPorId ? "Reporte de la salida número {$salidas[0]->id()}" : "Reporte de salidas";
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
        <h2>' . $titulo . '</h2>
        <table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
            <tr>
                <th width="15%">Cliente</th>
                <th width="15%">Material</th>
                <th width="12%">Categoría</th>
                <th width="12%">Marca</th>
                <th width="20%">Observaciòn</th>
                <th width="12%">Cantidad</th>
                <th width="14%">Fecha</th>
            </tr>
    ';

    foreach ($salidas as $salida) {
        foreach ($salida->lineas() as $indice => $salidaLinea) {
            if (
                (!empty($_GET['material']) && $salidaLinea->material()->id() !== $_GET['material'])
                || (!empty($_GET['categoria']) && $salidaLinea->material()->categoria()->id() !== $_GET['categoria'])
            ) {
                continue;
            }

            $marca = !empty($salidaLinea->material()->marca()) ? $salidaLinea->material()->marca()->nombre() : "";

            $html .= '
                <tr>
                    <td>' . $salida->cliente()->nombre() . " " . $salida->cliente()->apellido() . '</td>
                    <td>' . $salidaLinea->material()->codigo() . " - " . $salidaLinea->material()->nombre() . " - " . $salidaLinea->material()->presentacion() . '</td>
                    <td>' . $salidaLinea->material()->categoria()->nombre() . '</td>
                    <td>' . $marca . '</td>
                    <td>' . $salida->observacion() . '</td>
                    <td>' . $salidaLinea->cantidad() . '</td>
            ';

            if ($buscandoPorId && $indice > 0) {
                $html .= '<td></td> </tr>';
                continue;
            }

            $html .= '
                <td>' . DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $salida->fechaCreacion())->format('d/m/Y h:i:sA') . '</td>
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