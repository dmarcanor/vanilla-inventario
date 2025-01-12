<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../../Models/Materiales/Material.php';

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

    public function id()
    {
        return $this->id;
    }

    public function cantidad()
    {
        return $this->cantidad;
    }

    public function precio()
    {
        return $this->precio;
    }

    public static function crear($entradaId, $materialId, $cantidad, $precio)
    {
        date_default_timezone_set('America/Caracas');

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

    public function eliminar($id)
    {
        // se debe devolver la cantidad de material al stock
        $this->material()->rebajarStock($this->cantidad);

        // se elimina la linea de la base de datos
        $dbConexion = (new ConexionBD())->getConexion();
        $consulta = $dbConexion->prepare("
            DELETE FROM entrada_lineas WHERE id = ?
        ");

        $consulta->execute([$id]);
    }

    public function getEntradaLinea($id)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT * FROM entrada_lineas WHERE id = ?
        ");

        $consulta->execute([$id]);

        $lineaBaseDeDatos = $consulta->fetch(PDO::FETCH_ASSOC);

        return new EntradaLinea(
            $lineaBaseDeDatos['id'],
            $lineaBaseDeDatos['entrada_id'],
            $lineaBaseDeDatos['material_id'],
            $lineaBaseDeDatos['cantidad'],
            $lineaBaseDeDatos['precio']
        );
    }

    public static function getEntradaLineasDeEntrada($entradaId)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT * FROM entrada_lineas WHERE entrada_id = ?
        ");

        $consulta->execute([$entradaId]);

        $lineasBaseDeDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $lineas = [];

        foreach ($lineasBaseDeDatos as $lineaBaseDeDatos) {
            $linea = new EntradaLinea(
                $lineaBaseDeDatos['id'],
                $lineaBaseDeDatos['entrada_id'],
                $lineaBaseDeDatos['material_id'],
                $lineaBaseDeDatos['cantidad'],
                $lineaBaseDeDatos['precio']
            );

            $lineas[] = $linea;
        }

        return $lineas;
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

    public function material()
    {
        return Material::getMaterial($this->materialId);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'entradaId' => $this->entradaId,
            'materialId' => $this->materialId,
            'cantidad' => $this->cantidad,
            'precio' => $this->precio
        ];
    }
}