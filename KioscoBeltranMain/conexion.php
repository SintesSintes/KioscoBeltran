<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ventasbeltran";

// Conexión única del sistema
$base_de_datos = new mysqli($host, $user, $pass);

if ($base_de_datos->connect_error) {
    die("Error de conexión: " . $base_de_datos->connect_error);
}

// Crear base si no existe
$base_de_datos->query("CREATE DATABASE IF NOT EXISTS $dbname;");
$base_de_datos->select_db($dbname);

// ---- TABLA PRODUCTOS ----
$base_de_datos->query("
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50),
    descripcion VARCHAR(255),
    precioCompra DECIMAL(10,2),
    precioVenta DECIMAL(10,2),
    existencia INT
);
");

// ---- TABLA USUARIOS ----
$base_de_datos->query("
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);
");

// ---- TABLA VENTAS ----
$base_de_datos->query("
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL
);
");

// ---- TABLA PRODUCTOS VENDIDOS ----
$base_de_datos->query("
CREATE TABLE IF NOT EXISTS productos_vendidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_venta INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id),
    FOREIGN KEY (id_venta) REFERENCES ventas(id)
);
");
?>
