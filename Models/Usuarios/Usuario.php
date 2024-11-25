<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';

class Usuario
{
    private $id;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $direccion;
    private $contrasenia;
    private $rol;
    private $estado;

    public function __construct($id, $nombre, $apellido, $cedula, $telefono, $direccion, $contrasenia, $rol, $estado)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->cedula = $cedula;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->contrasenia = $contrasenia;
        $this->rol = $rol;
        $this->estado = $estado;
    }

    public function nombre()
    {
        return $this->nombre;
    }

    public function apellido()
    {
        return $this->apellido;
    }

    public static function crear($nombre, $apellido, $cedula, $telefono, $direccion, $contrasenia, $rol, $estado)
    {
        $validarContraseniaVacia = true;

        self::validarCamposVacios($nombre, $apellido, $cedula, $telefono, $direccion, $validarContraseniaVacia, $contrasenia, $rol, $estado);
        self::validarCedula($cedula);
        self::validarTelefono($telefono);
        self::validarContrasenia($contrasenia);

        $usuarioConCedula = self::getUsuarioPorCedula($cedula);

        if (!empty($usuarioConCedula)) {
            throw new Exception("La cédula {$cedula} ya está en uso.");
        }

        $usuario = new Usuario(
            null,
            $nombre,
            $apellido,
            $cedula,
            $telefono,
            $direccion,
            password_hash($contrasenia, PASSWORD_DEFAULT),
            $rol,
            $estado
        );

        $consultaCrearUsuario = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO usuarios (nombre, apellido, cedula, telefono, direccion, contrasenia, rol, estado) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $consultaCrearUsuario->execute([
            $usuario->nombre,
            $usuario->apellido,
            $usuario->cedula,
            $usuario->telefono,
            $usuario->direccion,
            $usuario->contrasenia,
            $usuario->rol,
            $usuario->estado
        ]);
    }

    public static function getUsuarioPorCedula($cedula)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre, apellido, cedula, telefono, direccion, estado, rol 
            FROM usuarios WHERE cedula = ?
        ");
        $consulta->execute([$cedula]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($usuario)) {
            return null;
        }

        return new Usuario(
            $usuario['id'],
            $usuario['nombre'],
            $usuario['apellido'],
            $usuario['cedula'],
            $usuario['telefono'],
            $usuario['direccion'],
            null,
            $usuario['rol'],
            $usuario['estado']
        );
    }

    public static function editar($id, $nombre, $apellido, $cedula, $telefono, $direccion, $contrasenia, $rol, $estado)
    {
        $usuario = self::getUsuario($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($usuario)) {
            throw new Exception("Usuario no encontrado.");
        }

        $validarContraseniaVacia = !empty($contrasenia) && $usuario->contrasenia !== $contrasenia;

        self::validarCamposVacios($nombre, $apellido, $cedula, $telefono, $direccion, $validarContraseniaVacia, $contrasenia, $rol, $estado);
        self::validarCedula($cedula);
        self::validarTelefono($telefono);

        if (!empty($contrasenia)) {
            self::validarContrasenia($contrasenia);
        }

        if ($usuario->cedula !== $cedula) {
            $usuarioConCedula = self::getUsuarioPorCedula($cedula);

            if (!empty($usuarioConCedula)) {
                throw new Exception("La cédula {$cedula} ya está en uso.");
            }
        }

        $usuarioModificado = new Usuario(
            $id,
            $nombre,
            $apellido,
            $cedula,
            $telefono,
            $direccion,
            password_hash($contrasenia, PASSWORD_DEFAULT),
            $rol,
            $estado
        );

        if (!empty($contrasenia)) {
            $consultaEditarUsuario = $conexionBaseDatos->prepare("
                UPDATE usuarios 
                SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, direccion = ?, contrasenia = ?, rol = ?, estado = ?
                WHERE id = ?
            ");

            $consultaEditarUsuario->execute([
                $usuarioModificado->nombre,
                $usuarioModificado->apellido,
                $usuarioModificado->cedula,
                $usuarioModificado->telefono,
                $usuarioModificado->direccion,
                $usuarioModificado->contrasenia,
                $usuarioModificado->rol,
                $usuarioModificado->estado,
                $usuarioModificado->id
            ]);
        } else {
            $consultaEditarUsuario = $conexionBaseDatos->prepare("
                UPDATE usuarios 
                SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, direccion = ?, rol = ?, estado = ?
                WHERE id = ?
            ");

            $consultaEditarUsuario->execute([
                $usuarioModificado->nombre,
                $usuarioModificado->apellido,
                $usuarioModificado->cedula,
                $usuarioModificado->telefono,
                $usuarioModificado->direccion,
                $usuarioModificado->rol,
                $usuarioModificado->estado,
                $usuarioModificado->id
            ]);
        }
    }

    public static function cambiarEstado($id)
    {
        $usuario = self::getUsuario($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($usuario)) {
            throw new Exception("Usuario no encontrado.");
        }

        if ($usuario->estado === 'activo') {
            $nuevoEstado = 'inactivo';
        } else {
            $nuevoEstado = 'activo';
        }

        $consultaEditarUsuario = $conexionBaseDatos->prepare("
            UPDATE usuarios 
            SET estado = ?
            WHERE id = ?
        ");

        $consultaEditarUsuario->execute([
            $nuevoEstado,
            $id
        ]);
    }

    public static function getUsuario($id)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre, apellido, cedula, telefono, direccion, estado, rol, estado 
            FROM usuarios WHERE id = ?
        ");
        $consulta->execute([$id]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($usuario)) {
            throw new Exception("Usuario no encontrado.");
        }

        return new Usuario(
            $usuario['id'],
            $usuario['nombre'],
            $usuario['apellido'],
            $usuario['cedula'],
            $usuario['telefono'],
            $usuario['direccion'],
            null,
            $usuario['rol'],
            $usuario['estado']
        );
    }

    public static function getUsuarios($filtros, $orden)
    {
        $consultaUsuarios = "SELECT id, nombre, apellido, cedula, telefono, direccion, rol, estado FROM usuarios";

        if (!empty($filtros)) {
            $consultaUsuarios .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['nombre', 'cedula', 'apellido', 'telefono', 'direccion'];
                $operador = in_array($key, $campos) ? 'LIKE' : '=';

                $consultaUsuarios .= "{$key} {$operador} '{$filtro}'";

                $iteracion++;

                if ($iteracion < count($filtros)) {
                    $consultaUsuarios .= " AND ";
                }
            }
        }

        $consultaUsuarios .= " ORDER BY id {$orden}";

        $consulta = (new ConexionBD())->getConexion()->prepare($consultaUsuarios);
        $consulta->execute();

        $usuariosBaseDatos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        foreach ($usuariosBaseDatos as $usuario) {
            $usuarios[] = new Usuario(
                $usuario['id'],
                $usuario['nombre'],
                $usuario['apellido'],
                $usuario['cedula'],
                $usuario['telefono'],
                $usuario['direccion'],
                null,
                $usuario['rol'],
                $usuario['estado']
            );
        }

        return $usuarios;
    }

    public static function eliminar($id)
    {
        $usuario = self::getUsuario($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($usuario)) {
            throw new Exception("Usuario no encontrado.");
        }

        $consultaEliminarUsuario = $conexionBaseDatos->prepare("
            DELETE FROM usuarios
            WHERE id = ?
        ");

        $consultaEliminarUsuario->execute([$id]);
    }

    public static function validarCamposVacios($nombre, $apellido, $cedula, $telefono, $direccion, $validarContraseniaVacia, $contrasenia, $rol, $estado)
    {
        if (empty($nombre)) {
            throw new Exception("El nombre no puede estar vacío.");
        }

        if (empty($apellido)) {
            throw new Exception("El appelido no puede estar vacío.");
        }

        if (empty($cedula)) {
            throw new Exception("La cédula no puede estar vacía.");
        }

        if (empty($telefono)) {
            throw new Exception("El número de teléfono no puede estar vacío.");
        }

        if (empty($direccion)) {
            throw new Exception("La dirección no puede estar vacía.");
        }

        if ($validarContraseniaVacia === true && empty($contrasenia)) {
            throw new Exception("La contraseña no puede estar vacía.");
        }

        if (empty($rol)) {
            throw new Exception("El rol no puede estar vacío.");
        }

        if (empty($estado)) {
            throw new Exception("El estado no puede estar vacío.");
        }
    }

    public static function validarCedula($cedula)
    {
        $cedula = trim($cedula);

        if (!preg_match('/^\d{6,8}$/', $cedula)) {
            throw new Exception("La cédula debe tener de 6 a 8 dígitos numéricos.");
        }
    }

    public static function validarTelefono($telefono)
    {
        $telefono = trim($telefono);

        if (!preg_match('/^\d{11}$/', $telefono)) {
            throw new Exception("El número de teléfono debe tener 11 dígitos numéricos.");
        }
    }

    public static function validarContrasenia($contrasenia)
    {
        $contrasenia = trim($contrasenia);

        if (strlen($contrasenia) < 8) {
            throw new Exception("La contraseña debe tener 8 o más caracteres.");
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'cedula' => $this->cedula,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'rol' => $this->rol,
            'estado' => $this->estado
        ];
    }
}