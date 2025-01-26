<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';


class Marca
{
    private $id;
    private $nombre;

    public function __construct($id, $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public static function crear($nombre, $usuarioSesion)
    {
        date_default_timezone_set('America/Caracas');

        self::validarCamposVacios($nombre);
        self::validarMarcaDuplicada($nombre);

        $marca = new Marca(
            null,
            $nombre
        );

        $consultaCrearMarca = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO marcas(nombre) VALUES 
            (?)
        ");

        $consultaCrearMarca->execute([
            $marca->nombre
        ]);

        $consultaMarcaId = (new ConexionBD())->getConexion()->prepare("SELECT id FROM marcas ORDER BY id DESC LIMIT 1");
        $consultaMarcaId->execute();
        $marcaId = $consultaMarcaId->fetch(PDO::FETCH_ASSOC);

        $nuevaMarca = new Marca(
            $marcaId['id'],
            $marca->nombre
        );

        self::guardarHistorial($usuarioSesion, $nuevaMarca, null);

        return $nuevaMarca;
    }

    private static function validarCamposVacios($nombre)
    {
        if (empty($nombre)) {
            throw new Exception('El campo nombre no puede estar vacío');
        }
    }

    private static function validarMarcaDuplicada($nombre)
    {
        $consultaMarcaDuplicada = (new ConexionBD())->getConexion()->prepare("SELECT nombre FROM marcas WHERE nombre = ?");
        $consultaMarcaDuplicada->execute([$nombre]);
        $marcaDuplicada = $consultaMarcaDuplicada->fetch(PDO::FETCH_ASSOC);

        if ($marcaDuplicada) {
            throw new Exception("Ya existe una marca con el nombre {$nombre}");
        }
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
                'Marca',
                $usuarioOriginal->id,
                'Marca creada',
                date('Y-m-d H:i:s')
            ]);

            return;
        }

        if ($usuarioOriginal->nombre !== $usuarioModificado->nombre) {
            $cambios[] = "Nombre: {$usuarioOriginal->nombre} -> {$usuarioModificado->nombre}";
        }

        foreach ($cambios as $cambio) {
            $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

            $consultaHistorial->execute([
                $usuarioSesion,
                'Cambio',
                'Marca',
                $usuarioModificado->id,
                $cambio,
                date('Y-m-d H:i:s')
            ]);
        }
    }

    public static function getMarca($id)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre
            FROM marcas WHERE id = ?
        ");
        $consulta->execute([$id]);
        $marca = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($marca)) {
            throw new Exception('Marca no encontrado.');
        }

        return new Marca(
            $marca['id'],
            $marca['nombre']
        );
    }

    public static function getMarcas()
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("SELECT * FROM marcas ORDER BY id DESC");
        $consulta->execute();

        $marcasBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $marcas = [];

        foreach ($marcasBaseDatos as $marca) {
            $marcas[] = new Marca(
                $marca['id'],
                $marca['nombre']
            );
        }

        return $marcas;
    }

    public function id()
    {
        return $this->id;
    }

    public function nombre()
    {
        return $this->nombre;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre
        ];
    }
}