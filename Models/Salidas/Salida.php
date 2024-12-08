<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../Materiales/Material.php';
require_once __DIR__ . '/SalidaLinea.php';

class Salida
{
    private $id;
    private $cliente_id;
    private $descripcion;
    private $usuarioId;
    private $fechaCreacion;
    private $lineas;

    public function __construct($id, $cliente_id, $descripcion, $usuarioId, $fechaCreacion, $lineas)
    {
        $this->id = $id;
        $this->cliente_id = $cliente_id;
        $this->descripcion = $descripcion;
        $this->usuarioId = $usuarioId;
        $this->fechaCreacion = $fechaCreacion;
        $this->lineas = $lineas;
    }

    public static function crear($cliente_id, $descripcion, $usuarioId, $lineas)
    {
        self::validarCamposVacios($cliente_id, $descripcion, $usuarioId);

        $conexionBD = (new ConexionBD())->getConexion();
        $salida = new Salida(
            null,
            $cliente_id,
            $descripcion,
            $usuarioId,
            date('Y-m-d H:i:s'),
            $lineas
        );

        $consultaCrearSalida = $conexionBD->prepare("
            INSERT INTO salidas (cliente_id, descripcion, usuario_id, fecha_creacion) VALUES 
            (?, ?, ?, ?, ?)
        ");

        // se guarda la salida en la base de datos
        $consultaCrearSalida->execute([
            $salida->cliente_id,
            $salida->descripcion,
            $salida->usuarioId,
            $salida->fechaCreacion
        ]);

        // se busca el id de la salida que se acaba de crear para relacionarla a las lineas
        $consultaId = $conexionBD->prepare("
            SELECT id FROM salidas ORDER BY id DESC LIMIT 1
        ");
        $consultaId->execute();

        $salidaId = $consultaId->fetch(PDO::FETCH_ASSOC);

        $salidaConId = new Salida(
            $salidaId['id'],
            $salida->cliente_id,
            $salida->descripcion,
            $salida->usuarioId,
            $salida->fechaCreacion,
            $lineas
        );

        // se iteran las lineas para guardar cada una en la base de datos
        foreach ($salidaConId->lineas as $entradaLinea) {
            SalidaLinea::crear($salidaConId->id, $entradaLinea['materialId'], $entradaLinea['cantidad'], $entradaLinea['precio']);

            // se rebaja el stock del material seleccionado en la linea
            Material::getMaterial($entradaLinea['materialId'])->rebajarStock($entradaLinea['cantidad']);
        }
    }

    private static function validarCamposVacios($cliente_id, $descripcion, $usuarioId)
    {
        if (empty($cliente_id)) {
            throw new Exception("La relación con cliente no puede estar vacía.");
        }

        if (empty($descripcion)) {
            throw new Exception("La descripción no puede estar vacía.");
        }

        if (empty($usuarioId)) {
            throw new Exception("El usuario registrador no puede estar vacío.");
        }
    }
}