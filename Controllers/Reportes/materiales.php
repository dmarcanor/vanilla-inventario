<?php

require_once __DIR__ . '/../../Models/Materiales/Material.php';
require_once __DIR__ . '/../../Models/Categorias/Categoria.php';
require_once __DIR__ . '/../../Models/Marcas/Marca.php';
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
    'id' => !empty($_GET['id']) ? $_GET['id'] : 0,
    'codigo' => $_GET['codigo'] != '' ? "%{$_GET['codigo']}%" : '',
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
    'estado' => !empty($_GET['estado']) ? $_GET['estado'] : '',
    'stock_minimo' => !empty($_GET['stock_minimo']) && $_GET['stock_minimo'] === 'true' ? $_GET['stock_minimo'] : ''
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

// Cuerpo del reporte en HTML, incompleto porque mas abajo se completa con los datos de la base de datos
$id = str_replace('%', '', $filtros['id'] ?? '');
$codigo = str_replace('%', '', $filtros['codigo'] ?? '');
$nombre = str_replace('%', '', $filtros['nombre'] ?? '');
$descripcion = str_replace('%', '', $filtros['descripcion'] ?? '');
$marca = str_replace('%', '', $filtros['marca'] ?? '');
$categoria = str_replace('%', '', $filtros['categoria_id'] ?? '');
$unidad = str_replace('_', ' ', $filtros['unidad'] ?? '');
$fechaDesdeFiltro = !empty($_GET['fecha_desde']) ? (new DateTimeImmutable($_GET['fecha_desde']))->format('d/m/Y') : '';
$fechaHastaFiltro = !empty($_GET['fecha_hasta']) ? (new DateTimeImmutable($_GET['fecha_hasta']))->format('d/m/Y') : '';
$precio = str_replace('%', '', $filtros['precio'] ?? '');
$presentacion = str_replace('%', '', $filtros['presentacion'] ?? '');
$estado = str_replace('%', '', $filtros['estado'] ?? '');

try {
    $marca = Marca::getMarca($marca)->nombre();
} catch (Exception $exception) {
    $marca = "";
}

try {
    $categoria = Categoria::getCategoria($categoria)->nombre();
} catch (Exception $exception) {
    $categoria = "";
}

$filtrosTitulo = [];

if (!empty($id)) {
    $filtrosTitulo[] = "ID: {$id}";
}

if (!empty($codigo)) {
    $filtrosTitulo[] = "Código: {$codigo}";
}

if (!empty($nombre)) {
    $filtrosTitulo[] = "Nombre: {$nombre}";
}

if (!empty($descripcion)) {
    $filtrosTitulo[] = "Descripción: {$descripcion}";
}

if (!empty($marca)) {
    $filtrosTitulo[] = "Marca: {$marca}";
}

if (!empty($categoria)) {
    $filtrosTitulo[] = "Categoría: {$categoria}";
}

if (!empty($unidad)) {
    $filtrosTitulo[] = "Unidad: {$unidad}";
}

if (!empty($precio)) {
    $filtrosTitulo[] = "Precio: {$precio}";
}

if (!empty($presentacion)) {
    $filtrosTitulo[] = "Presentación: {$presentacion}";
}

if (!empty($estado)) {
    $filtrosTitulo[] = "Estado: {$estado}";
}

if (!empty($fechaDesdeFiltro)) {
    $filtrosTitulo[] = "Fecha creación desde: {$fechaDesdeFiltro}";
}

if (!empty($fechaHastaFiltro)) {
    $filtrosTitulo[] = "Fecha creación hasta: {$fechaHastaFiltro}";
}

$filtrosTexto = !empty($filtrosTitulo) ? "Filtros: " . implode(', ', $filtrosTitulo) : '';

$titulo = "Reporte de materiales. {$filtrosTexto}";

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
<h2>' . $titulo . ' </h2>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
    <tr>
        <th width="12%">Código</th>
        <th width="15%">Nombre</th>
        <th width="15%">Descripción</th>
        <th width="14%">Marca</th>
        <th width="15%">Categoría</th>
        <th width="9%">Precio al detal</th>
        <th width="9%">Precio al mayor</th>
        <th width="11%">Cantidad</th>
    </tr>
';

try {
    $materiales = Material::getMateriales($filtros, $order, $ordenCampo, $limit);

    foreach ($materiales as $material) {
        $marca = $material->marca() ? $material->marca()->nombre() : "";

        $html .= '
            <tr>
                <td>' . $material->codigo() . '</td>
                <td>' . $material->nombre() . '</td>
                <td>' . $material->descripcion() . '</td>
                <td>' . $marca . '</td>
                <td>' . $material->categoria()->nombre() . '</td>
                <td>' . $material->precioDetal() . '</td>
                <td>' . $material->precioMayor() . '</td>
                <td>' . $material->stock() . '</td>
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