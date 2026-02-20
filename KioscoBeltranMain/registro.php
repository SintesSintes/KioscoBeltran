<?php
session_start();
require_once "conexion.php";

$registro_error = "";
$registro_ok = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if (!$nombre || !$email || !$password || !$password2) {
        $registro_error = "Completa todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registro_error = "Email inválido.";
    } elseif ($password !== $password2) {
        $registro_error = "Las contraseñas no coinciden.";
    } else {
        // Verificar email único
        $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $registro_error = "El email ya está registrado.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO usuarios (nombre,email,password) VALUES (?,?,?)");
            $stmt->bind_param("sss", $nombre, $email, $hash);
            $stmt->execute();

            $registro_ok = "¡Cuenta creada! Ahora podés iniciar sesión.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Cuenta</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(120deg, #009ffd, #2a2a72);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI';
}

.glass-box {
    width: 420px;
    background: rgba(255,255,255,0.16);
    border-radius: 18px;
    padding: 35px;
    backdrop-filter: blur(12px);
    box-shadow: 0 0 25px rgba(0,0,0,0.25);
    color: white;
    animation: fadeIn 0.6s ease;
}

.logo {
    width: 120px;
}

.form-control {
    background: rgba(255,255,255,0.4);
    color: #000;
}

.btn-primary {
    background: #00c3ff;
    border: none;
}

.btn-primary:hover {
    background: #009fe3;
}

a { color: #fff; }

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>
</head>

<body>
<div class="glass-box text-center">

    <img src="escudo2.png" class="logo mb-3">

    <h2 class="mb-3">Crear Cuenta</h2>

    <?php if ($registro_error): ?>
    <div class="alert alert-danger"><?= $registro_error ?></div>
    <?php endif; ?>

    <?php if ($registro_ok): ?>
    <div class="alert alert-success"><?= $registro_ok ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3 text-start">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Repetir contraseña</label>
            <input type="password" name="password2" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Registrarme</button>
    </form>

    <hr class="border-light">

    <a href="login.php" class="btn btn-outline-light w-100">Volver al Login</a>

</div>
</body>
</html>
