<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Ventas Beltrán</title>

	<!-- Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

	<!-- Estilos Premium -->
	<style>
		body {
			background: #f3faff;
		}

		/* NAV PREMIUM */
		.navbar-premium {
			background: rgba(255, 255, 255, 0.75);
			backdrop-filter: blur(10px);
			border-bottom: 1px solid rgba(0,0,0,0.1);
			box-shadow: 0 4px 16px rgba(0,0,0,0.15);
		}

		.navbar-premium .navbar-brand {
			font-weight: 700;
			color: #0a3d62 !important;
			font-size: 1.3rem;
			letter-spacing: 1px;
		}

		.navbar-premium .nav-link {
			font-weight: 600;
			color: #0a3d62 !important;
			margin-right: 10px;
			transition: 0.2s;
		}

		.navbar-premium .nav-link:hover {
			color: #0d6efd !important;
			transform: translateY(-2px);
		}

		/* Contenedor */
		.main-container {
			margin-top: 90px;
		}

	</style>
</head>
<body>

	<!-- NAVBAR MODERNA -->
	<nav class="navbar navbar-expand-lg navbar-premium fixed-top">
		<div class="container">

			<a class="navbar-brand" href="index.php">
				<i class="fa-solid fa-store"></i> Kiosco Beltrán
			</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="menuNav">
				<ul class="navbar-nav ms-auto mb-2 mb-lg-0">

					<li class="nav-item">
						<a class="nav-link" href="./listar.php">
							<i class="fa-solid fa-boxes-stacked"></i> Productos
						</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="./vender.php">
							<i class="fa-solid fa-cart-shopping"></i> Vender
						</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="./ventas.php">
							<i class="fa-solid fa-receipt"></i> Ventas
						</a>
					</li>

					<li class="nav-item">
						<a class="nav-link text-danger" href="./logout.php">
							<i class="fa-solid fa-right-from-bracket"></i> Salir
						</a>
					</li>

				</ul>
			</div>

		</div>
	</nav>

	<!-- CONTENIDO -->
	<div class="container main-container">
		<div class="row">
