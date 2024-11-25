<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';

final class Categoria
{
    private $id;
    private $nombre;
    private $descripcion;
    private $fechaCreacion;
    private $estado;

    public function __construct($id, $nombre, $descripcion, $fechaCreacion, $estado)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fechaCreacion = $fechaCreacion;
        $this->estado = $estado;
    }

    public static function crear($nombre, $descripcion, $estado)
    {
        date_default_timezone_set('America/Caracas');

        self::validarCamposVacios($nombre, $descripcion, $estado);

        $categoria = new Categoria(
            null,
            $nombre,
            $descripcion,
            date('Y-m-d H:i:s'),
            $estado
        );

        $consultaCrearCategoria = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO categorias(nombre, descripcion, fecha_creacion, estado) VALUES 
            (?, ?, ?, ?)
        ");

        $consultaCrearCategoria->execute([
            $categoria->nombre,
            $categoria->descripcion,
            $categoria->fechaCreacion,
            $categoria->estado
        ]);
    }

    public static function editar($id, $nombre, $descripcion, $estado)
    {
        $categoria = self::getCategoria($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($categoria)) {
            throw new Exception("Categoría no encontrada.");
        }

        self::validarCamposVacios($nombre, $descripcion, $estado);

        $categoriaModificada = new Categoria(
            $id,
            $nombre,
            $descripcion,
            null,
            $estado
        );

        $consultaEditarCategoria = $conexionBaseDatos->prepare("
            UPDATE categorias 
            SET nombre = ?, descripcion = ?, estado = ?
            WHERE id = ?
        ");

        $consultaEditarCategoria->execute([
            $categoriaModificada->nombre,
            $categoriaModificada->descripcion,
            $categoriaModificada->estado,
            $categoriaModificada->id
        ]);
    }

    public static function eliminar($id)
    {
        $categoria = self::getCategoria($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($categoria)) {
            throw new Exception("Categoría no encontrada.");
        }

        $consultaEliminarCategoria = $conexionBaseDatos->prepare("
            UPDATE categorias SET eliminado = true
            WHERE id = ?
        ");

        $consultaEliminarCategoria->execute([$id]);
    }

    public function nombre()
    {
        return $this->nombre;
    }

    public static function cambiarEstado($id)
    {
        $categoria = self::getCategoria($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($categoria)) {
            throw new Exception("Categoría no encontrado.");
        }

        if ($categoria->estado === 'activo') {
            $nuevoEstado = 'inactivo';
        } else {
            $nuevoEstado = 'activo';
        }

        $consultaEditarCategoria = $conexionBaseDatos->prepare("
            UPDATE categorias 
            SET estado = ?
            WHERE id = ?
        ");

        $consultaEditarCategoria->execute([
            $nuevoEstado,
            $id
        ]);
    }

    public static function getCategoria($id)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre, descripcion, fecha_creacion, estado 
            FROM categorias WHERE id = ?
        ");
        $consulta->execute([$id]);
        $categoria = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($categoria)) {
            throw new Exception('Categoría no encontrado.');
        }

        return new Categoria(
            $categoria['id'],
            $categoria['nombre'],
            $categoria['descripcion'],
            $categoria['fecha_creacion'],
            $categoria['estado']
        );
    }

    public static function getCategorias($filtros, $orden)
    {
        $consultaCategorias = "SELECT id, nombre, descripcion, fecha_creacion, estado FROM categorias WHERE eliminado = 0";

        if (!empty($filtros)) {
            $consultaCategorias .= " AND ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['nombre', 'descripcion'];

                if (in_array($key, $campos)) {
                    $operador = 'LIKE';
                } elseif ($key === 'fecha_desde') {
                    $key = 'fecha_creacion';
                    $operador = '>=';
                } elseif ($key === 'fecha_hasta') {
                    $key = 'fecha_creacion';
                    $operador = '<=';
                } else {
                    $operador = '=';
                }

                $consultaCategorias .= "{$key} {$operador} '{$filtro}'";

                $iteracion++;

                if ($iteracion < count($filtros)) {
                    $consultaCategorias .= " AND ";
                }
            }
        }

        $consultaCategorias .= " ORDER BY id {$orden}";

        $consulta = (new ConexionBD())->getConexion()->prepare($consultaCategorias);
        $consulta->execute();

        $categoriasBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $clientes = [];

        foreach ($categoriasBaseDatos as $categoria) {
            $clientes[] = new Categoria(
                $categoria['id'],
                $categoria['nombre'],
                $categoria['descripcion'],
                $categoria['fecha_creacion'],
                $categoria['estado']
            );
        }

        return $clientes;
    }

    public static function validarCamposVacios($nombre, $descripcion, $estado)
    {
        if (empty($nombre)) {
            throw new Exception("El nombre no puede estar vacío.");
        }

        if (empty($descripcion)) {
            throw new Exception("La descripción no puede estar vacía.");
        }

        if (empty($estado)) {
            throw new Exception("El estado no puede estar vacío.");
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'fechaCreacion' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y H:i:s'),
            'estado' => $this->estado
        ];
    }
}