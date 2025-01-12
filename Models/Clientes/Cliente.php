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

    public static function crear($nombre, $apellido, $tipoIdentificacion, $numeroIdentificacion, $telefono, $direccion, $estado, $usuarioSesion)
    {
        date_default_timezone_set('America/Caracas');

        self::validarIdentificacion($tipoIdentificacion, $numeroIdentificacion);
        self::validarTelefono($telefono);

        $clientePorIdentificacion = self::getClientePorIdentificacion($numeroIdentificacion);

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

        $consultaClienteId = (new ConexionBD())->getConexion()->prepare("SELECT id FROM clientes ORDER BY id DESC LIMIT 1");
        $consultaClienteId->execute();
        $clienteId = $consultaClienteId->fetch(PDO::FETCH_ASSOC);

        $nuevoCliente = new Cliente(
            $clienteId['id'],
            $cliente->nombre,
            $cliente->apellido,
            $cliente->tipoIdentificacion,
            $cliente->numeroIdentificacion,
            $cliente->telefono,
            $cliente->direccion,
            $cliente->fechaCreacion,
            $cliente->estado
        );

        self::guardarHistorial($usuarioSesion, $nuevoCliente, null);
    }

    public static function editar($id, $nombre, $apellido, $tipoIdentificacion, $numeroIdentificacion, $telefono, $direccion, $estado, $usuarioSesion)
    {
        date_default_timezone_set('America/Caracas');

        $cliente = self::getCliente($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($cliente)) {
            throw new Exception("Cliente no encontrado.");
        }

        self::validarIdentificacion($tipoIdentificacion, $numeroIdentificacion);
        self::validarTelefono($telefono);

        if ($cliente->numeroIdentificacion !== $numeroIdentificacion) {
            $clientePorIdentificacion = self::getClientePorIdentificacion($numeroIdentificacion);

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

        self::guardarHistorial($usuarioSesion, $cliente, $clienteModificado);
    }

    private static function guardarHistorial($usuarioSesion, $clienteOriginal, $clienteModificado)
    {
        date_default_timezone_set('America/Caracas');

        $conexionBaseDatos = (new ConexionBD())->getConexion();
        $cambios = [];

        if (empty($clienteModificado)) {
            $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

            $consultaHistorial->execute([
                $usuarioSesion,
                'Creado',
                'Cliente',
                $clienteOriginal->id,
                'Cliente creado',
                date('Y-m-d H:i:s')
            ]);

            return;
        }

        if ($clienteOriginal->nombre !== $clienteModificado->nombre) {
            $cambios[] = "Nombre: {$clienteOriginal->nombre} -> {$clienteModificado->nombre}";
        }

        if ($clienteOriginal->apellido !== $clienteModificado->apellido) {
            $cambios[] = "Nombre: {$clienteOriginal->apellido} -> {$clienteModificado->apellido}";
        }

        if ($clienteOriginal->tipoIdentificacion !== $clienteModificado->tipoIdentificacion) {
            $cambios[] = "Tipo de identificación: {$clienteOriginal->tipoIdentificacion} -> {$clienteModificado->tipoIdentificacion}";
        }

        if ($clienteOriginal->numeroIdentificacion !== $clienteModificado->numeroIdentificacion) {
            $cambios[] = "Número de identificacion: {$clienteOriginal->numeroIdentificacion} -> {$clienteModificado->numeroIdentificacion}";
        }

        if ($clienteOriginal->telefono !== $clienteModificado->telefono) {
            $cambios[] = "Teléfono: {$clienteOriginal->telefono} -> {$clienteModificado->telefono}";
        }

        if ($clienteOriginal->direccion !== $clienteModificado->direccion) {
            $cambios[] = "Dirección: {$clienteOriginal->direccion} -> {$clienteModificado->direccion}";
        }

        if ($clienteOriginal->estado !== $clienteModificado->estado) {
            $cambios[] = "Estado: {$clienteOriginal->estado} -> {$clienteModificado->estado}";
        }

        foreach ($cambios as $cambio) {
            $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

            $consultaHistorial->execute([
                $usuarioSesion,
                'Cambio',
                'Cliente',
                $clienteModificado->id,
                $cambio,
                date('Y-m-d H:i:s')
            ]);
        }
    }

    public static function getClientePorIdentificacion($numeroIdentificacion)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre, apellido, tipo_identificacion, numero_identificacion, telefono, direccion, fecha_creacion, estado 
            FROM clientes WHERE numero_identificacion = ?
        ");
        $consulta->execute([$numeroIdentificacion]);
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

    public static function cambiarEstado($id, $usuarioSesion)
    {
        $clienteOriginal = self::getCliente($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($clienteOriginal)) {
            throw new Exception("Cliente no encontrado.");
        }

        if ($clienteOriginal->estado === 'activo') {
            $nuevoEstado = 'inactivo';
        } else {
            $nuevoEstado = 'activo';
        }

        $clienteModificado = new Cliente(
            $clienteOriginal->id,
            $clienteOriginal->nombre,
            $clienteOriginal->apellido,
            $clienteOriginal->tipoIdentificacion,
            $clienteOriginal->numeroIdentificacion,
            $clienteOriginal->telefono,
            $clienteOriginal->direccion,
            $clienteOriginal->fechaCreacion,
            $nuevoEstado
        );

        $consultaEditarCliente = $conexionBaseDatos->prepare("
            UPDATE clientes 
            SET estado = ?
            WHERE id = ?
        ");

        $consultaEditarCliente->execute([
            $nuevoEstado,
            $id
        ]);

        self::guardarHistorial($usuarioSesion, $clienteOriginal, $clienteModificado);
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
            throw new Exception('Cliente no encontrado.');
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

    public static function getClientes($filtros, $orden, $ordenCampo)
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

        $consultaClientes .= " ORDER BY {$ordenCampo} {$orden}";

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

        $consultaEliminarCliente = $conexionBaseDatos->prepare("
            DELETE FROM clientes
            WHERE id = ?
        ");

        $consultaEliminarCliente->execute([$id]);
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

    public function nombre()
    {
        return $this->nombre;
    }

    public function apellido()
    {
        return $this->apellido;
    }

    public function tipoIdentificacion()
    {
        return $this->tipoIdentificacion;
    }

    public function numeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    public function telefono()
    {
        return $this->telefono;
    }

    public function direccion()
    {
        return $this->direccion;
    }

    public function fechaCreacion()
    {
        return $this->fechaCreacion;
    }

    public function estado()
    {
        return $this->estado;
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