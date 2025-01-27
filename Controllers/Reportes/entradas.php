<?php

require_once __DIR__ . '/../../Models/Entradas/Entrada.php';
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
    'numero_entrada' => !empty($_GET['numeroEntrada']) ? "%{$_GET['numeroEntrada']}%" : '',
    'material' => !empty($_GET['material']) ? $_GET['material'] : '',
    'fecha_desde' => $fechaDesde,
    'fecha_hasta' => $fechaHasta,
    'categoria' => !empty($_GET['categoria']) ? $_GET['categoria'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['limit']) ? (int)$_GET['limit'] : 0;
$length = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';
$ordenCampo = obtenerCampoOrdenEnTabla();

if ($ordenCampo === 'id') {
    $ordenCampo = 'entradas.id';
}

if ($ordenCampo === 'numeroEntrada') {
    $ordenCampo = 'numero_entrada';
}

if ($ordenCampo === 'fechaCreacionSinHora') {
    $ordenCampo = 'fecha_creacion';
}

try {
    $entradas = Entrada::getEntradas($filtros, $order, $limit, $ordenCampo);
    $esSoloUnaEntrada = count($entradas) === 1;
    $buscandoPorId = !empty($filtros['id']);

    $titulo = $buscandoPorId ? "Reporte de la entrada número {$entradas[0]->numeroEntrada()}" : "Reporte de entradas";

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
        <h2> ' . $titulo . '</h2>
        <table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
            <tr>
                <th width="11%">Número de entrada</th>
                <th width="16%">Material</th>
                <th width="10%">Categoría</th>
                <th width="10%">Marca</th>
                <th width="19%">Observación</th>
                <th width="10%">Cantidad</th>
                <th width="11%">Precio costo</th>
                <th width="13%">Fecha</th>
            </tr>
    ';

    foreach ($entradas as $entrada) {
        foreach ($entrada->lineas() as $indice => $entradaLinea) {
            if (
                (!empty($_GET['material']) && $entradaLinea->material()->id() != $_GET['material'])
                || (!empty($_GET['categoria']) && $entradaLinea->material()->categoria()->id() != $_GET['categoria'])
            ) {
                continue;
            }

            $marca = !empty($entradaLinea->material()->marca()) ? $entradaLinea->material()->marca()->nombre() : "";

            $html .= '
                <tr>
                    <td>' . $entrada->numeroEntrada() . '</td>
                    <td>' . $entradaLinea->material()->codigo() . " - " . $entradaLinea->material()->nombre() . " - " . $entradaLinea->material()->presentacion() . '</td>
                    <td>' . $entradaLinea->material()->categoria()->nombre() . '</td>
                    <td>' . $marca . '</td>
                    <td>' . $entrada->observacion() . '</td>
                    <td>' . $entradaLinea->cantidad() . '</td>
                    <td>' . "$" . $entradaLinea->precio() . '</td>
            ';

            if ($buscandoPorId && $indice > 0) {
                $html .= '<td></td> </tr>';
                continue;
            }

            $html .= '
                <td>' . DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entrada->fechaCreacion())->format('d/m/Y h:i:sA') . '</td>
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