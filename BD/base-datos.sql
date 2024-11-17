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
VALUES ('admin', 'apellido', '12345670', '$2y$10$qGY76PQnevfXJvhxfRM6cOoj.oKPH9uGvI4rmLh7e6kh6TBJQcAB2', '04161234567', 'calle juncal', 'admin', 'activo');

select * from usuarios;

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