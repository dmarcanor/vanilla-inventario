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

$filtrosHtml = "
    <tr>
        <td>ID</td>
        <td>{$id}</td>
    </tr>
    <tr>
        <td>Código</td>
        <td>{$codigo}</td>
    </tr>
    <tr>
        <td>Nombre</td>
        <td>{$nombre}</td>
    </tr>
    <tr>
        <td>Descripción</td>
        <td>{$descripcion}</td>
    </tr>
    <tr>
        <td>Marca</td>
        <td>{$marca}</td>
    </tr>
    <tr>
        <td>Categoría</td>
        <td>{$categoria}</td>
    </tr>
    <tr>
        <td>Unidad</td>
        <td>{$unidad}</td>
    </tr>
    <tr>
        <td>Fecha de creación (desde)</td>
        <td>{$fechaDesdeFiltro}</td>
    </tr>
    <tr>
        <td>Fecha de creación (hasta)</td>
        <td>{$fechaHastaFiltro}</td>
    </tr>
    <tr>
        <td>Precio</td>
        <td>{$precio}</td>
    </tr>
    <tr>
        <td>Presentación</td>
        <td>{$presentacion}</td>
    </tr>
    <tr>
        <td>Estado</td>
        <td>{$estado}</td>
    </tr>
";

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
<h4>Filtros:</h4>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center; width: 40%">
    ' . $filtrosHtml . '
</table>
<h2>Reporte de materiales</h2>
<table border="1" cellspacing="0" cellpadding="5" style="text-align: center">
    <tr>
        <th width="15%">Código</th>
        <th width="15%">Nombre</th>
        <th width="20%">Descripción</th>
        <th width="14%">Marca</th>
        <th width="15%">Categoría</th>
        <th width="10%">Precio</th>
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
                <td>' . $material->precio() . '</td>
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