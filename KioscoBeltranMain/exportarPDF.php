<?php
require "conexion.php";
require __DIR__ . "/fpdf/fpdf.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$inicio = $_GET["inicio"] ?? date("Y-m-d");
$fin    = $_GET["fin"] ?? date("Y-m-d");

// ---- RESUMEN DEL PERÍODO ----
$stmt = $base_de_datos->prepare("
    SELECT COUNT(*) AS cantidad, IFNULL(SUM(total),0) AS total
    FROM ventas
    WHERE DATE(fecha) BETWEEN ? AND ?
");
$stmt->bind_param("ss", $inicio, $fin);
$stmt->execute();
$resumen = $stmt->get_result()->fetch_assoc();

// ---- DETALLE DE VENTAS ----
$query = "
    SELECT v.id AS venta_id, v.fecha, v.total,
           p.codigo, p.descripcion, pv.cantidad
    FROM ventas v
    INNER JOIN productos_vendidos pv ON pv.id_venta = v.id
    INNER JOIN productos p ON p.id = pv.id_producto
    WHERE DATE(v.fecha) BETWEEN ? AND ?
    ORDER BY v.id ASC
";

$stmt2 = $base_de_datos->prepare($query);
$stmt2->bind_param("ss", $inicio, $fin);
$stmt2->execute();
$res = $stmt2->get_result();

$ventas = [];
while ($row = $res->fetch_assoc()) {
    $id = $row["venta_id"];
    if (!isset($ventas[$id])) {
        $ventas[$id] = [
            "fecha" => $row["fecha"],
            "total" => $row["total"],
            "productos" => []
        ];
    }
    $ventas[$id]["productos"][] = $row;
}

// --- PDF TIPO TICKET (ANCHO REDUCIDO) ---
class TicketPDF extends FPDF {
    function Header() {
        // Título centrado
        $this->SetFont("Arial", "B", 14);
        $this->Cell(0, 8, utf8_decode("Kiosco Beltrán"), 0, 1, "C");

        $this->SetFont("Arial", "", 10);
        $this->Cell(0, 5, utf8_decode("Reporte de ventas"), 0, 1, "C");
        $this->Ln(2);

        // Línea
        $this->SetDrawColor(0,0,0);
        $this->Line(10, $this->GetY(), 70, $this->GetY());
        $this->Ln(3);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont("Arial", "I", 8);
        $this->Cell(0,10,utf8_decode("Gracias por usar el sistema"),0,0,"C");
    }
}

$pdf = new TicketPDF("P", "mm", array(80, 350)); // ancho ticket típico
$pdf->AddPage();

$pdf->SetFont("Arial","",10);

$pdf->Cell(0,5,utf8_decode("Desde: $inicio"),0,1,"L");
$pdf->Cell(0,5,utf8_decode("Hasta: $fin"),0,1,"L");
$pdf->Ln(2);

// Resumen
$pdf->SetFont("Arial","B",11);
$pdf->Cell(0,6,utf8_decode("Resumen del periodo"),0,1);
$pdf->SetFont("Arial","",10);
$pdf->Cell(0,5,utf8_decode("Ventas:  ".$resumen["cantidad"]),0,1);
$pdf->Cell(0,5,utf8_decode("Total:   $".number_format($resumen["total"],2,',','.')),0,1);
$pdf->Ln(3);

// Línea divisoria
$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());
$pdf->Ln(3);

// ---- DETALLE DE CADA VENTA ----
foreach ($ventas as $id => $v) {

    $pdf->SetFont("Arial","B",11);
    $pdf->Cell(0,6,utf8_decode("Venta #$id"),0,1);
    
    $pdf->SetFont("Arial","",10);
    $pdf->Cell(0,5,utf8_decode("Fecha: ".$v["fecha"]),0,1);
    $pdf->Cell(0,5,utf8_decode("Total: $".number_format($v["total"],2,',','.')),0,1);

    $pdf->Ln(1);
    $pdf->SetFont("Arial","I",9);
    $pdf->Cell(0,4,"Productos:",0,1);

    $pdf->SetFont("Arial","",9);
    foreach ($v["productos"] as $p) {
        $linea = "{$p['codigo']} - {$p['descripcion']} x {$p['cantidad']}";
        $pdf->MultiCell(0,4, utf8_decode($linea));
    }

    // Separador entre ventas
    $pdf->Ln(2);
    $pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());
    $pdf->Ln(3);
}

$pdf->Output("I", "Ticket_$inicio-$fin.pdf");
