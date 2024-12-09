<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../Usuarios/Usuario.php';
require_once __DIR__ . '/../Materiales/Material.php';
require_once __DIR__ . '/EntradaLinea.php';

class Entrada
{
    private $id;
    private $observacion;
    private $usuarioId;
    private $fechaCreacion;
    private $lineas;

    public function __construct($id, $observacion, $usuarioId, $fechaCreacion, $lineas)
    {
        $this->id = $id;
        $this->observacion = $observacion;
        $this->usuarioId = $usuarioId;
        $this->fechaCreacion = $fechaCreacion;
        $this->lineas = $lineas;
    }

    public static function crear($observacion, $usuarioId, $lineas)
    {
        self::validarCamposVacios($observacion, $usuarioId);

        $conexionBD = (new ConexionBD())->getConexion();
        $entrada = new Entrada(
            null,
            $observacion,
            $usuarioId,
            date('Y-m-d H:i:s'),
            $lineas
        );

        $consultaCrearEntrada = $conexionBD->prepare("
            INSERT INTO entradas (observacion, usuario_id, fecha_creacion) VALUES 
            (?, ?, ?)
        ");

        // se guarda la entrada en la base de datos
        $consultaCrearEntrada->execute([
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
            $material->cambiarPrecio($salidaLinea['precio']);
        }
    }

    private static function validarCamposVacios($observacion, $usuarioId)
    {
        if (empty($observacion)) {
            throw new Exception("La descripción no puede estar vacía.");
        }

        if (empty($usuarioId)) {
            throw new Exception("El usuario registrador no puede estar vacío.");
        }
    }

    public static function getEntrada($id)
    {
        $conexionBD = (new ConexionBD())->getConexion();

        $consultaEntrada = $conexionBD->prepare("
            SELECT id, observacion, usuario_id, fecha_creacion 
            FROM entradas WHERE id = ?
        ");
        $consultaEntrada->execute([$id]);
        $entrada = $consultaEntrada->fetch(PDO::FETCH_ASSOC);

        if (empty($entrada)) {
            throw new Exception('Entrada no encontrada.');
        }

        return new Entrada(
            $entrada['id'],
            $entrada['observacion'],
            $entrada['usuario_id'],
            $entrada['fecha_creacion'],
            EntradaLinea::getEntradaLineasDeEntrada($entrada['id'])
        );
    }

    public static function getEntradas($filtros, $orden)
    {
        $consultaEntradas = "SELECT id, observacion, usuario_id, fecha_creacion FROM entradas";

        if (!empty($filtros)) {
            $consultaEntradas .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['observacion'];

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

                $consultaEntradas .= "{$key} {$operador} '{$filtro}'";

                $iteracion++;

                if ($iteracion < count($filtros)) {
                    $consultaEntradas .= " AND ";
                }
            }
        }

        $consultaEntradas .= " ORDER BY id {$orden}";

        $consulta = (new ConexionBD())->getConexion()->prepare($consultaEntradas);
        $consulta->execute();

        $entradasBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $entradas = [];

        foreach ($entradasBaseDatos as $entrada) {
            $entradas[] = new Entrada(
                $entrada['id'],
                $entrada['observacion'],
                $entrada['usuario_id'],
                $entrada['fecha_creacion'],
                []
            );
        }

        return $entradas;
    }

    public function usuario()
    {
        return Usuario::getUsuario($this->usuarioId);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'observacion' => $this->observacion,
            'usuarioId' => $this->usuarioId,
            'usuarioFullNombre' => "{$this->usuario()->nombre()} {$this->usuario()->apellido()}",
            'fechaCreacion' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y H:i:s'),
            'lineas' => $this->lineas
        ];
    }
}