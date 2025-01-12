<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../../Models/Categorias/Categoria.php';

class Material
{
    private $id;
    private $codigo;
    private $nombre;
    private $descripcion;
    private $marca;
    private $categoriaId;
    private $unidad;
    private $presentacion;
    private $stock;
    private $precio;
    private $fechaCreacion;
    private $estado;
    private $stockMinimo;

    public function __construct($id, $codigo, $nombre, $descripcion, $marca, $categoriaId, $unidad, $presentacion, $precio, $stock, $stockMinimo, $fechaCreacion, $estado)
    {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->marca = $marca;
        $this->categoriaId = $categoriaId;
        $this->unidad = $unidad;
        $this->presentacion = $presentacion;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->stockMinimo = $stockMinimo;
        $this->fechaCreacion = $fechaCreacion;
        $this->estado = $estado;
    }

    public static function crear($codigo, $nombre, $descripcion, $marca, $categoriaId, $unidad, $presentacion, $estado, $precio, $stockMinimo, $usuarioSesion)
    {
        date_default_timezone_set('America/Caracas');

        self::validarCamposVacios($codigo, $nombre, $categoriaId, $unidad, $presentacion, $estado);
        self::validarPrecio($precio);
        self::validarStockMinimo($stockMinimo);

        $materialConCodigo = self::getMaterialPorCodigo($codigo);

        if (!empty($materialConCodigo)) {
            throw new Exception("El código {$codigo} ya está en uso.");
        }

        $material = new Material(
            null,
            $codigo,
            $nombre,
            $descripcion,
            $marca,
            $categoriaId,
            $unidad,
            $presentacion,
            $precio,
            0,
            $stockMinimo,
            date('Y-m-d H:i:s'),
            $estado
        );

        $consultaCrearMaterial = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO materiales(codigo, nombre, descripcion, marca, categoria_id, unidad, presentacion, precio, stock, stock_minimo, fecha_creacion, estado) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $consultaCrearMaterial->execute([
            $material->codigo,
            $material->nombre,
            $material->descripcion,
            $material->marca,
            $material->categoriaId,
            $material->unidad,
            $material->presentacion,
            $material->precio,
            $material->stock,
            $material->stockMinimo,
            $material->fechaCreacion,
            $material->estado
        ]);

        $consultaMaterialId = (new ConexionBD())->getConexion()->prepare("SELECT id FROM materiales ORDER BY id DESC LIMIT 1");
        $consultaMaterialId->execute();
        $materialId = $consultaMaterialId->fetch(PDO::FETCH_ASSOC);

        $nuevoMaterial = new Material(
            $materialId['id'],
            $material->codigo,
            $material->nombre,
            $material->descripcion,
            $material->marca,
            $material->categoriaId,
            $material->unidad,
            $material->presentacion,
            $material->precio,
            $material->stock,
            $material->stockMinimo,
            $material->fechaCreacion,
            $material->estado
        );

        self::guardarHistorial($usuarioSesion, $nuevoMaterial, null);
    }

    public static function editar($id, $codigo, $nombre, $descripcion, $marca, $categoriaId, $unidad, $presentacion, $estado, $precio, $stockMinimo, $usuarioSesion)
    {
        date_default_timezone_set('America/Caracas');

        $materialOriginal = self::getMaterial($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($materialOriginal)) {
            throw new Exception("Material no encontrado.");
        }

        self::validarCamposVacios($codigo, $nombre, $categoriaId, $unidad, $presentacion, $estado);
        self::validarPrecio($precio);
        self::validarStockMinimo($stockMinimo);

        if ($materialOriginal->codigo !== $codigo) {
            $materialConCodigo = self::getMaterialPorCodigo($codigo);

            if (!empty($materialConCodigo)) {
                throw new Exception("El código {$codigo} ya está en uso.");
            }
        }

        $materialModificado = new Material(
            $id,
            $codigo,
            $nombre,
            $descripcion,
            $marca,
            $categoriaId,
            $unidad,
            $presentacion,
            $precio,
            $materialOriginal->stock,
            $stockMinimo,
            $materialOriginal->fechaCreacion,
            $estado
        );

        $consultaEditarCliente = $conexionBaseDatos->prepare("
            UPDATE materiales 
            SET codigo = ?, nombre = ?, descripcion = ?, marca = ?, categoria_id = ?, unidad = ?, presentacion = ?, estado = ?, precio = ?, stock_minimo = ?
            WHERE id = ?
        ");

        $consultaEditarCliente->execute([
            $materialModificado->codigo,
            $materialModificado->nombre,
            $materialModificado->descripcion,
            $materialModificado->marca,
            $materialModificado->categoriaId,
            $materialModificado->unidad,
            $materialModificado->presentacion,
            $materialModificado->estado,
            $materialModificado->precio,
            $materialModificado->stockMinimo,
            $materialModificado->id
        ]);

        self::guardarHistorial($usuarioSesion, $materialOriginal, $materialModificado);
    }

    private static function guardarHistorial($usuarioSesion, $materialOriginal, $materialModificado)
    {
        date_default_timezone_set('America/Caracas');

        $conexionBaseDatos = (new ConexionBD())->getConexion();
        $cambios = [];

        if (empty($materialModificado)) {
            $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

            $consultaHistorial->execute([
                $usuarioSesion,
                'Creado',
                'Material',
                $materialOriginal->id,
                'Material creado',
                date('Y-m-d H:i:s')
            ]);

            return;
        }

        if ($materialOriginal->codigo !== $materialModificado->codigo) {
            $cambios[] = "Código: {$materialOriginal->codigo} => {$materialModificado->codigo}";
        }

        if ($materialOriginal->nombre !== $materialModificado->nombre) {
            $cambios[] = "Nombre: {$materialOriginal->nombre} => {$materialModificado->nombre}";
        }

        if ($materialOriginal->descripcion !== $materialModificado->descripcion) {
            $cambios[] = "Descripción: {$materialOriginal->descripcion} => {$materialModificado->descripcion}";
        }

        if ($materialOriginal->marca !== $materialModificado->marca) {
            $cambios[] = "Marca: {$materialOriginal->marca} => {$materialModificado->marca}";
        }

        if ($materialOriginal->categoriaId !== $materialModificado->categoriaId) {
            $categoriaOriginal = Categoria::getCategoria($materialOriginal->categoriaId);
            $categoriaModificada = Categoria::getCategoria($materialModificado->categoriaId);

            $cambios[] = "Categoría: {$categoriaOriginal->nombre()} => {$categoriaModificada->nombre()}";
        }

        if ($materialOriginal->unidad !== $materialModificado->unidad) {
            $cambios[] = "Unidad: {$materialOriginal->unidad} => {$materialModificado->unidad}";
        }

        if ($materialOriginal->presentacion !== $materialModificado->presentacion) {
            $cambios[] = "Presentación: {$materialOriginal->presentacion} => {$materialModificado->presentacion}";
        }

        if ($materialOriginal->precio !== $materialModificado->precio) {
            $cambios[] = "Precio: {$materialOriginal->precio} => {$materialModificado->precio}";
        }

        if ($materialOriginal->stockMinimo !== $materialModificado->stockMinimo) {
            $cambios[] = "Stock mínimo: {$materialOriginal->stockMinimo} => {$materialModificado->stockMinimo}";
        }

        if ($materialOriginal->estado !== $materialModificado->estado) {
            $cambios[] = "Estado: {$materialOriginal->estado} => {$materialModificado->estado}";
        }

        foreach ($cambios as $cambio) {
            $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

            $consultaHistorial->execute([
                $usuarioSesion,
                'Cambio',
                'Material',
                $materialModificado->id,
                $cambio,
                date('Y-m-d H:i:s')
            ]);
        }
    }

    public static function cambiarEstado($id, $usuarioSesion)
    {
        $materialOriginal = self::getMaterial($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($materialOriginal)) {
            throw new Exception("Material no encontrado.");
        }

        if ($materialOriginal->estado === 'activo') {
            $nuevoEstado = 'inactivo';
        } else {
            $nuevoEstado = 'activo';
        }

        $materialModificado = new Material(
            $materialOriginal->id,
            $materialOriginal->codigo,
            $materialOriginal->nombre,
            $materialOriginal->descripcion,
            $materialOriginal->marca,
            $materialOriginal->categoriaId,
            $materialOriginal->unidad,
            $materialOriginal->presentacion,
            $materialOriginal->precio,
            $materialOriginal->stock,
            $materialOriginal->stockMinimo,
            $materialOriginal->fechaCreacion,
            $nuevoEstado
        );

        $consultaEditarMaterial = $conexionBaseDatos->prepare("
            UPDATE materiales 
            SET estado = ?
            WHERE id = ?
        ");

        $consultaEditarMaterial->execute([
            $nuevoEstado,
            $id
        ]);

        self::guardarHistorial($usuarioSesion, $materialOriginal, $materialModificado);
    }

    public static function getMaterial($id)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, codigo, nombre, descripcion, marca, categoria_id, unidad, presentacion, precio, stock, stock_minimo, fecha_creacion, estado 
            FROM materiales WHERE id = ?
        ");
        $consulta->execute([$id]);
        $material = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($material)) {
            throw new Exception('Material no encontrado.');
        }

        return new Material(
            $material['id'],
            $material['codigo'],
            $material['nombre'],
            $material['descripcion'],
            $material['marca'],
            $material['categoria_id'],
            $material['unidad'],
            $material['presentacion'],
            $material['precio'],
            $material['stock'],
            $material['stock_minimo'],
            $material['fecha_creacion'],
            $material['estado']
        );
    }

    public static function getMaterialPorCodigo($codigo)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, codigo, nombre, descripcion, marca, categoria_id, unidad, presentacion, precio, stock, stock_minimo, fecha_creacion, estado 
            FROM materiales WHERE codigo = ?
        ");
        $consulta->execute([$codigo]);
        $material = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($material)) {
            return null;
        }

        return new Material(
            $material['id'],
            $material['codigo'],
            $material['nombre'],
            $material['descripcion'],
            $material['marca'],
            $material['categoria_id'],
            $material['unidad'],
            $material['presentacion'],
            $material['precio'],
            $material['stock'],
            $material['stock_minimo'],
            $material['fecha_creacion'],
            $material['estado']
        );
    }

    public static function getMateriales($filtros, $orden, $ordenCampo, $limit)
    {
        $consultaMateriales = "SELECT id, codigo, nombre, descripcion, marca, categoria_id, unidad, presentacion, precio, stock, stock_minimo, fecha_creacion, estado FROM materiales";

        if (!empty($filtros)) {
            $consultaMateriales .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['codigo', 'nombre', 'presentacion', 'descripcion', 'marca'];

                if (in_array($key, $campos)) {
                    $operador = 'LIKE';
                    $filtro = "'{$filtro}'";
                } elseif ($key === 'fecha_desde') {
                    $key = 'fecha_creacion';
                    $operador = '>=';
                    $filtro = "'{$filtro}'";
                } elseif ($key === 'fecha_hasta') {
                    $key = 'fecha_creacion';
                    $operador = '<=';
                    $filtro = "'{$filtro}'";
                } elseif ($key === 'precio_desde') {
                    $key = 'precio';
                    $operador = '>=';
                } elseif ($key === 'precio_hasta') {
                    $key = 'precio';
                    $operador = '<=';
                } elseif ($key === 'stock_desde') {
                    $key = 'stock';
                    $operador = '>=';
                } elseif ($key === 'stock_hasta') {
                    $key = 'stock';
                    $operador = '<=';
                } elseif ($key === 'stock_minimo') {
                    $key = 'stock';
                    $operador = '<=';
                    $filtro = 'stock_minimo';
                } else {
                    $operador = '=';
                    $filtro = "'{$filtro}'";
                }

                $consultaMateriales .= "{$key} {$operador} {$filtro}";

                $iteracion++;

                if ($iteracion < count($filtros)) {
                    $consultaMateriales .= " AND ";
                }
            }
        }

        $consultaMateriales .= " ORDER BY {$ordenCampo} {$orden}";

        if ($limit > 0) {
            $consultaMateriales .= " LIMIT {$limit}";
        }

        $consulta = (new ConexionBD())->getConexion()->prepare($consultaMateriales);
        $consulta->execute();

        $materialesBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $materiales = [];

        foreach ($materialesBaseDatos as $material) {
            $materiales[] = new Material(
                $material['id'],
                $material['codigo'],
                $material['nombre'],
                $material['descripcion'],
                $material['marca'],
                $material['categoria_id'],
                $material['unidad'],
                $material['presentacion'],
                $material['precio'],
                $material['stock'],
                $material['stock_minimo'],
                $material['fecha_creacion'],
                $material['estado']
            );
        }

        return $materiales;
    }

    public static function eliminar($id)
    {
        $material = self::getMaterial($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($material)) {
            throw new Exception("Material no encontrado.");
        }

        $consultaEliminarMaterial = $conexionBaseDatos->prepare("
            DELETE FROM materiales
            WHERE id = ?
        ");

        $consultaEliminarMaterial->execute([$id]);
    }

    public function incrementarStock($cantidad)
    {
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        $this->stock = $this->stock + (float)$cantidad;

        $consultaAumentarStock = $conexionBaseDatos->prepare("
            UPDATE materiales SET stock = ? WHERE id = ?
        ");

        $consultaAumentarStock->execute([
            $this->stock,
            $this->id
        ]);
    }

    public function rebajarStock($cantidad)
    {
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        $this->stock = $this->stock - (float)$cantidad;

        if ($this->stock < $this->stockMinimo) {
            $_POST['mensaje'] = "Está rebajando más que el stock mínimo para el material {$this->nombre}.";
        }

        if ($this->stock < 0) {
            throw new Exception("No hay suficiente stock para rebajar el material {$this->nombre}.");
        }

        $consultaAumentarStock = $conexionBaseDatos->prepare("
            UPDATE materiales SET stock = ? WHERE id = ?
        ");

        $consultaAumentarStock->execute([
            $this->stock,
            $this->id
        ]);
    }

    public function cambiarPrecio($precio)
    {
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        $this->precio = $precio;

        $consultaCambiarPrecio = $conexionBaseDatos->prepare("
            UPDATE materiales SET precio = ? WHERE id = ?
        ");

        $consultaCambiarPrecio->execute([
            $this->precio,
            $this->id
        ]);
    }

    public static function validarCamposVacios($codigo, $nombre, $categoria_id, $unidad, $presentacion, $estado)
    {
        if (empty($codigo)) {
            throw new Exception("El código no puede estar vacío.");
        }

        if (empty($nombre)) {
            throw new Exception("El nombre no puede estar vacío.");
        }

        if (empty($categoria_id)) {
            throw new Exception("La categoría no puede estar vacía.");
        }

        if (empty($unidad)) {
            throw new Exception("La unidad no puede estar vacía.");
        }

        if (empty($presentacion)) {
            throw new Exception("La presentación no puede estar vacía.");
        }

        if (empty($estado)) {
            throw new Exception("El estado no puede estar vacío.");
        }
    }

    public static function validarPrecio($precio)
    {
        if ($precio <= 0) {
            throw new Exception("El precio no puede ser menor o igual a 0.");
        }
    }

    public static function validarStockMinimo($stockMinimo)
    {
        if ($stockMinimo <= 0) {
            throw new Exception("El stock mínimo no puede ser menor o igual a 0.");
        }
    }

    public function categoria()
    {
        return Categoria::getCategoria($this->categoriaId);
    }

    public function nombre()
    {
        return $this->nombre;
    }

    public function id()
    {
        return $this->id;
    }

    public function codigo()
    {
        return $this->codigo;
    }

    public function descripcion()
    {
        return $this->descripcion;
    }

    public function marca()
    {
        return $this->marca;
    }

    public function categoriaId()
    {
        return $this->categoriaId;
    }

    public function unidad()
    {
        return $this->unidad;
    }

    public function presentacion()
    {
        return $this->presentacion;
    }

    public function stock()
    {
        return $this->stock;
    }

    public function precio()
    {
        return $this->precio;
    }

    public function fechaCreacion()
    {
        return $this->fechaCreacion;
    }

    public function estado()
    {
        return $this->estado;
    }

    public function stockMinimo()
    {
        return $this->stockMinimo;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'marca' => $this->marca,
            'categoriaId' => $this->categoriaId,
            'categoriaNombre' => $this->categoria()->nombre(),
            'unidad' => $this->unidad,
            'presentacion' => $this->presentacion,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'stockMinimo' => $this->stockMinimo,
            'fechaCreacion' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y H:i:s'),
            'estado' => $this->estado
        ];
    }
}