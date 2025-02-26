<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../Usuarios/Usuario.php';
require_once __DIR__ . '/../Materiales/Material.php';
require_once __DIR__ . '/EntradaLinea.php';

class Entrada
{
    private $id;
    private $numeroEntrada;
    private $observacion;
    private $usuarioId;
    private $fechaCreacion;
    private $lineas;

    public function __construct($id, $numeroEntrada, $observacion, $usuarioId, $fechaCreacion, $lineas)
    {
        $this->id = $id;
        $this->observacion = $observacion;
        $this->numeroEntrada = $numeroEntrada;
        $this->usuarioId = $usuarioId;
        $this->fechaCreacion = $fechaCreacion;
        $this->lineas = $lineas;
    }

    public static function crear($numeroEntrada, $observacion, $usuarioId, $lineas, $usuarioSesion)
    {
        date_default_timezone_set('America/Caracas');

        self::validarCamposVacios($numeroEntrada, $usuarioId);

        $entradaConNumero = self::getEntradaConNumero($numeroEntrada);

        if (!empty($entradaConNumero)) {
            throw new Exception("Ya existe una entrada con el número ingresado {$numeroEntrada}.");
        }

        $conexionBD = (new ConexionBD())->getConexion();
        $entrada = new Entrada(
            null,
            $numeroEntrada,
            $observacion,
            $usuarioId,
            date('Y-m-d H:i:s'),
            $lineas
        );

        $consultaCrearEntrada = $conexionBD->prepare("
            INSERT INTO entradas (numero_entrada, observacion, usuario_id, fecha_creacion) VALUES 
            (?, ?, ?, ?)
        ");

        // se guarda la entrada en la base de datos
        $consultaCrearEntrada->execute([
            $entrada->numeroEntrada,
            $entrada->observacion,
            $entrada->usuarioId,
            $entrada->fechaCreacion,
        ]);

        // se busca el id de la entrada que se acaba de crear para relacionarla a las lineas
        $consultaId = $conexionBD->prepare("
            SELECT id FROM entradas ORDER BY id DESC LIMIT 1
        ");
        $consultaId->execute();

        $entradaId = $consultaId->fetch(PDO::FETCH_ASSOC);

        $entradaConId = new Entrada(
            $entradaId['id'],
            $entrada->numeroEntrada,
            $entrada->observacion,
            $entrada->usuarioId,
            $entrada->fechaCreacion,
            $lineas
        );

        // se iteran las lineas para guardar cada una en la base de datos
        foreach ($entradaConId->lineas as $salidaLinea) {
            EntradaLinea::crear($entradaConId->id, $salidaLinea['materialId'], $salidaLinea['cantidad'], $salidaLinea['precio']);

            // se aumenta el stock del material seleccionado en la linea
            $material = Material::getMaterial($salidaLinea['materialId']);

            $material->incrementarStock($salidaLinea['cantidad']);
        }

        $consultaEntradaId = (new ConexionBD())->getConexion()->prepare("SELECT id FROM entradas ORDER BY id DESC LIMIT 1");
        $consultaEntradaId->execute();
        $entradaId = $consultaEntradaId->fetch(PDO::FETCH_ASSOC);

        $nuevaEntrada = new Entrada(
            $entradaId['id'],
            $entrada->numeroEntrada,
            $entrada->observacion,
            $entrada->usuarioId,
            $entrada->fechaCreacion,
            $lineas
        );

        self::guardarHistorial($usuarioSesion, $nuevaEntrada);
    }

    private static function guardarHistorial($usuarioSesion, $entrada)
    {
        date_default_timezone_set('America/Caracas');

        $conexionBaseDatos = (new ConexionBD())->getConexion();

        $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

        $consultaHistorial->execute([
            $usuarioSesion,
            'Creado',
            'Entrada',
            $entrada->id,
            'Entrada creada',
            date('Y-m-d H:i:s')
        ]);
    }

    public static function eliminar($id)
    {
        $entrada = self::getEntrada($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($entrada)) {
            throw new Exception("Entrada no encontrada.");
        }

        // se eliminan las lineas de la entrada
        foreach ($entrada->lineas as $linea) {
            $linea->eliminar($linea->id());
        }

        // se elimina la entrada
        $consultaEliminarCliente = $conexionBaseDatos->prepare("
            DELETE FROM entradas
            WHERE id = ?
        ");

        $consultaEliminarCliente->execute([$id]);
    }

    private static function validarCamposVacios($numeroEntrada, $usuarioId)
    {
        if (empty($numeroEntrada)) {
            throw new Exception("El número de la entrada no puede estar vacía.");
        }

        if (empty($usuarioId)) {
            throw new Exception("El usuario registrador no puede estar vacío.");
        }
    }

    public static function getEntrada($id)
    {
        $conexionBD = (new ConexionBD())->getConexion();

        $consultaEntrada = $conexionBD->prepare("
            SELECT id, numero_entrada, observacion, usuario_id, fecha_creacion 
            FROM entradas WHERE id = ?
        ");
        $consultaEntrada->execute([$id]);
        $entrada = $consultaEntrada->fetch(PDO::FETCH_ASSOC);

        if (empty($entrada)) {
            throw new Exception('Entrada no encontrada.');
        }

        return new Entrada(
            $entrada['id'],
            $entrada['numero_entrada'],
            $entrada['observacion'],
            $entrada['usuario_id'],
            $entrada['fecha_creacion'],
            EntradaLinea::getEntradaLineasDeEntrada($entrada['id'])
        );
    }

    public static function getEntradaConNumero($numeroEntrada)
    {
        $conexionBD = (new ConexionBD())->getConexion();

        $consultaEntrada = $conexionBD->prepare("
            SELECT id, numero_entrada, observacion, usuario_id, fecha_creacion 
            FROM entradas WHERE numero_entrada = ?
        ");
        $consultaEntrada->execute([$numeroEntrada]);
        $entrada = $consultaEntrada->fetch(PDO::FETCH_ASSOC);

        if (empty($entrada)) {
            return null;
        }

        return new Entrada(
            $entrada['id'],
            $entrada['numero_entrada'],
            $entrada['observacion'],
            $entrada['usuario_id'],
            $entrada['fecha_creacion'],
            EntradaLinea::getEntradaLineasDeEntrada($entrada['id'])
        );
    }

    public static function getEntradas($filtros, $orden, $limit, $ordenCampo)
    {
        $consultaEntradas = "
            SELECT entradas.id, entradas.numero_entrada, entradas.observacion, entradas.usuario_id, entradas.fecha_creacion FROM entradas
            LEFT JOIN entrada_lineas ON entradas.id = entrada_lineas.entrada_id
            LEFT JOIN materiales ON entrada_lineas.material_id = materiales.id
        ";

        if (!empty($filtros)) {
            $consultaEntradas .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['numero_entrada', 'observacion'];

                if (in_array($key, $campos)) {
                    $operador = 'LIKE';
                } elseif ($key === 'id') {
                    $key = 'entradas.id';
                    $operador = '=';
                } elseif ($key === 'fecha_desde') {
                    $key = 'entradas.fecha_creacion';
                    $operador = '>=';
                } elseif ($key === 'fecha_hasta') {
                    $key = 'entradas.fecha_creacion';
                    $operador = '<=';
                } elseif ($key === 'material') {
                    $key = 'entrada_lineas.material_id';
                    $operador = '=';
                } elseif ($key === 'categoria') {
                    $key = 'materiales.categoria_id';
                    $operador = '=';
                } else {
                    $operador = '=';
                }

                $consultaEntradas .= "{$key} {$operador} '{$filtro}'";

                $iteracion++;

                if ($iteracion < count($filtros)) {
                    $consultaEntradas .= " AND ";
                }
            }
        }

        $consultaEntradas .= " GROUP BY entradas.id ORDER BY {$ordenCampo} {$orden}";

        if ($limit > 0) {
            $consultaEntradas .= " LIMIT {$limit}";
        }

        $consulta = (new ConexionBD())->getConexion()->prepare($consultaEntradas);
        $consulta->execute();

        $entradasBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $entradas = [];

        foreach ($entradasBaseDatos as $entrada) {
            $entradas[] = new Entrada(
                $entrada['id'],
                $entrada['numero_entrada'],
                $entrada['observacion'],
                $entrada['usuario_id'],
                $entrada['fecha_creacion'],
                EntradaLinea::getEntradaLineasDeEntrada($entrada['id'])
            );
        }

        return $entradas;
    }

    public function usuario()
    {
        return Usuario::getUsuario($this->usuarioId);
    }

    public function numeroEntrada()
    {
        return $this->numeroEntrada;
    }

    public function observacion()
    {
        return $this->observacion;
    }

    public function fechaCreacion()
    {
        return $this->fechaCreacion;
    }

    public function lineas()
    {
        return $this->lineas;
    }

    public function lineasArray()
    {
        $lineas = [];

        foreach ($this->lineas as $linea) {
            $lineas[] = $linea->toArray();
        }

        return $lineas;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'numeroEntrada' => $this->numeroEntrada,
            'observacion' => $this->observacion,
            'usuarioId' => $this->usuarioId,
            'usuarioFullNombre' => "{$this->usuario()->nombre()} {$this->usuario()->apellido()}",
            'fechaCreacion' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y H:i:s'),
            'fechaCreacionSinHora' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y'),
            'lineas' => $this->lineasArray()
        ];
    }
}