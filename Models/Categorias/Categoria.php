<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';

final class Categoria
{
    private $id;
    private $nombre;
    private $descripcion;
    private $estado;

    public function __construct($id, $nombre, $descripcion, $estado)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
    }

    public function nombre()
    {
        return $this->nombre;
    }

    public static function getCategoria($id)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre, descripcion, estado 
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
            $categoria['estado']
        );
    }
}