<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';

class Cliente
{
    private $id;
    private $nombre;
    private $apellido;
    private $tipoIdentificacion;
    private $numeroIdentificacion;
    private $telefono;
    private $direccion;
    private $fechaCreacion;
    private $estado;

    public function __construct($id, $nombre, $apellido, $tipoIdentificacion, $numeroIdentificacion, $telefono, $direccion, $fechaCreacion, $estado)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->tipoIdentificacion = $tipoIdentificacion;
        $this->numeroIdentificacion = $numeroIdentificacion;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->fechaCreacion = $fechaCreacion;
        $this->estado = $estado;
    }

    public static function crear($nombre, $apellido, $tipoIdentificacion, $numeroIdentificacion, $telefono, $direccion, $estado)
    {
        date_default_timezone_set('America/Caracas');

        self::validarIdentificacion($tipoIdentificacion, $numeroIdentificacion);
        self::validarTelefono($telefono);

        $clientePorIdentificacion = self::getClientePorIdentificacion($tipoIdentificacion, $numeroIdentificacion);

        if (!empty($clientePorIdentificacion)) {
            $mensaje = '';

            switch ($tipoIdentificacion) {
                case 'cedula':
                    $mensaje = "La cédula {$numeroIdentificacion} ya está en uso.";
                    break;
                case 'pasaporte':
                    $mensaje = "El pasaporte {$numeroIdentificacion} ya está en uso.";
                    break;
                case 'rif':
                    $mensaje = "El rif {$numeroIdentificacion} ya está en uso.";
                    break;
            }

            throw new Exception($mensaje);
        }

        $cliente = new Cliente(
            null,
            $nombre,
            $apellido,
            $tipoIdentificacion,
            $numeroIdentificacion,
            $telefono,
            $direccion,
            date('Y-m-d H:i:s'),
            $estado
        );

        $consultaCrearCliente = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO clientes(nombre, apellido, tipo_identificacion, numero_identificacion, telefono, direccion, fecha_creacion, estado) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $consultaCrearCliente->execute([
            $cliente->nombre,
            $cliente->apellido,
            $cliente->tipoIdentificacion,
            $cliente->numeroIdentificacion,
            $cliente->telefono,
            $cliente->direccion,
            $cliente->fechaCreacion,
            $cliente->estado
        ]);
    }

    public static function getClientePorIdentificacion($tipoIdentificacion, $numeroIdentificacion)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre, apellido, tipo_identificacion, numero_identificacion, telefono, direccion, fecha_creacion, estado 
            FROM clientes WHERE tipo_identificacion = ? AND numero_identificacion = ?
        ");
        $consulta->execute([$tipoIdentificacion, $numeroIdentificacion]);
        $cliente = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($cliente)) {
            return null;
        }

        return new Cliente(
            $cliente['id'],
            $cliente['nombre'],
            $cliente['apellido'],
            $cliente['tipo_identificacion'],
            $cliente['numero_identificacion'],
            $cliente['telefono'],
            $cliente['direccion'],
            $cliente['fecha_creacion'],
            $cliente['estado']
        );
    }

    public static function editar($id, $nombre, $apellido, $tipoIdentificacion, $numeroIdentificacion, $telefono, $direccion, $estado)
    {
        $cliente = self::getCliente($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($cliente)) {
            throw new Exception("Cliente no encontrado.");
        }

        self::validarIdentificacion($tipoIdentificacion, $numeroIdentificacion);
        self::validarTelefono($telefono);

        if ($cliente->numeroIdentificacion !== $numeroIdentificacion) {
            $clientePorIdentificacion = self::getClientePorIdentificacion($tipoIdentificacion, $numeroIdentificacion);

            if (!empty($clientePorIdentificacion)) {
                $mensaje = '';

                switch ($tipoIdentificacion) {
                    case 'cedula':
                        $mensaje = "La cédula {$numeroIdentificacion} ya está en uso.";
                        break;
                    case 'pasaporte':
                        $mensaje = "El pasaporte {$numeroIdentificacion} ya está en uso.";
                        break;
                    case 'rif':
                        $mensaje = "El rif {$numeroIdentificacion} ya está en uso.";
                        break;
                }

                throw new Exception($mensaje);
            }
        }

        $clienteModificado = new Cliente(
            $id,
            $nombre,
            $apellido,
            $tipoIdentificacion,
            $numeroIdentificacion,
            $telefono,
            $direccion,
            null,
            $estado
        );

        $consultaEditarCliente = $conexionBaseDatos->prepare("
            UPDATE clientes 
            SET nombre = ?, apellido = ?, tipo_identificacion = ?, numero_identificacion = ?, telefono = ?, direccion = ?, estado = ?
            WHERE id = ?
        ");

        $consultaEditarCliente->execute([
            $clienteModificado->nombre,
            $clienteModificado->apellido,
            $clienteModificado->tipoIdentificacion,
            $clienteModificado->numeroIdentificacion,
            $clienteModificado->telefono,
            $clienteModificado->direccion,
            $clienteModificado->estado,
            $clienteModificado->id
        ]);
    }

    public static function cambiarEstado($id)
    {
        $cliente = self::getCliente($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($cliente)) {
            throw new Exception("Cliente no encontrado.");
        }

        if ($cliente->estado === 'activo') {
            $nuevoEstado = 'inactivo';
        } else {
            $nuevoEstado = 'activo';
        }

        $consultaEditarUsuario = $conexionBaseDatos->prepare("
            UPDATE clientes 
            SET estado = ?
            WHERE id = ?
        ");

        $consultaEditarUsuario->execute([
            $nuevoEstado,
            $id
        ]);
    }

    public static function getCliente($id)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre, apellido, tipo_identificacion, numero_identificacion, telefono, direccion, fecha_creacion, estado 
            FROM clientes WHERE id = ?
        ");
        $consulta->execute([$id]);
        $cliente = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($cliente)) {
            return null;
        }

        return new Cliente(
            $cliente['id'],
            $cliente['nombre'],
            $cliente['apellido'],
            $cliente['tipo_identificacion'],
            $cliente['numero_identificacion'],
            $cliente['telefono'],
            $cliente['direccion'],
            $cliente['fecha_creacion'],
            $cliente['estado']
        );
    }

    public static function getClientes($filtros, $orden)
    {
        $consultaClientes = "SELECT id, nombre, apellido, tipo_identificacion, numero_identificacion, telefono, direccion, fecha_creacion, estado FROM clientes";

        if (!empty($filtros)) {
            $consultaClientes .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['nombre', 'cedula', 'apellido', 'numero_identificacion', 'telefono', 'direccion'];

                if (in_array($key, $campos)) {
                    $operador = 'LIKE';
                } elseif ($key === 'fecha_desde') {
                    $key = 'fecha_creacion';
                    $operador = '>=';
                } elseif ($key === 'fecha_hasta') {
                    $key = 'fecha_creacion';
                    $operador = '<=';
                } else {
                    $operador = '=';
                }

                $consultaClientes .= "{$key} {$operador} '{$filtro}'";

                $iteracion++;

                if ($iteracion < count($filtros)) {
                    $consultaClientes .= " AND ";
                }
            }
        }

        $consultaClientes .= " ORDER BY id {$orden}";

        $consulta = (new ConexionBD())->getConexion()->prepare($consultaClientes);
        $consulta->execute();

        $clientesBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $clientes = [];

        foreach ($clientesBaseDatos as $cliente) {
            $clientes[] = new Cliente(
                $cliente['id'],
                $cliente['nombre'],
                $cliente['apellido'],
                $cliente['tipo_identificacion'],
                $cliente['numero_identificacion'],
                $cliente['telefono'],
                $cliente['direccion'],
                $cliente['fecha_creacion'],
                $cliente['estado']
            );
        }

        return $clientes;
    }

    public static function eliminar($id)
    {
        $cliente = self::getCliente($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($cliente)) {
            throw new Exception("Cliente no encontrado.");
        }

        $consultaEliminarUsuario = $conexionBaseDatos->prepare("
            DELETE FROM clientes
            WHERE id = ?
        ");

        $consultaEliminarUsuario->execute([$id]);
    }

    public static function validarIdentificacion($tipoIdentificacion, $numeroIdentificacion)
    {
        $tipoIdentificacion = trim($tipoIdentificacion);
        $numeroIdentificacion = trim($numeroIdentificacion);

        switch ($tipoIdentificacion) {
            case 'cedula':
                // La cédula debe ser un número de 6 a 8 dígitos
                if (!preg_match('/^\d{6,8}$/', $numeroIdentificacion)) {
                    throw new Exception("La cédula debe tener de 6 a 8 dígitos numéricos.");
                }
                break;

            case 'rif':
                // El RIF debe seguir el formato L########-# donde L puede ser V, E, J, G, P o C
                if (!preg_match('/^[VEJGP][0-9]{8}-[0-9]$/', $numeroIdentificacion)) {
                    throw new Exception("El rif debe seguir el formato L########-# donde L puede ser V, E, J, G, P o C.");
                }
                break;

            case 'pasaporte':
                // El pasaporte puede ser una combinación de letras y números, típicamente 6 a 9 caracteres
                if (!preg_match('/^[A-Z0-9]{6,9}$/i', $numeroIdentificacion)) {
                    throw new Exception("El pasaporte debe tener de 6 a 9 dígitos.");
                }
                break;

            default:
                // Tipo de identificación no válido
                return false;
        }
    }

    public static function validarTelefono($telefono)
    {
        $telefono = trim($telefono);

        if (!preg_match('/^\d{11}$/', $telefono)) {
            throw new Exception("El número de teléfono debe tener 11 dígitos numéricos.");
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'tipoIdentificacion' => $this->tipoIdentificacion,
            'numeroIdentificacion' => $this->numeroIdentificacion,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'fechaCreacion' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->fechaCreacion)->format('d/m/Y H:i:s'),
            'estado' => $this->estado
        ];
    }
}