CREATE DATABASE IF NOT EXISTS inventario;

USE inventario;

DROP TABLE IF EXISTS usuarios;
CREATE TABLE IF NOT EXISTS usuarios
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)                NOT NULL,
    apellido    VARCHAR(100)                NOT NULL,
    cedula      VARCHAR(10)                 NOT NULL UNIQUE,
    contrasenia VARCHAR(255)                NOT NULL,
    telefono    VARCHAR(100)                NOT NULL,
    direccion   VARCHAR(255)                NOT NULL,
    rol         VARCHAR(10)                 NOT NULL,
    estado      ENUM ('activo', 'inactivo') NOT NULL
);

INSERT INTO usuarios (nombre, apellido, cedula, contrasenia, telefono, direccion, rol, estado)
VALUES ('admin', 'apellido', '12345670', '$2y$10$qGY76PQnevfXJvhxfRM6cOoj.oKPH9uGvI4rmLh7e6kh6TBJQcAB2', '04161234567',
        'calle juncal', 'admin', 'activo');

DROP TABLE IF EXISTS clientes;
CREATE TABLE IF NOT EXISTS clientes
(
    id                    INT AUTO_INCREMENT PRIMARY KEY,
    nombre                VARCHAR(100)                NOT NULL,
    apellido              VARCHAR(100)                NOT NULL,
    tipo_identificacion   VARCHAR(100)                NOT NULL,
    numero_identificacion VARCHAR(100)                NOT NULL,
    telefono              VARCHAR(100)                NOT NULL,
    direccion             VARCHAR(255)                NOT NULL,
    fecha_creacion        DATETIME                    NOT NULL,
    estado                ENUM ('activo', 'inactivo') NOT NULL
);

DROP TABLE IF EXISTS categorias;
CREATE TABLE IF NOT EXISTS categorias
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(100)                NOT NULL,
    fecha_creacion DATETIME                    NOT NULL,
    estado         ENUM ('activo', 'inactivo') NOT NULL
);

INSERT INTO categorias (nombre, fecha_creacion, estado)
VALUES ('categoria de purbea', NOW(), 'activo');

DROP TABLE IF EXISTS materiales;
CREATE TABLE IF NOT EXISTS materiales
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(100)                NOT NULL,
    descripcion    VARCHAR(255)                NOT NULL,
    marca          VARCHAR(255)                NOT NULL,
    usuario_id     INT                         NOT NULL,
    categoria_id   INT                         NOT NULL,
    unidad         VARCHAR(100)                NOT NULL,
    peso           DECIMAL(11, 2)              NOT NULL,
    precio         DECIMAL(11, 2)              NOT NULL,
    stock          DECIMAL(11, 2)              NOT NULL,
    fecha_creacion DATETIME                    NOT NULL,
    estado         ENUM ('activo', 'inactivo') NOT NULL
);

ALTER TABLE materiales
    ADD CONSTRAINT fk_materiales_categorias
        FOREIGN KEY (categoria_id)
            REFERENCES categorias (id) ON DELETE CASCADE;

# ALTER TABLE materiales
#     DROP CONSTRAINT fk_materiales_categorias;


ALTER TABLE materiales
    ADD CONSTRAINT fk_materiales_usuarios
        FOREIGN KEY (usuario_id)
            REFERENCES usuarios (id) ON DELETE CASCADE;


# ALTER TABLE materiales
#     DROP CONSTRAINT fk_materiales_usuarios;