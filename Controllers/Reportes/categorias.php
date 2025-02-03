<?php

require_once __DIR__ . '/../../Models/Categorias/Categoria.php';
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
    'descripcion' => !empty($_GET['descripcion']) ? "%{$_GET['descripcion']}%" : '',
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : ''
];

$filtros = array_filter($filtros);
$limit = !empty($_GET['length']) ? (int)$_GET['length'] : 10;
$skip = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
$order = !empty($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';
$ordenCampo = obtenerCampoOrdenEnTabla();

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$nombre = str_replace('%', '', $filtros['nombre'] ?? '');
$descripcion = str_replace('%', '', $filtros['descripcion'] ?? '');
$estado = str_replace('%', '', $filtros['estado'] ?? '');

$filtrosTitulo = [];

if (!empty($nombre)) {
    $filtrosTitulo[] = "Nombre: {$nombre}";
}

if (!empty($descripcion)) {
    $filtrosTitulo[] = "Descripción: {$descripcion}";
}

if (!empty($estado)) {
    $filtrosTitulo[] = "Estado: {$estado}";
}

$filtrosTexto = !empty($filtrosTitulo) ? "Filtros: " . implode(', ', $filtrosTitulo) : '';

$titulo = "Reporte de categorías. {$filtrosTexto}";

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
        <th width="30%">Nombre</th>
        <th width="40%">Descripción</th>
        <th width="30%">Estado</th>
    </tr>
';

try {
    $categorias = Categoria::getCategorias($filtros, $order, $ordenCampo);

    foreach ($categorias as $categoria) {
        $html .= '
            <tr>
                <td>' . $categoria->nombre() . '</td>
                <td>' . $categoria->descripcion() . '</td>
                <td>' . $categoria->estado() . '</td>
            </tr>
        ';
    }

    $html .= '
        </table>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('reporte_categorias.pdf', 'I');
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}