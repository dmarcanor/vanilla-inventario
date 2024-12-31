<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../../Models/Usuarios/Usuario.php';
require_once __DIR__ . '/../../Models/Clientes/Cliente.php';
require_once __DIR__ . '/../../Models/Materiales/Material.php';

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

        return null;
    }

    private function getEnlaceEntidad()
    {
        $entidad = $this->getEntidad();

        if ($this->tipoEntidad === 'Usuario') {
            return "<a href='/vanilla-inventario/Views/Usuarios/editar.php?id={$this->entidadId}'>{$entidad->nombre()} {$entidad->apellido()}</a>";
        }

        if ($this->tipoEntidad === 'Cliente') {
            return "<a href='/vanilla-inventario/Views/Clientes/editar.php?id={$this->entidadId}'>{$entidad->nombre()} {$entidad->apellido()}</a>";
        }

        if ($this->tipoEntidad === 'Material') {
            return "<a href='/vanilla-inventario/Views/Materiales/editar.php?id={$this->entidadId}'>{$entidad->nombre()}</a>";
        }

        return '';
    }

    private function getEnlaceUsuario()
    {
        $usuario = $this->usuario();

        return "<a href='/vanilla-inventario/Views/Usuarios/editar.php?id={$this->usuarioId}'>{$usuario->nombre()} {$usuario->apellido()}</a>";
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'usuarioId' => $this->usuarioId,
            'usuario' => $this->getEnlaceUsuario(),
            'tipoAccion' => $this->tipoAccion,
            'tipoEntidad' => $this->tipoEntidad,
            'entidadId' => $this->entidadId,
            'entidad' => $this->getEnlaceEntidad(),
            'cambio' => $this->cambio,
            'fecha' =>  DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fecha)->format('d/m/Y H:i:s'),
        ];
    }
}