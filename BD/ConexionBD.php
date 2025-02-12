<?php

class ConexionBD
{
    private $host;
    private $bd_nombre;
    private $usuario;
    private $contrasenia;
    private $conexion;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->bd_nombre = 'inventario';
        $this->usuario = 'root';
        $this->contrasenia = '';

        $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->bd_nombre;charset=utf8", $this->usuario, $this->contrasenia);
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConexion()
    {
        return $this->conexion;
    }
}