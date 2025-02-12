<?php

require_once __DIR__ . '/../../Models/Salidas/Salida.php';
require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';
require_once __DIR__ . '/../../Models/Clientes/Cliente.php';
require_once __DIR__ . '/../../Models/Materiales/Material.php';
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

    $id = str_replace('%', '', $filtros['id'] ?? '');
    $observacion = str_replace('%', '', $filtros['observacion'] ?? '');
    $usuario = str_replace('%', '', $filtros['usuario_id'] ?? '');
    $cliente = str_replace('%', '', $filtros['cliente_id'] ?? '');
    $fechaDesdeFiltro = !empty($_GET['fecha_desde']) ? (new DateTimeImmutable($_GET['fecha_desde']))->format('d/m/Y') : '';
    $fechaHastaFiltro = !empty($_GET['fecha_hasta']) ? (new DateTimeImmutable($_GET['fecha_hasta']))->format('d/m/Y') : '';
    $material = str_replace('%', '', $filtros['material'] ?? '');
    $categoria = str_replace('%', '', $filtros['categoria'] ?? '');

    try {
        $modeloUsuario = Usuario::getUsuario($usuario);
        $usuario = "{$modeloUsuario->nombre()} {$modeloUsuario->apellido()}";
    } catch (Exception $exception) {
        $usuario = "";
    }

    try {
        $modeloCliente = Cliente::getCliente($cliente);
        $cliente = "{$modeloCliente->numeroIdentificacion()} - {$modeloCliente->nombre()} {$modeloCliente->apellido()}";
    } catch (Exception $exception) {
        $cliente = "";
    }

    try {
        $objetoMaterial = Material::getMaterial($material);
        $materialMarca = !empty($objetoMaterial->marca()) ? $objetoMaterial->marca()->nombre() : '';
        $material = "{$objetoMaterial->nombre()} - {$objetoMaterial->descripcion()} - {$materialMarca}";
    } catch (Exception $exception) {
        $material = "";
    }

    try {
        $categoria = Categoria::getCategoria($categoria)->nombre();
    } catch (Exception $exception) {
        $categoria = "";
    }

    $filtros = [];

    if (!empty($id)) {
        $filtros[] = "ID: {$id}";
    }

    if (!empty($observacion)) {
        $filtros[] = "Observación: {$observacion}";
    }

    if (!empty($usuario)) {
        $filtros[] = "Usuario: {$usuario}";
    }

    if (!empty($cliente)) {
        $filtros[] = "Cliente: {$cliente}";
    }

    if (!empty($fechaDesdeFiltro)) {
        $filtros[] = "Fecha creación desde: {$fechaDesdeFiltro}";
    }

    if (!empty($fechaHastaFiltro)) {
        $filtros[] = "Fecha creación hasta: {$fechaHastaFiltro}";
    }

    if (!empty($material)) {
        $filtros[] = "Material: {$material}";
    }

    if (!empty($categoria)) {
        $filtros[] = "Categoría: {$categoria}";
    }

    $filtrosTexto = !empty($filtros) ? "Filtros: " . implode(', ', $filtros) : '';

    $titulo = "Reporte de salidas. {$filtrosTexto}";

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
                <th width="13%">Cliente</th>
                <th width="15%">Material</th>
                <th width="12%">Categoría</th>
                <th width="10%">Marca</th>
                <th width="13%">Observaciòn</th>
                <th width="8%">Cantidad</th>
                <th width="8%">Precio</th>
                <th width="8%">Precio total</th>
                <th width="13%">Fecha</th>
            </tr>
    ';

    $total = 0;

    foreach ($salidas as $salida) {
        foreach ($salida->lineas() as $indice => $salidaLinea) {
            if (
                (!empty($_GET['material']) && $salidaLinea->material()->id() != $_GET['material'])
                || (!empty($_GET['categoria']) && $salidaLinea->material()->categoria()->id() != $_GET['categoria'])
            ) {
                continue;
            }

            $total = $total + $salidaLinea->precioTotal();

            $marca = !empty($salidaLinea->material()->marca()) ? $salidaLinea->material()->marca()->nombre() : "";

            $html .= '
                <tr>
                    <td>' . $salida->cliente()->numeroIdentificacion() . " - " . $salida->cliente()->nombre() . " " . $salida->cliente()->apellido() . '</td>
                    <td>' . $salidaLinea->material()->codigo() . " - " . $salidaLinea->material()->nombre() . " - " . $salidaLinea->material()->descripcion() . " - " . $salidaLinea->material()->presentacion() . '</td>
                    <td>' . $salidaLinea->material()->categoria()->nombre() . '</td>
                    <td>' . $marca . '</td>
                    <td>' . $salida->observacion() . '</td>
                    <td>' . $salidaLinea->cantidad() . '</td>
                    <td>' . $salidaLinea->precio() . '</td>
                    <td>' . $salidaLinea->precioTotal() . '</td>
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
        

            <p style="font-size: 12px; text-align: right">Total: $' . $total . '</p>

    ';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('reporte_salidas.pdf', 'I');
} catch (\Exception $exception) {
    echo json_encode([
        'ok' => false,
        'data' => []
    ]);
}