<?php

require_once __DIR__ . '/../../Models/Categorias/Categoria.php';
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';

// Crear nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 11); // Establecer fuente

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$html = '
<h1>Reporte de categorías</h1>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
    <tr>
        <th width="10%">ID</th>
        <th width="20%">Nombre</th>
        <th width="30%">Descripción</th>
        <th width="20%">Estado</th>
        <th width="20%">Fecha</th>
    </tr>
';

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

try {
    $categorias = Categoria::getCategorias($filtros, $order);

    foreach ($categorias as $categoria) {
        $html .= '
            <tr>
                <td>' . $categoria->id() . '</td>
                <td>' . $categoria->nombre() . '</td>
                <td>' . $categoria->descripcion() . '</td>
                <td>' . $categoria->estado() . '</td>
                <td>' . $categoria->fechacreacion() . '</td>
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