<?php
if (!isset($_POST["codigo"])) {
    return;
}

$codigo = trim($_POST["codigo"]);
require "conexion.php"; // mysqli

// preparar y ejecutar
$stmt = $base_de_datos->prepare("SELECT * FROM productos WHERE codigo = ? LIMIT 1;");
$stmt->bind_param("s", $codigo);
$stmt->execute();
$res = $stmt->get_result();
$producto = $res->fetch_object();
$stmt->close();

if (!$producto) {
    header("Location: ./vender.php?status=4");
    exit;
}

// Si no hay existencia...
if ($producto->existencia < 1) {
    header("Location: ./vender.php?status=5");
    exit;
}

session_start();

if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

$indice = false;
for ($i = 0; $i < count($_SESSION["carrito"]); $i++) {
    if ($_SESSION["carrito"][$i]->codigo === $codigo) {
        $indice = $i;
        break;
    }
}

if ($indice === false) {
    // mover a objeto para no romper la estructura
    $item = clone $producto;
    $item->cantidad = 1;
    $item->total = $item->precioVenta;
    array_push($_SESSION["carrito"], $item);
} else {
    $cantidadExistente = $_SESSION["carrito"][$indice]->cantidad;
    if ($cantidadExistente + 1 > $producto->existencia) {
        header("Location: ./vender.php?status=5");
        exit;
    }
    $_SESSION["carrito"][$indice]->cantidad++;
    $_SESSION["carrito"][$indice]->total = $_SESSION["carrito"][$indice]->cantidad * $_SESSION["carrito"][$indice]->precioVenta;
}

header("Location: ./vender.php");
exit;
?>
