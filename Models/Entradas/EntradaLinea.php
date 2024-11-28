<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';

class EntradaLinea
{
    private $id;
    private $entradaId;
    private $materialId;
    private $cantidad;
    private $precio;

    public function __construct($id, $entradaId, $materialId, $cantidad, $precio)
    {
        $this->id = $id;
        $this->entradaId = $entradaId;
        $this->materialId = $materialId;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
    }

    public static function crear($entradaId, $materialId, $cantidad, $precio)
    {
        self::validarCamposVacios($entradaId, $materialId, $cantidad, $precio);

        $entradaLinea = new EntradaLinea(
            null,
            $entradaId,
            $materialId,
            $cantidad,
            $precio
        );

        $consultaCrearEntradaLinea = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO entrada_lineas (entrada_id, material_id, cantidad, precio) VALUES 
            (?, ?, ?, ?)
        ");

        $consultaCrearEntradaLinea->execute([
            $entradaLinea->entradaId,
            $entradaLinea->materialId,
            $entradaLinea->cantidad,
            $entradaLinea->precio
        ]);
    }

    private static function validarCamposVacios($entradaId, $materialId, $cantidad, $precio)
    {
        if (empty($entradaId)) {
            throw new Exception("La relación con entrada no puede estar vacía.");
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