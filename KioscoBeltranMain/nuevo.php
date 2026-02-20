<?php
session_start();
require_once "conexion.php";   // ← FIX AQUÍ

if (!isset($_POST["codigo"]) || !isset($_POST["descripcion"]) 
    || !isset($_POST["precioCompra"]) || !isset($_POST["precioVenta"]) 
    || !isset($_POST["existencia"])) {

    echo "Faltan datos";
    exit;
}

$codigo = $_POST["codigo"];
$descripcion = $_POST["descripcion"];
$precioCompra = $_POST["precioCompra"];
$precioVenta = $_POST["precioVenta"];
$existencia = $_POST["existencia"];

$sentencia = $base_de_datos->prepare("
    INSERT INTO productos (codigo, descripcion, precioCompra, precioVenta, existencia) 
    VALUES (?, ?, ?, ?, ?)
");
$sentencia->bind_param("ssddi", $codigo, $descripcion, $precioCompra, $precioVenta, $existencia);

if ($sentencia->execute()) {
    header("Location: listar.php?status=ok");
    exit;
} else {
    echo "Error al registrar producto";
}
