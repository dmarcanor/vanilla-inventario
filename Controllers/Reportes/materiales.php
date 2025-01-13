<?php

require_once __DIR__ . '/../../Models/Materiales/Material.php';
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';

// Crear nueva instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('times', '', 11); // Establecer fuente

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$html = '
<img src="/vanilla-inventario/Assets/imagenes/logo.jpg" alt="logo.jpg">
<h1>Reporte de usuarios</h1>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
    <tr>
        <th width="8%">ID</th>
        <th width="8%">Código</th>
        <th width="11%">Nombre</th>
        <th width="12%">Descripción</th>
        <th width="11%">Marca</th>
        <th width="10%">Categoría</th>
        <th width="8%">Precio</th>
        <th width="10%">Cantidad</th>
        <th width="8%">Estado</th>
        <th width="14%">Fecha</th>
    </tr>
';

// buscando los registros
$fechaDesde = !empty($_GET['fecha_desde']) ? (new DateTimeImmutable($_GET['fecha_desde']))->format('Y-m-d 00:00:00') : '';
$fechaHasta = !empty($_GET['fecha_hasta']) ? (new DateTimeImmutable($_GET['fecha_hasta']))->format('Y-m-d 23:59:59') : '';

$filtros = [
    'id' => !empty($_GET['id']) ? $_GET['id'] : 0,
    'codigo' => !empty($_GET['codigo']) ? "%{$_GET['codigo']}%" : '',
    'nombre' => !empty($_GET['nombre']) ? "%{$_GET['nombre']}%" : '',
    'descripcion' => !empty($_GET['descripcion']) ? "%{$_GET['descripcion']}%" : '',
    'presentacion' => !empty($_GET['presentacion']) ? "%{$_GET['presentacion']}%" : '',
    'marca' => !empty($_GET['marca']) ? "%{$_GET['marca']}%" : '',
    'categoria_id' => !empty($_GET['categoria_id']) ? $_GET['categoria_id'] : '',
    'unidad' => !empty($_GET['unidad']) ? $_GET['unidad'] : '',
    'precio' => !empty($_GET['precio']) ? $_GET['precio'] : 0,
    'stock_desde' => !empty($_GET['stock_desde']) ? $_GET['stock_desde'] : 0,
    'stock_hasta' => !empty($_GET['stock_hasta']) ? $_GET['stock_hasta'] : 0,
    'fecha_desde' => $fechaDesde,
    'fecha_hasta' => $fechaHasta,
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : ''
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
                <td>' . $material->id() . '</td>
                <td>' . $material->codigo() . '</td>
                <td>' . $material->nombre() . '</td>
                <td>' . $material->descripcion() . '</td>
                <td>' . $material->marca() . '</td>
                <td>' . $material->categoria()->nombre() . '</td>
                <td>' . $material->precio() . '</td>
                <td>' . $material->stock() . '</td>
                <td>' . $material->estado() . '</td>
                <td>' . DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $material->fechaCreacion())->format('d/m/Y h:i:sA') . '</td>
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