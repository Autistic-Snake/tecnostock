CREATE DATABASE IF NOT EXISTS tecnostock
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE tecnostock;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    estado TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    precio DECIMAL(10,2) NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 0,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_categoria INT NOT NULL,
    CONSTRAINT fk_producto_categoria
        FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    CONSTRAINT ck_precio CHECK (precio >= 0),
    CONSTRAINT ck_stock_actual CHECK (stock_actual >= 0),
    CONSTRAINT ck_stock_minimo CHECK (stock_minimo >= 0)
);

CREATE TABLE IF NOT EXISTS movimientos (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    fecha_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tipo ENUM('entrada', 'salida') NOT NULL,
    cantidad INT NOT NULL,
    observacion VARCHAR(255),
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_mov_producto
        FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    CONSTRAINT fk_mov_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT ck_cantidad CHECK (cantidad > 0)
);

-- Datos iniciales (clave del usuario: admin123)
INSERT IGNORE INTO usuarios (nombre, correo, clave) VALUES
    ('Encargado de Inventario', 'admin@tecnostock.cl',
     '$2y$12$IYtPWB6aHFa4.ojkroGPT.QOQJP4MPZs/BNdRnRqYlvFxy5aUnw7W');

INSERT IGNORE INTO categorias (nombre, descripcion) VALUES
    ('Accesorios', 'Mouse, teclados y similares'),
    ('Almacenamiento', 'Pendrives y discos'),
    ('Conectividad', 'Routers y adaptadores');

INSERT IGNORE INTO productos
    (codigo, nombre, descripcion, precio, stock_actual, stock_minimo, id_categoria)
VALUES
    ('TEC-001', 'Mouse inalámbrico', 'Mouse óptico 2.4 GHz', 9990, 25, 5, 1),
    ('TEC-002', 'Pendrive 64 GB', 'USB 3.0', 7990, 3, 5, 2),
    ('TEC-003', 'Router WiFi', 'Doble banda', 24990, 10, 4, 3);