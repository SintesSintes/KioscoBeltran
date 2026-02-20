<?php
session_start();

// SI NO HAY DATOS → ERROR
if (!isset($_POST["carrito"])) {
    header("Location: vender.php?status=0");
    exit;
}

require "conexion.php"; // carga $base_de_datos (MYSQLi)

$carrito = json_decode($_POST["carrito"], true);

if (!$carrito || count($carrito) == 0) {
    header("Location: vender.php?status=0");
    exit;
}

// CALCULAR TOTAL
$total = 0;
foreach ($carrito as $p) {
    $total += $p["precio"] * $p["cantidad"];
}

$fecha = date("Y-m-d H:i:s");

// GUARDAR VENTA
$sqlVenta = $base_de_datos->prepare("INSERT INTO ventas (fecha, total) VALUES (?, ?)");
$sqlVenta->bind_param("sd", $fecha, $total);
$sqlVenta->execute();

$idVenta = $base_de_datos->insert_id;

// GUARDAR PRODUCTOS VENDIDOS
$sqlProducto = $base_de_datos->prepare("
    INSERT INTO productos_vendidos (id_producto, id_venta, cantidad)
    VALUES (?, ?, ?)
");

$sqlStock = $base_de_datos->prepare("
    UPDATE productos SET existencia = existencia - ? WHERE id = ?
");

foreach ($carrito as $p) {
    $idProd = $p["id"];
    $cant = $p["cantidad"];

    // Insertar producto vendido
    $sqlProducto->bind_param("iii", $idProd, $idVenta, $cant);
    $sqlProducto->execute();

    // Actualizar stock
    $sqlStock->bind_param("ii", $cant, $idProd);
    $sqlStock->execute();
}

// LIMPIAR CARRITO
unset($_SESSION["carrito"]);

header("Location: vender.php?status=1");
exit;
?>
