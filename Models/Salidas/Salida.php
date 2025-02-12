<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';
require_once __DIR__ . '/../Materiales/Material.php';
require_once __DIR__ . '/../Usuarios/Usuario.php';
require_once __DIR__ . '/../Clientes/Cliente.php';
require_once __DIR__ . '/SalidaLinea.php';

class Salida
{
    private $id;
    private $cliente_id;
    private $observacion;
    private $usuarioId;
    private $fechaCreacion;
    private $lineas;

    public function __construct($id, $cliente_id, $observacion, $usuarioId, $fechaCreacion, $lineas)
    {
        $this->id = $id;
        $this->cliente_id = $cliente_id;
        $this->observacion = $observacion;
        $this->usuarioId = $usuarioId;
        $this->fechaCreacion = $fechaCreacion;
        $this->lineas = $lineas;
    }

    public function usuario()
    {
        return Usuario::getUsuario($this->usuarioId);
    }

    public function cliente()
    {
        return Cliente::getCliente($this->cliente_id);
    }

    public static function crear($cliente_id, $observacion, $usuarioId, $lineas, $usuarioSesion)
    {
        date_default_timezone_set('America/Caracas');

        self::validarCamposVacios($cliente_id, $usuarioId);

        foreach ($lineas as $linea) {
            $material = Material::getMaterial($linea['materialId']);
            $stockDespuesDeSalida = $material->stock() - $linea['cantidad'];

            if ($stockDespuesDeSalida < 0) {
                throw new Exception("No hay suficiente stock de {$material->nombre()} - {$material->descripcion()} - {$material->marca()->nombre()} para realizar la salida.");
            }
        }

        $conexionBD = (new ConexionBD())->getConexion();
        $salida = new Salida(
            null,
            $cliente_id,
            $observacion,
            $usuarioId,
            date('Y-m-d H:i:s'),
            $lineas
        );

        $consultaCrearSalida = $conexionBD->prepare("
            INSERT INTO salidas (cliente_id, observacion, usuario_id, fecha_creacion) VALUES 
            (?, ?, ?, ?)
        ");

        // se guarda la salida en la base de datos
        $consultaCrearSalida->execute([
            $salida->cliente_id,
            $salida->observacion,
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
            $salida->observacion,
            $salida->usuarioId,
            $salida->fechaCreacion,
            $lineas
        );

        // se iteran las lineas para guardar cada una en la base de datos
        foreach ($salidaConId->lineas as $entradaLinea) {
            SalidaLinea::crear($salidaConId->id, $entradaLinea['materialId'], $entradaLinea['cantidad'], $entradaLinea['tipoPrecio'], $entradaLinea['precio']);

            // se rebaja el stock del material seleccionado en la linea
            Material::getMaterial($entradaLinea['materialId'])->rebajarStock($entradaLinea['cantidad']);
        }

        self::guardarHistorial($usuarioSesion, $salidaConId);
    }

    private static function guardarHistorial($usuarioSesion, $salida)
    {
        date_default_timezone_set('America/Caracas');

        $conexionBaseDatos = (new ConexionBD())->getConexion();

        $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

        $consultaHistorial->execute([
            $usuarioSesion,
            'Creado',
            'Salida',
            $salida->id,
            'Salida creada',
            date('Y-m-d H:i:s')
        ]);
    }

    public static function eliminar($id)
    {
        $salida = self::getSalida($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($salida)) {
            throw new Exception("Salida no encontrada.");
        }

        // se eliminan las lineas de la salida
        foreach ($salida->lineas as $linea) {
            $linea->eliminar($linea->id());
        }

        // se elimina la salida
        $consultaEliminarSalida = $conexionBaseDatos->prepare("
            DELETE FROM salidas
            WHERE id = ?
        ");

        $consultaEliminarSalida->execute([$id]);
    }

    public static function getSalida($id)
    {
        $conexionBD = (new ConexionBD())->getConexion();

        $consultaSalida = $conexionBD->prepare("
            SELECT id, observacion, usuario_id, cliente_id, fecha_creacion 
            FROM salidas WHERE id = ?
        ");
        $consultaSalida->execute([$id]);
        $salida = $consultaSalida->fetch(PDO::FETCH_ASSOC);

        if (empty($salida)) {
            throw new Exception('Salida no encontrada.');
        }

        return new Salida(
            $salida['id'],
            $salida['cliente_id'],
            $salida['observacion'],
            $salida['usuario_id'],
            $salida['fecha_creacion'],
            SalidaLinea::getSalidaLineasDeSalida($salida['id'])
        );
    }

    public static function getSalidas($filtros, $orden, $limit, $ordenCampo)
    {
        $consultaSalidas = "SELECT salidas.id, salidas.observacion, salidas.cliente_id, salidas.usuario_id, salidas.fecha_creacion, count(salida_lineas.id) AS count FROM salidas
            LEFT JOIN salida_lineas ON salidas.id = salida_lineas.salida_id
            LEFT JOIN materiales ON salida_lineas.material_id = materiales.id
        ";

        if (!empty($filtros)) {
            $consultaSalidas .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['observacion'];

                if (in_array($key, $campos)) {
                    $operador = 'LIKE';
                } elseif ($key === 'id') {
                    $key = 'salidas.id';
                    $operador = '=';
                } elseif ($key === 'fecha_desde') {
                    $key = 'salidas.fecha_creacion';
                    $operador = '>=';
                } elseif ($key === 'fecha_hasta') {
                    $key = 'salidas.fecha_creacion';
                    $operador = '<=';
                } elseif ($key === 'material') {
                    $key = 'salida_lineas.material_id';
                    $operador = '=';
                } elseif ($key === 'categoria') {
                    $key = 'materiales.categoria_id';
                    $operador = '=';
                } else {
                    $operador = '=';
                }

                $consultaSalidas .= "{$key} {$operador} '{$filtro}'";

                $iteracion++;

                if ($iteracion < count($filtros)) {
                    $consultaSalidas .= " AND ";
                }
            }
        }

        if ($ordenCampo == 'cantidadMateriales') {
            $ordenCampo = 'count';
        }

        $consultaSalidas .= " GROUP BY salidas.id ORDER BY {$ordenCampo} {$orden}";

        if ($limit > 0) {
            $consultaSalidas .= " LIMIT {$limit}";
        }

        $consulta = (new ConexionBD())->getConexion()->prepare($consultaSalidas);
        $consulta->execute();

        $salidasBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $salidas = [];

        foreach ($salidasBaseDatos as $salida) {
            $salidas[] = new Salida(
                $salida['id'],
                $salida['cliente_id'],
                $salida['observacion'],
                $salida['usuario_id'],
                $salida['fecha_creacion'],
                SalidaLinea::getSalidaLineasDeSalida($salida['id'])
            );
        }

        return $salidas;
    }

    private static function validarCamposVacios($cliente_id, $usuarioId)
    {
        if (empty($cliente_id)) {
            throw new Exception("La relación con cliente no puede estar vacía.");
        }

        if (empty($usuarioId)) {
            throw new Exception("El usuario registrador no puede estar vacío.");
        }
    }

    public function lineasArray()
    {
        $lineas = [];

        foreach ($this->lineas as $linea) {
            $lineas[] = $linea->toArray();
        }

        return $lineas;
    }

    public function id()
    {
        return $this->id;
    }

    public function observacion()
    {
        return $this->observacion;
    }

    public function lineas()
    {
        return $this->lineas;
    }

    public function fechaCreacion()
    {
        return $this->fechaCreacion;
    }

    public function precioTotal()
    {
        $total = 0;

        foreach ($this->lineas as $linea) {
            $total += $linea->precioTotal();
        }

        return $total;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'observacion' => $this->observacion,
            'usuarioId' => $this->usuarioId,
            'usuarioFullNombre' => "{$this->usuario()->nombre()} {$this->usuario()->apellido()}",
            'clienteId' => $this->cliente_id,
            'clienteFullNombre' => "{$this->cliente()->nombre()} {$this->cliente()->apellido()}",
            'fechaCreacion' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y h:i:sA'),
            'fechaCreacionSinHora' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y'),
            'lineas' => $this->lineasArray(),
            'precioTotal' => $this->precioTotal()
        ];
    }
}