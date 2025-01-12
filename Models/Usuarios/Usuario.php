<?php

require_once __DIR__ . '/../../BD/ConexionBD.php';

class Usuario
{
    public const LLAVE = 'clave-secreta-vanilla-inventario';
    public const METODO_ENCRIPTADO = 'aes-256-cbc';

    private $id;
    private $nombreUsuario;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $direccion;
    private $contrasenia;
    private $iv;
    private $rol;
    private $estado;

    public function __construct($id, $nombreUsuario, $nombre, $apellido, $cedula, $telefono, $direccion, $contrasenia, $iv, $rol, $estado)
    {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->cedula = $cedula;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->contrasenia = $contrasenia;
        $this->iv = $iv;
        $this->rol = $rol;
        $this->estado = $estado;
    }

    public function id()
    {
        return $this->id;
    }

    public function nombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function cedula()
    {
        return $this->cedula;
    }

    public function telefono()
    {
        return $this->telefono;
    }

    public function direccion()
    {
        return $this->direccion;
    }

    public function rol()
    {
        return $this->rol;
    }

    public function estado()
    {
        return $this->estado;
    }

    public function nombre()
    {
        return $this->nombre;
    }

    public function apellido()
    {
        return $this->apellido;
    }

    public function contrasenia()
    {
        return $this->contrasenia;
    }

    public static function ivAleatorio()
    {
        return openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::METODO_ENCRIPTADO));
    }

    public function contraseniaDesencriptada()
    {
        return openssl_decrypt($this->contrasenia, 'aes-256-cbc', self::LLAVE, 0, hex2bin($this->iv));
    }

    public static function contraseniaEncriptada($contrasenia, $iv)
    {
        return openssl_encrypt($contrasenia, self::METODO_ENCRIPTADO, self::LLAVE, 0, $iv);
    }

    public static function crear($nombreUsuario, $nombre, $apellido, $cedula, $telefono, $direccion, $contrasenia, $rol, $estado, $usuarioSesion)
    {
        $validarContraseniaVacia = true;

        self::validarCamposVacios($nombreUsuario, $nombre, $apellido, $cedula, $telefono, $direccion, $validarContraseniaVacia, $contrasenia, $rol, $estado);
        self::validarCedula($cedula);
        self::validarTelefono($telefono);
        self::validarContrasenia($contrasenia);
        self::validarNombreUsuario($nombreUsuario);

        $usuarioConCedula = self::getUsuarioPorCedula($cedula);

        if (!empty($usuarioConCedula)) {
            throw new Exception("La cédula {$cedula} ya está en uso.");
        }

        $usuarioConNombreUsuario = self::getUsuarioPorNombreUsuario($nombreUsuario);

        if (!empty($usuarioConNombreUsuario)) {
            throw new Exception("El nombre de usuario {$nombreUsuario} ya está en uso.");
        }

        $ivAleatorio = self::ivAleatorio();
        $contraseniaEncriptada = self::contraseniaEncriptada($contrasenia, $ivAleatorio);

        $usuario = new Usuario(
            null,
            $nombreUsuario,
            $nombre,
            $apellido,
            $cedula,
            $telefono,
            $direccion,
            $contraseniaEncriptada,
            $ivAleatorio,
            $rol,
            $estado
        );

        $consultaCrearUsuario = (new ConexionBD())->getConexion()->prepare("
            INSERT INTO usuarios (nombre_usuario, nombre, apellido, cedula, telefono, direccion, contrasenia, iv, rol, estado) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $consultaCrearUsuario->execute([
            $usuario->nombreUsuario,
            $usuario->nombre,
            $usuario->apellido,
            $usuario->cedula,
            $usuario->telefono,
            $usuario->direccion,
            $usuario->contrasenia,
            bin2hex($usuario->iv),
            $usuario->rol,
            $usuario->estado
        ]);

        $consultaUsuarioId = (new ConexionBD())->getConexion()->prepare("SELECT id FROM usuarios ORDER BY id DESC LIMIT 1");
        $consultaUsuarioId->execute();
        $usuarioId = $consultaUsuarioId->fetch(PDO::FETCH_ASSOC);

        $nuevoUsuario = new Usuario(
            $usuarioId['id'],
            $usuario->nombreUsuario,
            $usuario->nombre,
            $usuario->apellido,
            $usuario->cedula,
            $usuario->telefono,
            $usuario->direccion,
            $usuario->contrasenia,
            $usuario->iv,
            $usuario->rol,
            $usuario->estado
        );

        self::guardarHistorial($usuarioSesion, $nuevoUsuario, null, null);
    }

    public static function editar($id, $nombreUsuario, $nombre, $apellido, $cedula, $telefono, $direccion, $contrasenia, $rol, $estado, $usuarioSesion)
    {
        $usuarioOriginal = self::getUsuario($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($usuarioOriginal)) {
            throw new Exception("Usuario no encontrado.");
        }

        self::validarCamposVacios($nombreUsuario, $nombre, $apellido, $cedula, $telefono, $direccion, $contrasenia, $rol, $estado);
        self::validarCedula($cedula);
        self::validarTelefono($telefono);
        self::validarNombreUsuario($nombreUsuario);
        self::validarContrasenia($contrasenia);

        if ($usuarioOriginal->cedula !== $cedula) {
            $usuarioConCedula = self::getUsuarioPorCedula($cedula);

            if (!empty($usuarioConCedula)) {
                throw new Exception("La cédula {$cedula} ya está en uso.");
            }
        }

        if ($usuarioOriginal->nombreUsuario !== $nombreUsuario) {
            $usuarioPorNombreUsuario = self::getUsuarioPorNombreUsuario($nombreUsuario);

            if (!empty($usuarioPorNombreUsuario)) {
                throw new Exception("El nombre de usuario {$nombreUsuario} ya está en uso.");
            }
        }

        $iv = hex2bin($usuarioOriginal->iv);
        $contraseniaEncriptada = self::contraseniaEncriptada($contrasenia, $iv);

        $usuarioModificado = new Usuario(
            $id,
            $nombreUsuario,
            $nombre,
            $apellido,
            $cedula,
            $telefono,
            $direccion,
            $contraseniaEncriptada,
            $iv,
            $rol,
            $estado
        );

        if ($contrasenia !== $usuarioOriginal->contraseniaDesencriptada()) {
            $consultaEditarUsuario = $conexionBaseDatos->prepare("
                UPDATE usuarios 
                SET nombre_usuario = ?, nombre = ?, apellido = ?, cedula = ?, telefono = ?, direccion = ?, contrasenia = ?, rol = ?, estado = ?
                WHERE id = ?
            ");

            $consultaEditarUsuario->execute([
                $usuarioModificado->nombreUsuario,
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
                SET nombre_usuario = ?, nombre = ?, apellido = ?, cedula = ?, telefono = ?, direccion = ?, rol = ?, estado = ?
                WHERE id = ?
            ");

            $consultaEditarUsuario->execute([
                $usuarioModificado->nombreUsuario,
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

        self::guardarHistorial($usuarioSesion, $usuarioOriginal, $usuarioModificado, $contrasenia);
    }

    private static function guardarHistorial($usuarioSesion, $usuarioOriginal, $usuarioModificado, $contraseniaModificada)
    {
        $conexionBaseDatos = (new ConexionBD())->getConexion();
        $cambios = [];

        if (empty($usuarioModificado)) {
            $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

            $consultaHistorial->execute([
                $usuarioSesion,
                'Creado',
                'Usuario',
                $usuarioOriginal->id,
                'Usuario creado',
                date('Y-m-d H:i:s')
            ]);

            return;
        }

        if ($usuarioOriginal->nombreUsuario !== $usuarioModificado->nombreUsuario) {
            $cambios[] = "Nombre de usuario: {$usuarioOriginal->nombreUsuario} -> {$usuarioModificado->nombreUsuario}";
        }

        if ($usuarioOriginal->nombre !== $usuarioModificado->nombre) {
            $cambios[] = "Nombre: {$usuarioOriginal->nombre} -> {$usuarioModificado->nombre}";
        }

        if ($usuarioOriginal->apellido !== $usuarioModificado->apellido) {
            $cambios[] = "Apellido: {$usuarioOriginal->apellido} -> {$usuarioModificado->apellido}";
        }

        if ($usuarioOriginal->cedula !== $usuarioModificado->cedula) {
            $cambios[] = "Cédula: {$usuarioOriginal->cedula} -> {$usuarioModificado->cedula}";
        }

        if ($usuarioOriginal->telefono !== $usuarioModificado->telefono) {
            $cambios[] = "Teléfono: {$usuarioOriginal->telefono} -> {$usuarioModificado->telefono}";
        }

        if ($usuarioOriginal->direccion !== $usuarioModificado->direccion) {
            $cambios[] = "Dirección: {$usuarioOriginal->direccion} -> {$usuarioModificado->direccion}";
        }

        if ($usuarioOriginal->rol !== $usuarioModificado->rol) {
            $cambios[] = "Rol: {$usuarioOriginal->rol} -> {$usuarioModificado->rol}";
        }

        if ($usuarioOriginal->estado !== $usuarioModificado->estado) {
            $cambios[] = "Estado: {$usuarioOriginal->estado} -> {$usuarioModificado->estado}";
        }

        if (!empty($contraseniaModificada)) {
            $cambios[] = "Contraseña cambiada";
        }

        foreach ($cambios as $cambio) {
            $consultaHistorial = $conexionBaseDatos->prepare("
                INSERT INTO usuarios_historial (usuario_id, tipo_accion, tipo_entidad, entidad_id, cambio, fecha) VALUES 
                (?, ?, ?, ?, ?, ?)
            ");

            $consultaHistorial->execute([
                $usuarioSesion,
                'Cambio',
                'Usuario',
                $usuarioModificado->id,
                $cambio,
                date('Y-m-d H:i:s')
            ]);
        }
    }

    public static function getUsuarioPorCedula($cedula)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre_usuario, contrasenia, iv, nombre, apellido, cedula, telefono, direccion, estado, rol 
            FROM usuarios WHERE cedula = ?
        ");
        $consulta->execute([$cedula]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($usuario)) {
            return null;
        }

        return new Usuario(
            $usuario['id'],
            $usuario['nombre_usuario'],
            $usuario['nombre'],
            $usuario['apellido'],
            $usuario['cedula'],
            $usuario['telefono'],
            $usuario['direccion'],
            $usuario['contrasenia'],
            $usuario['iv'],
            $usuario['rol'],
            $usuario['estado']
        );
    }

    public static function getUsuarioPorNombreUsuario($nombreUsuario)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre_usuario, iv, contrasenia, nombre, apellido, cedula, telefono, direccion, estado, rol 
            FROM usuarios WHERE BINARY nombre_usuario = ?
        ");
        $consulta->execute([$nombreUsuario]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($usuario)) {
            return null;
        }

        return new Usuario(
            $usuario['id'],
            $usuario['nombre_usuario'],
            $usuario['nombre'],
            $usuario['apellido'],
            $usuario['cedula'],
            $usuario['telefono'],
            $usuario['direccion'],
            $usuario['contrasenia'],
            $usuario['iv'],
            $usuario['rol'],
            $usuario['estado']
        );
    }

    public static function cambiarEstado($id, $usuarioSesion)
    {
        $usuarioOriginal = self::getUsuario($id);
        $conexionBaseDatos = (new ConexionBD())->getConexion();

        if (empty($usuarioOriginal)) {
            throw new Exception("Usuario no encontrado.");
        }

        if ($usuarioOriginal->estado === 'activo') {
            $nuevoEstado = 'inactivo';
        } else {
            $nuevoEstado = 'activo';
        }

        $usuarioModificado = new Usuario(
            $usuarioOriginal->id,
            $usuarioOriginal->nombreUsuario,
            $usuarioOriginal->nombre,
            $usuarioOriginal->apellido,
            $usuarioOriginal->cedula,
            $usuarioOriginal->telefono,
            $usuarioOriginal->direccion,
            $usuarioOriginal->contrasenia,
            $usuarioOriginal->iv,
            $usuarioOriginal->rol,
            $nuevoEstado
        );

        $consultaEditarUsuario = $conexionBaseDatos->prepare("
            UPDATE usuarios 
            SET estado = ?
            WHERE id = ?
        ");

        $consultaEditarUsuario->execute([
            $nuevoEstado,
            $id
        ]);

        self::guardarHistorial($usuarioSesion, $usuarioOriginal, $usuarioModificado, null);
    }

    public static function getUsuario($id)
    {
        $consulta = (new ConexionBD())->getConexion()->prepare("
            SELECT id, nombre_usuario, contrasenia, iv, nombre, apellido, cedula, telefono, direccion, estado, rol, estado 
            FROM usuarios WHERE id = ?
        ");
        $consulta->execute([$id]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if (empty($usuario)) {
            throw new Exception("Usuario no encontrado.");
        }

        return new Usuario(
            $usuario['id'],
            $usuario['nombre_usuario'],
            $usuario['nombre'],
            $usuario['apellido'],
            $usuario['cedula'],
            $usuario['telefono'],
            $usuario['direccion'],
            $usuario['contrasenia'],
            $usuario['iv'],
            $usuario['rol'],
            $usuario['estado']
        );
    }

    public static function getUsuarios($filtros, $orden)
    {
        $consultaUsuarios = "SELECT id, nombre_usuario, nombre, apellido, cedula, telefono, direccion, rol, estado FROM usuarios";

        if (!empty($filtros)) {
            $consultaUsuarios .= " WHERE ";
            $iteracion = 0;

            foreach ($filtros as $key => $filtro) {
                $campos = ['nombre_usuario','nombre', 'cedula', 'apellido', 'telefono', 'direccion'];
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
                $usuario['nombre_usuario'],
                $usuario['nombre'],
                $usuario['apellido'],
                $usuario['cedula'],
                $usuario['telefono'],
                $usuario['direccion'],
                null,
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

    public static function validarCamposVacios($nombreUsuario, $nombre, $apellido, $cedula, $telefono, $direccion, $contrasenia, $rol, $estado)
    {
        if (empty($nombreUsuario)) {
            throw new Exception("El nombre de usuario no puede estar vacío.");
        }

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

        if (empty($contrasenia)) {
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

    public static function validarNombreUsuario($nombreUsuario)
    {
        $nombreUsuario = trim($nombreUsuario);

        // Verificar al menos una letra mayúscula
        if (preg_match('/\s/' , $nombreUsuario)) {
            throw new Exception("El nombre de usuario no puede contener espacios.");
        }
    }

    public static function validarContrasenia($contrasenia)
    {
        $contrasenia = trim($contrasenia);

        // Verificar longitud
        if (strlen($contrasenia) < 8) {
            throw new Exception("La contraseña debe tener 8 o más caracteres.");
        }

        // Verificar al menos una letra mayúscula
        if (!preg_match('/[A-Z]/', $contrasenia)) {
            throw new Exception("La contraseña debe contener al menos una letra mayúscula.");
        }

        // Verificar al menos una letra minúscula
        if (!preg_match('/[a-z]/', $contrasenia)) {
            throw new Exception("La contraseña debe contener al menos una letra minúscula.");
        }

        // Verificar al menos un número
        if (!preg_match('/\d/', $contrasenia)) {
            throw new Exception("La contraseña debe contener al menos un número.");
        }

        // Verificar al menos un carácter especial
        if (!preg_match('/[!@#$%^&*]/', $contrasenia)) {
            throw new Exception("La contraseña debe contener al menos un carácter especial (!@#$%^&*).");
        }
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombreUsuario' => $this->nombreUsuario,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'cedula' => $this->cedula,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'rol' => $this->rol,
            'estado' => $this->estado,
            'contrasenia' => $this->contraseniaDesencriptada()
        ];
    }
}