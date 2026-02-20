<?php
if(
    !isset($_POST["codigo"]) || 
    !isset($_POST["descripcion"]) || 
    !isset($_POST["precioCompra"]) || 
    !isset($_POST["precioVenta"]) || 
    !isset($_POST["existencia"]) || 
    !isset($_POST["id"])
) exit();

require "conexion.php";

$id = $_POST["id"];
$codigo = $_POST["codigo"];
$descripcion = $_POST["descripcion"];
$precioCompra = $_POST["precioCompra"];
$precioVenta = $_POST["precioVenta"];
$existencia = $_POST["existencia"];

$stmt = $base_de_datos->prepare(
    "UPDATE productos SET codigo=?, descripcion=?, precioCompra=?, precioVenta=?, existencia=? WHERE id=?"
);

$stmt->bind_param("ssddii", $codigo, $descripcion, $precioCompra, $precioVenta, $existencia, $id);

if ($stmt->execute()) {
    header("Location: listar.php");
    exit;
} else {
    echo "Error al actualizar el producto";
}
