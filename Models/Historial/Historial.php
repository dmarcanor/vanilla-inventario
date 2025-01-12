<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';
require_once __DIR__ . '/../../Models/Clientes/Cliente.php';
require_once __DIR__ . '/../../Models/Materiales/Material.php';
require_once __DIR__ . '/../../Models/Categorias/Categoria.php';
require_once __DIR__ . '/../../Models/Entradas/Entrada.php';
require_once __DIR__ . '/../../Models/Salidas/Salida.php';

class Historial
{
    private $id;
    private $usuarioId;
    private $tipoAccion;
    private $tipoEntidad;
    private $entidadId;
    private $cambio;
    private $fecha;

    public function __construct($id, $usuarioId, $tipoAccion, $tipoEntidad, $entidadId, $cambio, $fecha)
    {
        $this->id = $id;
        $this->usuarioId = $usuarioId;
        $this->tipoAccion = $tipoAccion;
        $this->tipoEntidad = $tipoEntidad;
        $this->entidadId = $entidadId;
        $this->cambio = $cambio;
        $this->fecha = $fecha;
    }

    public static function getHistorialUsuarios($filtros, $orden)
    {
        $consultaHistorial = "SELECT * FROM usuarios_historial";

        if (!empty($filtros)) {
            $consultaHistorial .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = [];
                $operador = in_array($key, $campos) ? 'LIKE' : '=';

                if ($key === 'fecha_desde') {
                    $key = 'fecha';
                    $operador = '>=';
                } elseif ($key === 'fecha_hasta') {
                    $key = 'fecha';
                    $operador = '<=';
                }

                $consultaHistorial .= "{$key} {$operador} '{$filtro}'";

                $iteracion++;

                if ($iteracion < count($filtros)) {
                    $consultaHistorial .= " AND ";
                }
            }
        }

        $consultaHistorial .= " ORDER BY id {$orden}";

        $consulta = (new ConexionBD())->getConexion()->prepare($consultaHistorial);
        $consulta->execute();

        $historialBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        foreach ($historialBaseDatos as $historial) {
            $usuarios[] = new Historial(
                $historial['id'],
                $historial['usuario_id'],
                $historial['tipo_accion'],
                $historial['tipo_entidad'],
                $historial['entidad_id'],
                $historial['cambio'],
                $historial['fecha']
            );
        }

        return $usuarios;
    }

    private function usuario()
    {
        return Usuario::getUsuario($this->usuarioId);
    }

    private function getEntidad()
    {
        if ($this->tipoEntidad === 'Usuario') {
            return Usuario::getUsuario($this->entidadId);
        }

        if ($this->tipoEntidad === 'Cliente') {
            return Cliente::getCliente($this->entidadId);
        }

        if ($this->tipoEntidad === 'Material') {
            return Material::getMaterial($this->entidadId);
        }

        if ($this->tipoEntidad === 'Categoria') {
            return Categoria::getCategoria($this->entidadId);
        }

        if ($this->tipoEntidad === 'Entrada') {
            return Entrada::getEntrada($this->entidadId);
        }

        if ($this->tipoEntidad === 'Salida') {
            return Salida::getSalida($this->entidadId);
        }

        return null;
    }

    private function getEnlaceEntidad()
    {
        $entidad = $this->getEntidad();

        if ($this->tipoEntidad === 'Usuario') {
            return "{$entidad->nombre()} {$entidad->apellido()}";
        }

        if ($this->tipoEntidad === 'Cliente') {
            return "{$entidad->nombre()} {$entidad->apellido()}";
        }

        if ($this->tipoEntidad === 'Material') {
            return $entidad->nombre();
        }

        if ($this->tipoEntidad === 'Categoria') {
            return $entidad->nombre();
        }

        if ($this->tipoEntidad === 'Entrada') {
            return $entidad->numeroEntrada();
        }

        if ($this->tipoEntidad === 'Salida') {
            return $entidad->id();
        }

        return '';
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'usuarioId' => $this->usuarioId,
            'usuario' => "{$this->usuario()->nombre()} {$this->usuario()->apellido()}",
            'tipoAccion' => $this->tipoAccion,
            'tipoEntidad' => $this->tipoEntidad,
            'entidadId' => $this->entidadId,
            'entidad' => $this->getEnlaceEntidad(),
            'cambio' => $this->cambio,
            'fecha' =>  DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fecha)->format('d/m/Y h:i:sA'),
        ];
    }
}