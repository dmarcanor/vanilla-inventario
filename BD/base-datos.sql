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
    estado      ENUM ('activo', 'inactivo') NOT NULL,
    eliminado   BOOL DEFAULT FALSE
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
    estado                ENUM ('activo', 'inactivo') NOT NULL,
    eliminado             BOOL DEFAULT FALSE
);

DROP TABLE IF EXISTS categorias;
CREATE TABLE IF NOT EXISTS categorias
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(100)                NOT NULL,
    descripcion    VARCHAR(255)                NOT NULL,
    fecha_creacion DATETIME                    NOT NULL,
    estado         ENUM ('activo', 'inactivo') NOT NULL,
    eliminado      BOOL DEFAULT FALSE
);

INSERT INTO categorias (nombre, descripcion, fecha_creacion, estado)
VALUES ('categoria de purbea', 'probando', NOW(), 'activo');

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
    estado         ENUM ('activo', 'inactivo') NOT NULL,
    eliminado      BOOL DEFAULT FALSE
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
    descripcion    VARCHAR(255)                 NOT NULL,
    usuario_id     INT                          NOT NULL,
    fecha_creacion DATETIME                     NOT NULL,
    estado         ENUM ('aprobado', 'anulado') NOT NULL,
    eliminado      BOOL DEFAULT FALSE
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
    descripcion    VARCHAR(255)                 NOT NULL,
    cliente_id     INT                          NOT NULL,
    usuario_id     INT                          NOT NULL,
    fecha_creacion DATETIME                     NOT NULL,
    estado         ENUM ('aprobado', 'anulado') NOT NULL,
    eliminado      BOOL DEFAULT FALSE
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
    salida_id  INT            NOT NULL,
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
    DROP COLUMN estado;

ALTER TABLE salidas
    DROP COLUMN estado;