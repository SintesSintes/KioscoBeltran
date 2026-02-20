<?php
session_start();
require "conexion.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// --- FECHAS ---
// Si el usuario no filtra, se usa HOY como único rango.
$fecha_inicio = $_GET["inicio"] ?? date("Y-m-d");
$fecha_fin     = $_GET["fin"] ?? date("Y-m-d");

// ---- TOTAL FILTRADO ----
$sqlDia = $base_de_datos->prepare("
    SELECT COUNT(*) AS cantidadVentas, IFNULL(SUM(total),0) AS totalDia
    FROM ventas
    WHERE DATE(fecha) BETWEEN ? AND ?
");
$sqlDia->bind_param("ss", $fecha_inicio, $fecha_fin);
$sqlDia->execute();
$resDia = $sqlDia->get_result()->fetch_assoc();

$cantidadVentas = $resDia["cantidadVentas"];
$totalDia       = $resDia["totalDia"];

// ---- LISTA DE VENTAS ----
$query = "
    SELECT v.id AS venta_id, v.fecha, v.total,
           p.codigo, p.descripcion, pv.cantidad
    FROM ventas v
    INNER JOIN productos_vendidos pv ON pv.id_venta = v.id
    INNER JOIN productos p ON p.id = pv.id_producto
    WHERE DATE(v.fecha) BETWEEN ? AND ?
    ORDER BY v.id DESC
";
$stmt = $base_de_datos->prepare($query);
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
$stmt->execute();
$result = $stmt->get_result();

$ventas = [];
while ($row = $result->fetch_assoc()) {
    $id = $row["venta_id"];
    if (!isset($ventas[$id])) {
        $ventas[$id] = [
            "id" => $id,
            "fecha" => $row["fecha"],
            "total" => $row["total"],
            "productos" => []
        ];
    }
    $ventas[$id]["productos"][] = $row;
}
$ventas = array_values($ventas);

include_once "encabezado.php";
?>

<style>
.page-title {
    font-size: 2rem;
    color: #073a67;
    font-weight: 800;
}
.wrapper-glass {
    background: rgba(255,255,255,0.20);
    backdrop-filter: blur(10px);
    padding: 22px;
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.22);
    box-shadow: 0 8px 22px rgba(2,48,71,0.15);
}
.btn-premium {
    background: linear-gradient(135deg,#009ffd,#00c6ff);
    color: white;
    padding: 10px 20px;
    border-radius: 12px;
    border: none;
}
.btn-premium:hover { opacity: .85; }
.summary-box {
    background: linear-gradient(135deg, #d0ecff, #a4dcff);
    padding: 18px;
    border-radius: 12px;
    border: 2px solid white;
}
.table-premium thead th {
    background: linear-gradient(90deg,#009ffd,#42baff);
    color: white;
    text-align: center;
    font-weight: 700;
}
.card-product {
    background: rgba(255,255,255,0.9);
    padding: 10px;
    border-radius: 10px;
}
</style>

<div class="container">

    <h1 class="page-title mb-3">Historial de Ventas</h1>

    <!-- FILTRO -->
    <form method="GET" class="wrapper-glass mb-4">
        <label class="fw-bold">Filtrar ventas</label>
        <div class="row mt-2">
            <div class="col-md-4">
                <label class="form-label">Desde:</label>
                <input type="date" name="inicio" value="<?= $fecha_inicio ?>" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Hasta:</label>
                <input type="date" name="fin" value="<?= $fecha_fin ?>" class="form-control">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn-premium w-100">Aplicar filtro</button>
            </div>
        </div>
    </form>

    <!-- RESUMEN -->
    <div class="summary-box mb-4">
        <h4 class="mb-2 text-dark fw-bold">Resumen del período</h4>
        <p class="mb-1"><strong>Ventas realizadas:</strong> <?= $cantidadVentas ?></p>
        <p class="mb-1"><strong>Total generado:</strong> $<?= number_format($totalDia,2,',','.') ?></p>

        <a href="exportarPDF.php?inicio=<?= $fecha_inicio ?>&fin=<?= $fecha_fin ?>" 
           target="_blank" 
           class="btn btn-danger mt-2">
            Descargar PDF
        </a>
    </div>

    <!-- TABLA -->
    <div class="wrapper-glass table-responsive">
        <table class="table table-premium table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Productos</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $v): ?>
                <tr>
                    <td><strong><?= $v["id"] ?></strong></td>
                    <td><?= $v["fecha"] ?></td>
                    <td class="fw-bold">$<?= number_format($v["total"],2,',','.') ?></td>
                    <td>
                        <?php foreach ($v["productos"] as $p): ?>
                            <div class="card-product mt-1">
                                <strong><?= $p["codigo"] ?></strong> — <?= $p["descripcion"] ?>  
                                <div class="text-muted">Cant: <?= $p["cantidad"] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-danger" onclick="return confirm('¿Eliminar venta?')" 
                           href="eliminarVenta.php?id=<?= $v['id'] ?>">
                           <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($ventas)): ?>
                <tr><td colspan="5" class="text-center p-4">No hay ventas en ese rango</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include "pie.php"; ?>
