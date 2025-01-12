<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../../Models/Materiales/Material.php';

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

    public static function crear($nombre, $descripcion, $estado, $usuarioSesion)
    {
        date_default_timezone_set('America/Caracas');

        self::validarCamposVacios($nombre, $descripcion, $estado);
        self::validarCategoriaDuplicada($nombre);

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

        $consultaCategoriaId = (new ConexionBD())->getConexion()->prepare("SELECT id FROM categorias ORDER BY id DESC LIMIT 1");
        $consultaCategoriaId->execute();
        $categoriaId = $consultaCategoriaId->fetch(PDO::FETCH_ASSOC);

        $nuevaCategoria = new Categoria(
            $categoriaId['id'],
            $categoria->nombre,
            $categoria->descripcion,
            $categoria->fechaCreacion,
            $categoria->estado
        );

        self::guardarHistorial($usuarioSesion, $nuevaCategoria, null);
    }

    public static function editar($id, $nombre, $descripcion, $estado, $usuarioSesion)
    {
        date_default_timezone_set('America/Caracas');

        $categoriaOriginal = self::getCategoria($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($categoriaOriginal)) {
            throw new Exception("Categoría no encontrada.");
        }

        self::validarCamposVacios($nombre, $descripcion, $estado);

        if ($categoriaOriginal->nombre !== $nombre) {
            self::validarCategoriaDuplicada($nombre);
        }

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

        self::guardarHistorial($usuarioSesion, $categoriaOriginal, $categoriaModificada);
    }

    private static function guardarHistorial($usuarioSesion, $usuarioOriginal, $usuarioModificado)
    {
        date_default_timezone_set('America/Caracas');

        $conexionBaseDatos = (new ConexionBD())->getConexion();
        $cambios = [];

        if (empty($usuarioModificado)) {
            $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

            $consultaHistorial->execute([
                $usuarioSesion,
                'Creado',
                'Categoria',
                $usuarioOriginal->id,
                'Categoría creada',
                date('Y-m-d H:i:s')
            ]);

            return;
        }

        if ($usuarioOriginal->nombre !== $usuarioModificado->nombre) {
            $cambios[] = "Nombre: {$usuarioOriginal->nombre} -> {$usuarioModificado->nombre}";
        }

        if ($usuarioOriginal->descripcion !== $usuarioModificado->descripcion) {
            $cambios[] = "Descripción: {$usuarioOriginal->descripcion} -> {$usuarioModificado->descripcion}";
        }

        if ($usuarioOriginal->estado !== $usuarioModificado->estado) {
            $cambios[] = "Estado: {$usuarioOriginal->estado} -> {$usuarioModificado->estado}";
        }

        foreach ($cambios as $cambio) {
            $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

            $consultaHistorial->execute([
                $usuarioSesion,
                'Cambio',
                'Categoria',
                $usuarioModificado->id,
                $cambio,
                date('Y-m-d H:i:s')
            ]);
        }
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

    public static function cambiarEstado($id, $usuarioSesion)
    {
        $categoriaOriginal = self::getCategoria($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($categoriaOriginal)) {
            throw new Exception("Categoría no encontrado.");
        }

        if ($categoriaOriginal->estado === 'activo') {
            $nuevoEstado = 'inactivo';
        } else {
            $nuevoEstado = 'activo';
        }

        if ($nuevoEstado === 'inactivo') {
            $filtros = ['categoria_id' => $id, 'estado' => 'activo'];
            $orden = 'ASC';
            $ordenCampo = 'id';
            $limit = 0;
            $tieneMaterialesActivos = Material::getMateriales($filtros, $orden, $ordenCampo, $limit);

            if (!empty($tieneMaterialesActivos)) {
                throw new Exception("No se puede desactivar la categoría porque tiene materiales activos.");
            }
        }

        $categoriaModificada = new Categoria(
            $categoriaOriginal->id,
            $categoriaOriginal->nombre,
            $categoriaOriginal->descripcion,
            $categoriaOriginal->fechaCreacion,
            $nuevoEstado
        );

        $consultaEditarCategoria = $conexionBaseDatos->prepare("
            UPDATE categorias 
            SET estado = ?
            WHERE id = ?
        ");

        $consultaEditarCategoria->execute([
            $nuevoEstado,
            $id
        ]);

        self::guardarHistorial($usuarioSesion, $categoriaOriginal, $categoriaModificada);
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

    public static function getCategorias($filtros, $orden, $ordenCampo)
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

        $consultaCategorias .= " ORDER BY {$ordenCampo} {$orden}";

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

    public static function validarCategoriaDuplicada($nombre)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id FROM categorias WHERE nombre = ?
        ");
        $consulta->execute([$nombre]);

        if ($consulta->rowCount() > 0) {
            throw new Exception("Ya existe la categoría {$nombre}.");
        }
    }

    public function id()
    {
        return $this->id;
    }

    public function descripcion()
    {
        return $this->descripcion;
    }

    public function fechaCreacion()
    {
        return $this->fechaCreacion;
    }

    public function estado()
    {
        return $this->estado;
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