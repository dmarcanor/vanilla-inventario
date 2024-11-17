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
VALUES ('admin', 'apellido', '1234567', sha('admin'), '04161234567', 'calle juncal', 'admin', 'activo');

select * from usuarios;

INSERT INTO usuarios (nombre, apellido, cedula, contrasenia, telefono, direccion, rol, estado)
VALUES ('usuario', 'apellido', '76543213', sha('contra'), '04161234567', 'calle A', 'operador', 'activo');

SELECT *
FROM usuarios
ORDER BY id ASC
LIMIT 10 OFFSET 10;

CREATE TABLE IF NOT EXISTS clientes
(
    id                    INT AUTO_INCREMENT PRIMARY KEY,
    nombre                VARCHAR(100)                NOT NULL,
    apellido              VARCHAR(100)                NOT NULL,
    tipo_identificacion   VARCHAR(100)                NOT NULL,
    numero_identificacion VARCHAR(100)                NOT NULL,
    telefono              VARCHAR(100)                NOT NULL,
    direccion             VARCHAR(255)                NOT NULL,
    fecha                 DATE                        NOT NULL,
    hora                  TIME                        NOT NULL,
    estado                ENUM ('activo', 'inactivo') NOT NULL
);