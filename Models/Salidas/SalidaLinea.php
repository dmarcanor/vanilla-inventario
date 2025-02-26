<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../../Models/Materiales/Material.php';

class SalidaLinea
{
    private $id;
    private $salidaId;
    private $materialId;
    private $cantidad;
    private $tipoPrecio;
    private $precio;

    public function __construct($id, $salidaId, $materialId, $cantidad, $tipoPrecio, $precio)
    {
        $this->id = $id;
        $this->salidaId = $salidaId;
        $this->materialId = $materialId;
        $this->cantidad = $cantidad;
        $this->tipoPrecio = $tipoPrecio;
        $this->precio = $precio;
    }

    public function id()
    {
        return $this->id;
    }

    public static function crear($salidaId, $materialId, $cantidad, $tipoPrecio, $precio)
    {
        date_default_timezone_set('America/Caracas');

        self::validarCamposVacios($salidaId, $materialId, $cantidad, $tipoPrecio, $precio);

        $salidaLinea = new SalidaLinea(
            null,
            $salidaId,
            $materialId,
            $cantidad,
            $tipoPrecio,
            $precio
        );

        $consultaCrearSalidaLinea = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO salida_lineas (salida_id, material_id, cantidad, tipo_precio, precio) VALUES 
            (?, ?, ?, ?, ?)
        ");

        $consultaCrearSalidaLinea->execute([
            $salidaLinea->salidaId,
            $salidaLinea->materialId,
            $salidaLinea->cantidad,
            $salidaLinea->tipoPrecio,
            $salidaLinea->precio
        ]);
    }

    public function eliminar($id)
    {
        // se debe devolver la cantidad de material al stock
        $this->material()->incrementarStock($this->cantidad);

        // se elimina la linea de la base de datos
        $dbConexion = (new ConexionBD())->getConexion();
        $consulta = $dbConexion->prepare("
            DELETE FROM salida_lineas WHERE id = ?
        ");

        $consulta->execute([$id]);
    }

    public static function getSalidaLineasDeSalida($entradaId)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT * FROM salida_lineas WHERE salida_id = ?
        ");

        $consulta->execute([$entradaId]);

        $lineasBaseDeDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $lineas = [];

        foreach ($lineasBaseDeDatos as $lineaBaseDeDatos) {
            $linea = new SalidaLinea(
                $lineaBaseDeDatos['id'],
                $lineaBaseDeDatos['salida_id'],
                $lineaBaseDeDatos['material_id'],
                $lineaBaseDeDatos['cantidad'],
                $lineaBaseDeDatos['tipo_precio'],
                $lineaBaseDeDatos['precio']
            );

            $lineas[] = $linea;
        }

        return $lineas;
    }

    private static function validarCamposVacios($salidaId, $materialId, $cantidad, $tipoPrecio, $precio)
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

        if (empty($tipoPrecio)) {
            throw new Exception("el tipo de precio no puede estar vacío.");
        }

        if (empty($precio)) {
            throw new Exception("el precio no puede estar vacío.");
        }
    }

    public function material()
    {
        return Material::getMaterial($this->materialId);
    }

    public function cantidad()
    {
        return $this->cantidad;
    }

    public function tipoPrecio()
    {
        return $this->tipoPrecio;
    }

    public function precio()
    {
        return $this->precio;
    }

    public function precioTotal()
    {
        return $this->cantidad * $this->precio;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'salidaId' => $this->salidaId,
            'materialId' => $this->materialId,
            'cantidad' => $this->cantidad,
            'tipoPrecio' => $this->tipoPrecio,
            'precio' => $this->precio,
            'precioTotal' => $this->precioTotal()
        ];
    }
}