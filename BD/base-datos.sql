CREATE DATABASE IF NOT EXISTS inventario;

USE inventario;

DROP TABLE IF EXISTS usuarios;
CREATE TABLE IF NOT EXISTS usuarios
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(20)                NOT NULL,
    apellido    VARCHAR(20)                NOT NULL,
    cedula      VARCHAR(10)                 NOT NULL UNIQUE,
    contrasenia VARCHAR(255)                NOT NULL,
    iv          VARCHAR(64)                NOT NULL,
    telefono    VARCHAR(11)                NOT NULL,
    direccion   VARCHAR(30)                NOT NULL,
    rol         VARCHAR(10)                 NOT NULL,
    estado      ENUM ('activo', 'desincorporado') NOT NULL
);

INSERT INTO usuarios (nombre, apellido, cedula, contrasenia, iv, telefono, direccion, rol, estado)
VALUES ('admin', 'apellido', '12345670', 'ygWKiGBhItwTBk0zutIQwQ==', '6de83e1d37e577d83e7be9fb96f90d7b', '04161234567',
        'calle juncal', 'admin', 'activo');

DROP TABLE IF EXISTS clientes;
CREATE TABLE IF NOT EXISTS clientes
(
    id                    INT AUTO_INCREMENT PRIMARY KEY,
    nombre                VARCHAR(20)                NOT NULL,
    apellido              VARCHAR(20)                NOT NULL,
    tipo_identificacion   VARCHAR(10)                NOT NULL,
    numero_identificacion VARCHAR(11)                NOT NULL,
    telefono              VARCHAR(11)                NOT NULL,
    direccion             VARCHAR(20)                NOT NULL,
    fecha_creacion        DATETIME                    NOT NULL,
    estado                ENUM ('activo', 'desincorporado') NOT NULL
);

DROP TABLE IF EXISTS categorias;
CREATE TABLE IF NOT EXISTS categorias
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(20)                NOT NULL,
    descripcion    VARCHAR(30)                NOT NULL,
    fecha_creacion DATETIME                    NOT NULL,
    estado         ENUM ('activo', 'desincorporado') NOT NULL
);

INSERT INTO categorias (nombre, descripcion, fecha_creacion, estado)
VALUES ('categoria de purbea', 'probando', NOW(), 'activo');

DROP TABLE IF EXISTS materiales;
CREATE TABLE IF NOT EXISTS materiales
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(30)                NOT NULL,
    descripcion    VARCHAR(30)                NOT NULL,
    marca          INT(15),
    usuario_id     INT                         NOT NULL,
    categoria_id   INT                         NOT NULL,
    unidad         VARCHAR(10)                NOT NULL,
    peso           DECIMAL(11, 2)              NOT NULL,
    precio         DECIMAL(11, 2)              NOT NULL,
    stock          DECIMAL(11, 2)              NOT NULL,
    fecha_creacion DATETIME                    NOT NULL,
    estado         ENUM ('activo', 'desincorporado') NOT NULL
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


DROP TABLE IF EXISTS entradas;
CREATE TABLE IF NOT EXISTS entradas
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    descripcion    VARCHAR(30)                 NOT NULL,
    usuario_id     INT                          NOT NULL,
    fecha_creacion DATETIME                     NOT NULL
);

ALTER TABLE entradas
    ADD CONSTRAINT fk_entradas_usuarios
        FOREIGN KEY (usuario_id)
            REFERENCES usuarios (id) ON DELETE CASCADE;

# ALTER TABLE entradas
#     DROP CONSTRAINT fk_entradas_usuarios;


DROP TABLE IF EXISTS entrada_lineas;
CREATE TABLE IF NOT EXISTS entrada_lineas
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    entrada_id  INT            NOT NULL,
    material_id INT            NOT NULL,
    cantidad    DECIMAL(11, 2) NOT NULL,
    precio      DECIMAL(11, 2) NOT NULL
);

ALTER TABLE entrada_lineas
    ADD CONSTRAINT fk_entrada_lineas_entrada
        FOREIGN KEY (entrada_id)
            REFERENCES entradas (id) ON DELETE CASCADE;

ALTER TABLE entrada_lineas
    ADD CONSTRAINT fk_entrada_lineas_material
        FOREIGN KEY (material_id)
            REFERENCES materiales (id) ON DELETE CASCADE;

DROP TABLE IF EXISTS salidas;
CREATE TABLE IF NOT EXISTS salidas
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    descripcion    VARCHAR(30)                 NOT NULL,
    cliente_id     INT                          NOT NULL,
    usuario_id     INT                          NOT NULL,
    fecha_creacion DATETIME                     NOT NULL
);

ALTER TABLE salidas
    ADD CONSTRAINT fk_salidas_usuarios
        FOREIGN KEY (usuario_id)
            REFERENCES usuarios (id) ON DELETE CASCADE;

ALTER TABLE salidas
    ADD CONSTRAINT fk_salidas_clientes
        FOREIGN KEY (cliente_id)
            REFERENCES clientes (id) ON DELETE CASCADE;


DROP TABLE IF EXISTS salida_lineas;
CREATE TABLE IF NOT EXISTS salida_lineas
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    salida_id   INT            NOT NULL,
    material_id INT            NOT NULL,
    cantidad    DECIMAL(11, 2) NOT NULL,
    precio      DECIMAL(11, 2) NOT NULL
);

ALTER TABLE salida_lineas
    ADD CONSTRAINT fk_salida_lineas_salida
        FOREIGN KEY (salida_id)
            REFERENCES salidas (id) ON DELETE CASCADE;

ALTER TABLE salida_lineas
    ADD CONSTRAINT fk_salida_lineas_material
        FOREIGN KEY (material_id)
            REFERENCES materiales (id) ON DELETE CASCADE;

ALTER TABLE materiales
    DROP CONSTRAINT fk_materiales_usuarios,
    DROP COLUMN usuario_id;

ALTER TABLE entrada_lineas
    MODIFY COLUMN cantidad INT NOT NULL;

ALTER TABLE salida_lineas
    MODIFY COLUMN cantidad INT NOT NULL;

ALTER TABLE entradas
    CHANGE COLUMN descripcion observacion VARCHAR(30) NOT NULL;

ALTER TABLE salidas
    CHANGE COLUMN descripcion observacion VARCHAR(30) NOT NULL;

ALTER TABLE materiales
    CHANGE COLUMN peso presentacion VARCHAR(100) NOT NULL;

delete
from materiales;
delete
from entradas;
truncate table entrada_lineas;

ALTER TABLE materiales
    ADD COLUMN stock_minimo DECIMAL(11, 2) NOT NULL,
    ADD COLUMN codigo       VARCHAR(30)   NOT NULL;

ALTER TABLE entradas
    ADD COLUMN numero_entrada INT NOT NULL;

ALTER TABLE usuarios
    ADD COLUMN nombre_usuario VARCHAR(30) NOT NULL;

UPDATE usuarios
SET nombre_usuario = 'admin'
WHERE id = 1;

CREATE TABLE IF NOT EXISTS usuarios_historial
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id   INT          NOT NULL,
    tipo_accion  VARCHAR(10)  NOT NULL,
    tipo_entidad VARCHAR(10) NOT NULL,
    entidad_id   INT          NOT NULL,
    cambio       TEXT         NOT NULL,
    fecha        DATETIME     NOT NULL
);

ALTER TABLE usuarios
    ADD COLUMN iv VARCHAR(255) NOT NULL AFTER contrasenia;

DELETE
FROM salida_lineas;

DELETE
FROM salidas;

DELETE
FROM usuarios;

ALTER TABLE usuarios
    AUTO_INCREMENT = 1;

INSERT INTO usuarios (nombre_usuario, nombre, apellido, cedula, contrasenia, iv, telefono, direccion, rol, estado)
VALUES ('admin2025', 'admin', 'apellido', '12345670', 'ygWKiGBhItwTBk0zutIQwQ==', '6de83e1d37e577d83e7be9fb96f90d7b',
        '04161234567',
        'calle juncal', 'admin', 'activo');

DELETE
FROM usuarios_historial
WHERE NOT EXISTS(SELECT * FROM usuarios WHERE usuarios.id = usuarios_historial.usuario_id);

DELETE
FROM usuarios_historial
WHERE NOT EXISTS(SELECT * FROM usuarios WHERE usuarios.id = usuarios_historial.entidad_id);

ALTER TABLE usuarios
    DROP COLUMN eliminado;

ALTER TABLE clientes
    DROP COLUMN eliminado;

ALTER TABLE categorias
    DROP COLUMN eliminado;

ALTER TABLE materiales
    DROP COLUMN eliminado;

ALTER TABLE entradas
    DROP COLUMN eliminado;

ALTER TABLE salidas
    DROP COLUMN eliminado;

DELETE FROM usuarios_historial;
DELETE FROM clientes;
DELETE FROM categorias;
DELETE FROM entradas;
DELETE FROM entrada_lineas;
DELETE FROM salidas;
DELETE FROM salida_lineas;
DELETE FROM materiales;

ALTER TABLE usuarios
    MODIFY COLUMN nombre VARCHAR(20) NOT NULL,
    MODIFY COLUMN apellido VARCHAR(20) NOT NULL,
    MODIFY COLUMN cedula VARCHAR(10) NOT NULL UNIQUE,
    MODIFY COLUMN contrasenia VARCHAR(255) NOT NULL,
    MODIFY COLUMN iv VARCHAR(64) NOT NULL,
    MODIFY COLUMN telefono VARCHAR(11) NOT NULL,
    MODIFY COLUMN direccion VARCHAR(30) NOT NULL,
    MODIFY COLUMN rol VARCHAR(10) NOT NULL,
    MODIFY COLUMN nombre_usuario VARCHAR(30) NOT NULL;

ALTER TABLE clientes
    MODIFY COLUMN nombre VARCHAR(20) NOT NULL,
    MODIFY COLUMN apellido VARCHAR(20),
    MODIFY COLUMN tipo_identificacion VARCHAR(9) NOT NULL,
    MODIFY COLUMN numero_identificacion VARCHAR(11) NOT NULL,
    MODIFY COLUMN telefono VARCHAR(11) NOT NULL,
    MODIFY COLUMN direccion VARCHAR(20) NOT NULL;

ALTER TABLE categorias
    MODIFY COLUMN nombre VARCHAR(20) NOT NULL,
    MODIFY COLUMN descripcion VARCHAR(30) NOT NULL;

ALTER TABLE materiales
    MODIFY COLUMN nombre VARCHAR(20) NOT NULL,
    MODIFY COLUMN descripcion VARCHAR(30) NOT NULL,
    MODIFY COLUMN marca INT,
    MODIFY COLUMN unidad VARCHAR(15) NOT NULL,
    MODIFY COLUMN presentacion VARCHAR(30) NOT NULL,
    MODIFY COLUMN codigo VARCHAR(20) NOT NULL;

ALTER TABLE entradas
    MODIFY COLUMN observacion VARCHAR(30) NOT NULL;

ALTER TABLE salidas
    MODIFY COLUMN observacion VARCHAR(30) NOT NULL;

ALTER TABLE usuarios_historial
    MODIFY COLUMN tipo_accion VARCHAR(10) NOT NULL,
    MODIFY COLUMN tipo_entidad VARCHAR(10) NOT NULL;

DROP TABLE IF EXISTS salida_lineas;
DROP TABLE IF EXISTS salidas;
DROP TABLE IF EXISTS entrada_lineas;
DROP TABLE IF EXISTS entradas;
DROP TABLE IF EXISTS materiales;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS usuarios_historial;
DROP TABLE IF EXISTS clientes;

CREATE TABLE marcas
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(20) UNIQUE NOT NULL
);

DELETE FROM materiales;

ALTER TABLE materiales
    MODIFY COLUMN marca INT NULL,
    ADD CONSTRAINT fk_materiales_marcas
        FOREIGN KEY (marca)
            REFERENCES marcas (id) ON DELETE CASCADE;


SELECT * FROM usuarios order by cedula desc;