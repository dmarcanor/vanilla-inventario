<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../../Models/Categorias/Categoria.php';

class Material
{
    private $id;
    private $nombre;
    private $descripcion;
    private $marca;
    private $categoriaId;
    private $unidad;
    private $peso;
    private $stock;
    private $precio;
    private $fechaCreacion;
    private $estado;

    public function __construct($id, $nombre, $descripcion, $marca, $categoriaId, $unidad, $peso, $precio, $stock, $fechaCreacion, $estado)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->marca = $marca;
        $this->categoriaId = $categoriaId;
        $this->unidad = $unidad;
        $this->peso = $peso;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->fechaCreacion = $fechaCreacion;
        $this->estado = $estado;
    }

    public static function crear($nombre, $descripcion, $marca, $categoriaId, $unidad, $peso, $precio, $estado)
    {
        date_default_timezone_set('America/Caracas');

        self::validarCamposVacios($nombre, $marca, $categoriaId, $unidad, $estado);
        self::validarPeso($peso);
        self::validarPrecio($precio);

        $material = new Material(
            null,
            $nombre,
            $descripcion,
            $marca,
            $categoriaId,
            $unidad,
            $peso,
            $precio,
            0,
            date('Y-m-d H:i:s'),
            $estado
        );

        $consultaCrearMaterial = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO materiales(nombre, descripcion, marca, categoria_id, unidad, peso, precio, stock, fecha_creacion, estado) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $consultaCrearMaterial->execute([
            $material->nombre,
            $material->descripcion,
            $material->marca,
            $material->categoriaId,
            $material->unidad,
            $material->peso,
            $material->precio,
            $material->stock,
            $material->fechaCreacion,
            $material->estado
        ]);
    }

    public static function editar($id, $nombre, $descripcion, $marca, $categoriaId, $unidad, $peso, $precio, $estado)
    {
        $material = self::getMaterial($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($material)) {
            throw new Exception("Material no encontrado.");
        }

        self::validarCamposVacios($nombre, $marca, $categoriaId, $unidad, $estado);
        self::validarPeso($peso);
        self::validarPrecio($precio);

        $materialModificado = new Material(
            $id,
            $nombre,
            $descripcion,
            $marca,
            $categoriaId,
            $unidad,
            $peso,
            $precio,
            $material->stock,
            $material->fechaCreacion,
            $estado
        );

        $consultaEditarCliente = $conexionBaseDatos->prepare("
            UPDATE materiales 
            SET nombre = ?, descripcion = ?, marca = ?, categoria_id = ?, unidad = ?, peso = ?, precio = ?, estado = ?
            WHERE id = ?
        ");

        $consultaEditarCliente->execute([
            $materialModificado->nombre,
            $materialModificado->descripcion,
            $materialModificado->marca,
            $materialModificado->categoriaId,
            $materialModificado->unidad,
            $materialModificado->peso,
            $materialModificado->precio,
            $materialModificado->estado,
            $materialModificado->id
        ]);
    }

    public static function cambiarEstado($id)
    {
        $material = self::getMaterial($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($material)) {
            throw new Exception("Material no encontrado.");
        }

        if ($material->estado === 'activo') {
            $nuevoEstado = 'inactivo';
        } else {
            $nuevoEstado = 'activo';
        }

        $consultaEditarMaterial = $conexionBaseDatos->prepare("
            UPDATE materiales 
            SET estado = ?
            WHERE id = ?
        ");

        $consultaEditarMaterial->execute([
            $nuevoEstado,
            $id
        ]);
    }

    public static function getMaterial($id)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre, descripcion, marca, categoria_id, unidad, peso, precio, stock, fecha_creacion, estado 
            FROM materiales WHERE id = ?
        ");
        $consulta->execute([$id]);
        $material = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($material)) {
            throw new Exception('Material no encontrado.');
        }

        return new Material(
            $material['id'],
            $material['nombre'],
            $material['descripcion'],
            $material['marca'],
            $material['categoria_id'],
            $material['unidad'],
            $material['peso'],
            $material['precio'],
            $material['stock'],
            $material['fecha_creacion'],
            $material['estado']
        );
    }

    public static function getMateriales($filtros, $orden)
    {
        $consultaMateriales = "SELECT id, nombre, descripcion, marca, categoria_id, unidad, peso, precio, stock, fecha_creacion, estado FROM materiales";

        if (!empty($filtros)) {
            $consultaMateriales .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['nombre', 'descripcion', 'marca'];

                if (in_array($key, $campos)) {
                    $operador = 'LIKE';
                } elseif ($key === 'fecha_desde') {
                    $key = 'fecha_creacion';
                    $operador = '>=';
                } elseif ($key === 'fecha_hasta') {
                    $key = 'fecha_creacion';
                    $operador = '<=';
                } elseif ($key === 'peso_desde') {
                    $key = 'peso';
                    $operador = '>=';
                } elseif ($key === 'peso_hasta') {
                    $key = 'peso';
                    $operador = '<=';
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
                } else {
                    $operador = '=';
                }

                $consultaMateriales .= "{$key} {$operador} '{$filtro}'";

                $iteracion++;

                if ($iteracion < count($filtros)) {
                    $consultaMateriales .= " AND ";
                }
            }
        }

        $consultaMateriales .= " ORDER BY id {$orden}";

        $consulta = (new ConexionBD())->getConexion()->prepare($consultaMateriales);
        $consulta->execute();

        $materialesBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $materiales = [];

        foreach ($materialesBaseDatos as $material) {
            $materiales[] = new Material(
                $material['id'],
                $material['nombre'],
                $material['descripcion'],
                $material['marca'],
                $material['categoria_id'],
                $material['unidad'],
                $material['peso'],
                $material['precio'],
                $material['stock'],
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
            $cantidad,
            $this->id
        ]);
    }

    public static function validarCamposVacios($nombre, $marca, $categoria_id, $unidad, $estado)
    {
        if (empty($nombre)) {
            throw new Exception("El nombre no puede estar vacío.");
        }

        if (empty($marca)) {
            throw new Exception("La marca no no puede estar vacía.");
        }

        if (empty($categoria_id)) {
            throw new Exception("La categoría no puede estar vacía.");
        }

        if (empty($unidad)) {
            throw new Exception("La unidad no puede estar vacía.");
        }

        if (empty($estado)) {
            throw new Exception("El estado no puede estar vacío.");
        }
    }

    public static function validarPeso($peso)
    {
        if ((float)$peso < 0.01) {
            throw new Exception("El peso debe ser mayor o igual a 0,01.");
        }
    }

    public static function validarPrecio($precio)
    {
        if ((float)$precio < 0.01) {
            throw new Exception("El precio debe ser mayor o igual a 0,01.");
        }
    }

    public function categoria()
    {
        return Categoria::getCategoria($this->categoriaId);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'marca' => $this->marca,
            'categoriaId' => $this->categoriaId,
            'categoriaNombre' => $this->categoria()->nombre(),
            'unidad' => $this->unidad,
            'peso' => $this->peso,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'fechaCreacion' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y H:i:s'),
            'estado' => $this->estado
        ];
    }
}