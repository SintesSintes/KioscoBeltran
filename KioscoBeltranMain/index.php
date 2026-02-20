<?php
session_start();

// Si no está logueado → al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user_name"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inicio - Sistema Kiosco</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root{
  --primary-1: #009ffd;
  --primary-2: #2a2a72;
  --glass-bg: rgba(255,255,255,0.16);
  --glass-strong: rgba(255,255,255,0.22);
  --accent: #00c3ff;
}

/* Page */
body {
    background: linear-gradient(120deg, var(--primary-1), var(--primary-2));
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    color: #04293a;
    margin: 0;
    padding-bottom: 140px; /* espacio para footer */
}

/* Container central */
.container-box {
    width: 90%;
    max-width: 1000px;
    margin: 48px auto;
    background: linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.06));
    border-radius: 18px;
    padding: 34px;
    box-shadow: 0 12px 40px rgba(2,48,71,0.18);
    backdrop-filter: blur(8px);
    color: white;
    position: relative;
    overflow: visible;
}

/* Header */
.header-top {
    display:flex;
    gap:16px;
    align-items:center;
    justify-content:space-between;
    margin-bottom:14px;
}
.brand-left { display:flex; gap:12px; align-items:center; }
.brand-logo {
    width:72px;
    padding:10px;
    background: rgba(255,255,255,0.25);
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    backdrop-filter: blur(6px);
}
.brand-title {
    font-size:1.25rem;
    font-weight:800;
    color: #fff;
    letter-spacing: 0.4px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.25);
}

/* Logout */
.logout-btn {
    margin-left: 10px;
}
.btn-logout {
    background: rgba(255,255,255,0.12);
    color: white;
    border: 1px solid rgba(255,255,255,0.16);
}

/* Cards */
.row-cards { margin-top:20px; gap:22px; }
.card-glass {
    background: var(--glass-bg);
    border-radius: 14px;
    padding: 22px;
    border: 1px solid rgba(255,255,255,0.14);
    box-shadow: 0 8px 22px rgba(2,48,71,0.08);
    min-height: 170px;
}
.card-glass h3 { color: #fff; font-weight:700; margin-bottom:8px; }
.card-glass p { color: rgba(255,255,255,0.9); margin-bottom:14px; }

/* Buttons */
.btn-custom {
    background: linear-gradient(90deg, var(--accent), #0096c7);
    border: none;
    color: white;
    font-weight:700;
    border-radius:10px;
    padding:10px 14px;
    box-shadow: 0 8px 20px rgba(0,156,255,0.18);
}
.btn-outline-custom{
    background: rgba(255,255,255,0.12);
    border:1px solid rgba(255,255,255,0.14);
    color: white;
    font-weight:600;
    border-radius:10px;
    padding:10px 14px;
}

/* Footer premium */
.site-footer {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 28px 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.12), rgba(0,0,0,0.18));
    color: white;
    backdrop-filter: blur(8px);
    border-top: 1px solid rgba(255,255,255,0.05);
}
.footer-inner {
    width: 90%;
    max-width: 1100px;
    margin: 0 auto;
    display:flex;
    gap: 22px;
    align-items:center;
    justify-content:space-between;
}
.footer-brand { display:flex; gap:16px; align-items:center; }
.footer-logo {
    width:72px;
    padding:10px;
    background: rgba(255,255,255,0.2);
    border-radius:14px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}
.footer-links { display:flex; gap:18px; align-items:center; }
.footer-links a { color: rgba(255,255,255,0.9); text-decoration:none; font-weight:600; }
.footer-social { display:flex; gap:12px; align-items:center; }
.footer-social a { color:white; font-size:18px; text-decoration:none; background: rgba(255,255,255,0.06); padding:8px 10px; border-radius:10px; }

/* Small */
.small-muted { color: rgba(255,255,255,0.85); font-size:0.95rem; }
.copy { opacity:0.85; font-size:0.9rem; }

/* Responsiveness */
@media (max-width: 900px) {
    .footer-inner { flex-direction:column; gap:12px; align-items:center; text-align:center; }
    .header-top { flex-direction:column; align-items:flex-start; gap:12px;}
    .row-cards { display:block; }
    .card-glass { margin-bottom:16px; }
    body { padding-bottom: 220px; }
}
</style>
</head>

<body>

<div class="container-box">

    <div class="header-top">
        <div class="brand-left">
            <img src="escudo2.png" alt="Escudo" class="brand-logo">
            <div>
                <div class="brand-title">Kiosco Beltrán</div>
                <div class="small-muted">Bienvenido al sistema administrativo de un Kiosco</div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <div class="me-3 text-white text-end">
                <div style="font-weight:700; font-size:0.95rem;"><?= htmlspecialchars($user) ?></div>
                <div class="small-muted">Usuario activo</div>
            </div>
            <a href="logout.php" class="btn btn-logout">Cerrar sesión <i class="fa-solid fa-right-from-bracket ms-2"></i></a>
        </div>
    </div>

    <p class="text-center mb-4 fs-5" style="color:rgba(255,255,255,0.95)">
        Sistema Kiosco Beltrán – Gestión de productos, ventas y usuarios.
    </p>

    <div class="row row-cards">
        <div class="col-md-6">
            <div class="card-glass">
                <h3>Productos</h3>
                <p>Administrá el catálogo: agregar, editar, eliminar y controlar stock.</p>
                <a href="listar.php" class="btn btn-custom w-100">Ver Productos</a>
                <a href="formulario.php" class="btn btn-outline-custom w-100 mt-2">Agregar Producto</a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-glass">
                <h3>Ventas</h3>
                <p>Iniciá ventas rápidas desde el código de barras, hacer tickets y consultá el historial.</p>
                <a href="vender.php" class="btn btn-custom w-100">Nueva Venta</a>
                <a href="ventas.php" class="btn btn-outline-custom w-100 mt-2">Historial de Ventas</a>
            </div>
        </div>
    </div>

    <hr style="border-color: rgba(255,255,255,0.06); margin-top:26px; margin-bottom:20px;">

    <div class="row" style="gap:18px;">
        <div class="col-md-6">
            <div class="card-glass p-3">
                <h4>Quiénes somos</h4>
                <p class="small-muted">Instituto Beltrán — Proyecto educativo y demo para prácticas con XAMPP, PHP y MySQL.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card-glass p-3">
                <h4>Contactoo</h4>
                <p class="small-muted">Email: soporte@gmail.com · Tel: +54 9 1234 5678</p>
            </div>
        </div>
    </div>

    <p class="text-center copy" style="margin-top:18px">© <?= date("Y") ?> Kiosco Beltrán — Sistema PHP + MySQL. Hecho por los estudiantes de Practicas Profesionalizantes 2</p>

</div>

<!-- FOOTER PREMIUM -->
<footer class="site-footer" aria-label="Footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <img src="escudo2.png" class="footer-logo" alt="Escudo">
            <div>
                <div style="font-weight:800; font-size:1.05rem">Kiosco Beltrán</div>
                <div class="small-muted">Gestión — Productos · Ventas · Usuarios</div>
            </div>
        </div>

        <div class="footer-links">
            <a href="listar.php">Productos</a>
            <a href="vender.php">Vender</a>
            <a href="ventas.php">Ventas</a>
            <a href="contacto.php">Contacto</a>
        </div>

        <div class="footer-social">
            <a href="#" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" title="Whatsapp"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
