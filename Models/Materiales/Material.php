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
    private $presentacion;
    private $stock;
    private $precio;
    private $fechaCreacion;
    private $estado;

    public function __construct($id, $nombre, $descripcion, $marca, $categoriaId, $unidad, $presentacion, $precio, $stock, $fechaCreacion, $estado)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->marca = $marca;
        $this->categoriaId = $categoriaId;
        $this->unidad = $unidad;
        $this->presentacion = $presentacion;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->fechaCreacion = $fechaCreacion;
        $this->estado = $estado;
    }

    public static function crear($nombre, $descripcion, $marca, $categoriaId, $unidad, $presentacion, $estado)
    {
        date_default_timezone_set('America/Caracas');

        self::validarCamposVacios($nombre, $marca, $categoriaId, $unidad, $presentacion, $estado);

        $material = new Material(
            null,
            $nombre,
            $descripcion,
            $marca,
            $categoriaId,
            $unidad,
            $presentacion,
            0,
            0,
            date('Y-m-d H:i:s'),
            $estado
        );

        $consultaCrearMaterial = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO materiales(nombre, descripcion, marca, categoria_id, unidad, presentacion, precio, stock, fecha_creacion, estado) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $consultaCrearMaterial->execute([
            $material->nombre,
            $material->descripcion,
            $material->marca,
            $material->categoriaId,
            $material->unidad,
            $material->presentacion,
            $material->precio,
            $material->stock,
            $material->fechaCreacion,
            $material->estado
        ]);
    }

    public static function editar($id, $nombre, $descripcion, $marca, $categoriaId, $unidad, $presentacion, $estado)
    {
        $material = self::getMaterial($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($material)) {
            throw new Exception("Material no encontrado.");
        }

        self::validarCamposVacios($nombre, $marca, $categoriaId, $unidad, $presentacion, $estado);

        $materialModificado = new Material(
            $id,
            $nombre,
            $descripcion,
            $marca,
            $categoriaId,
            $unidad,
            $presentacion,
            $material->precio,
            $material->stock,
            $material->fechaCreacion,
            $estado
        );

        $consultaEditarCliente = $conexionBaseDatos->prepare("
            UPDATE materiales 
            SET nombre = ?, descripcion = ?, marca = ?, categoria_id = ?, unidad = ?, presentacion = ?, estado = ?
            WHERE id = ?
        ");

        $consultaEditarCliente->execute([
            $materialModificado->nombre,
            $materialModificado->descripcion,
            $materialModificado->marca,
            $materialModificado->categoriaId,
            $materialModificado->unidad,
            $materialModificado->presentacion,
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
            SELECT id, nombre, descripcion, marca, categoria_id, unidad, presentacion, precio, stock, fecha_creacion, estado 
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
            $material['presentacion'],
            $material['precio'],
            $material['stock'],
            $material['fecha_creacion'],
            $material['estado']
        );
    }

    public static function getMateriales($filtros, $orden)
    {
        $consultaMateriales = "SELECT id, nombre, descripcion, marca, categoria_id, unidad, presentacion, precio, stock, fecha_creacion, estado FROM materiales";

        if (!empty($filtros)) {
            $consultaMateriales .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['nombre', 'presentacion', 'descripcion', 'marca'];

                if (in_array($key, $campos)) {
                    $operador = 'LIKE';
                } elseif ($key === 'fecha_desde') {
                    $key = 'fecha_creacion';
                    $operador = '>=';
                } elseif ($key === 'fecha_hasta') {
                    $key = 'fecha_creacion';
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
                $material['presentacion'],
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
            $this->stock,
            $this->id
        ]);
    }

    public function rebajarStock($cantidad)
    {
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        $this->stock = $this->stock - (float)$cantidad;

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

    public static function validarCamposVacios($nombre, $marca, $categoria_id, $unidad, $presentacion, $estado)
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

        if (empty($unidad)) {
            throw new Exception("La presentación no puede estar vacía.");
        }

        if (empty($estado)) {
            throw new Exception("El estado no puede estar vacío.");
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
            'presentacion' => $this->presentacion,
            'precio' => $this->precio,
            'stock' => $this->stock,
            'fechaCreacion' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y H:i:s'),
            'estado' => $this->estado
        ];
    }
}