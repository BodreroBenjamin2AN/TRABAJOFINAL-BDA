CREATE DATABASE IF NOT EXISTS fitzone;
USE fitzone;

CREATE TABLE IF NOT EXISTS Region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Sucursal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    region_id INT,
    FOREIGN KEY (region_id) REFERENCES Region(id)
);

CREATE TABLE IF NOT EXISTS Categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria_id INT,
    precio_unitario DECIMAL(10,2),
    stock INT DEFAULT 0,
    stock_inicial INT DEFAULT 0,
    precio_compra_unitaria DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (categoria_id) REFERENCES Categoria(id)
);

CREATE TABLE IF NOT EXISTS Cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    region_id INT,
    FOREIGN KEY (region_id) REFERENCES Region(id)
);

CREATE TABLE IF NOT EXISTS Venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE,
    sucursal_id INT,
    cliente_id INT,
    usuario_id INT,
    producto_id INT,
    cantidad INT,
    total DECIMAL(10,2),
    FOREIGN KEY (sucursal_id) REFERENCES Sucursal(id),
    FOREIGN KEY (cliente_id) REFERENCES Cliente(id),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id),
    FOREIGN KEY (producto_id) REFERENCES Producto(id)
);

-- AGREGA LA TABLA DE USUARIOS Y UN USUARIO DE PRUEBA:
CREATE TABLE IF NOT EXISTS Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Usuario: admin@fitzone.com  Contraseña: fitzone123
INSERT INTO Usuario (email, password) VALUES (
    'admin@fitzone.com',
    '$2y$10$eW5Qw6QwQwQwQwQwQwQwQeQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw'
);