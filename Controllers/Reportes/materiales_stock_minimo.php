<?php

require_once __DIR__ . '/../../Models/Materiales/Material.php';
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';

// Crear nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 11); // Establecer fuente

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$html = '
<h1>Reporte de materiales en stock mínimo</h1>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
    <tr>
        <th width="12%">Código</th>
        <th width="12%">Nombre</th>
        <th width="16%">Descripción</th>
        <th width="12%">Marca</th>
        <th width="12%">Categoría</th>
        <th width="10%">Precio</th>
        <th width="11%">Cantidad</th>
        <th width="15%">Fecha</th>
    </tr>
';

// buscando los registros
$fechaDesde = !empty($_GET['fecha_desde']) ? (new DateTimeImmutable($_GET['fecha_desde']))->format('Y-m-d 00:00:00') : '';
$fechaHasta = !empty($_GET['fecha_hasta']) ? (new DateTimeImmutable($_GET['fecha_hasta']))->format('Y-m-d 23:59:59') : '';

$filtros = [
    'stock_minimo' => !empty($_GET['stock_minimo']) && $_GET['stock_minimo'] === 'true' ? $_GET['stock_minimo'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['limit']) ? (int)$_GET['limit'] : 0;
$length = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['orden']) ? $_GET['orden'] : 'ASC';
$ordenCampo = !empty($_GET['ordenCampo']) ? $_GET['ordenCampo'] : 'id';

try {
    $materiales = Material::getMateriales($filtros, $order, $ordenCampo, $limit);

    foreach ($materiales as $material) {
        $html .= '
            <tr>
                <td>' . $material->codigo() . '</td>
                <td>' . $material->nombre() . '</td>
                <td>' . $material->descripcion() . '</td>
                <td>' . $material->marca() . '</td>
                <td>' . $material->categoria()->nombre() . '</td>
                <td>' . $material->precio() . '</td>
                <td>' . $material->stock() . '</td>
                <td>' . DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $material->fechaCreacion())->format('d/m/Y') . '</td>
            </tr>
        ';
    }

    $html .= '
        </table>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('reporte_materiales.pdf', 'I');
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}