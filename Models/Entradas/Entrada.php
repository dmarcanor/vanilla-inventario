<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../Materiales/Material.php';
require_once __DIR__ . '/EntradaLinea.php';

class Entrada
{
    private $id;
    private $descripcion;
    private $usuarioId;
    private $estado;
    private $fechaCreacion;
    private $lineas;

    public function __construct($id, $descripcion, $usuarioId, $estado, $fechaCreacion, $lineas)
    {
        $this->id = $id;
        $this->descripcion = $descripcion;
        $this->usuarioId = $usuarioId;
        $this->estado = $estado;
        $this->fechaCreacion = $fechaCreacion;
        $this->lineas = $lineas;
    }

    public static function crear($descripcion, $usuarioId, $estado, $lineas)
    {
        self::validarCamposVacios($descripcion, $usuarioId, $estado);

        $conexionBD = (new ConexionBD())->getConexion();
        $entrada = new Entrada(
            null,
            $descripcion,
            $usuarioId,
            $estado,
            date('Y-m-d H:i:s'),
            $lineas
        );

        $consultaCrearEntrada = $conexionBD->prepare("
            INSERT INTO entradas (descripcion, usuario_id, fecha_creacion, estado) VALUES 
            (?, ?, ?, ?)
        ");

        $consultaCrearEntrada->execute([
            $entrada->descripcion,
            $entrada->usuarioId,
            $entrada->fechaCreacion,
            $entrada->estado
        ]);

        $consultaId = $conexionBD->prepare("
            SELECT id FROM entradas ORDER BY id DESC LIMIT 1
        ");
        $consultaId->execute();

        $entradaId = $consultaId->fetch(PDO::FETCH_ASSOC);

        $entradaConId = new Entrada(
            $entradaId['id'],
            $entrada->descripcion,
            $entrada->usuarioId,
            $entrada->estado,
            $entrada->fechaCreacion,
            $lineas
        );

        foreach ($entradaConId->lineas as $entradaLinea) {
            EntradaLinea::crear($entradaConId->id, $entradaLinea['materialId'], $entradaLinea['cantidad'], $entradaLinea['precio']);

            Material::getMaterial($entradaLinea['materialId'])->incrementarStock($entradaLinea['cantidad']);
        }
    }

    private static function validarCamposVacios($descripcion, $usuarioId, $estado)
    {
        if (empty($descripcion)) {
            throw new Exception("La descripción no puede estar vacía.");
        }

        if (empty($usuarioId)) {
            throw new Exception("El usuario registrador no puede estar vacío.");
        }

        if (empty($estado)) {
            throw new Exception("El estado no puede estar vacío.");
        }
    }
}