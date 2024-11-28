<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';

class SalidaLinea
{
    private $id;
    private $salidaId;
    private $materialId;
    private $cantidad;
    private $precio;

    public function __construct($id, $salidaId, $materialId, $cantidad, $precio)
    {
        $this->id = $id;
        $this->salidaId = $salidaId;
        $this->materialId = $materialId;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
    }

    public static function crear($salidaId, $materialId, $cantidad, $precio)
    {
        self::validarCamposVacios($salidaId, $materialId, $cantidad, $precio);

        $salidaLinea = new SalidaLinea(
            null,
            $salidaId,
            $materialId,
            $cantidad,
            $precio
        );

        $consultaCrearSalidaLinea = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO salida_lineas (salida_id, material_id, cantidad, precio) VALUES 
            (?, ?, ?, ?)
        ");

        $consultaCrearSalidaLinea->execute([
            $salidaLinea->salidaId,
            $salidaLinea->materialId,
            $salidaLinea->cantidad,
            $salidaLinea->precio
        ]);
    }

    private static function validarCamposVacios($salidaId, $materialId, $cantidad, $precio)
    {
        if (empty($salidaId)) {
            throw new Exception("La relación con salida no puede estar vacía.");
        }

        if (empty($materialId)) {
            throw new Exception("El material no puede estar vacío.");
        }

        if (empty($cantidad)) {
            throw new Exception("La cantidad no puede estar vacía.");
        }

        if (empty($precio)) {
            throw new Exception("el precio no puede estar vacío.");
        }
    }
}