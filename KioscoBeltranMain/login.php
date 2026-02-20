<?php
session_start();
require_once "conexion.php";

// Si ya está logueado → ir al inicio
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // 🔥 FIX: cambiar $mysqli → $base_de_datos
    $stmt = $base_de_datos->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["nombre"];
            header("Location: index.php");
            exit;
        } else {
            $login_error = "Contraseña incorrecta.";
        }
    } else {
        $login_error = "Email no registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Sistema</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body {
    background: linear-gradient(120deg, #009ffd, #2a2a72);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', sans-serif;
}

.glass-box {
    width: 380px;
    background: rgba(255,255,255,0.15);
    border-radius: 18px;
    padding: 35px;
    box-shadow: 0 0 25px rgba(0,0,0,0.25);
    backdrop-filter: blur(12px);
    color: white;
    animation: fadeIn 0.6s ease;
}

/* --- LOGO GLASS --- */
.logo {
    width: 110px;
    padding: 12px;
    background: rgba(255,255,255,0.25);
    border-radius: 16px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.6);
    box-shadow: 0 0 12px rgba(255,255,255,0.7);
    margin-bottom: 15px;
}

.glass-box h2 {
    font-weight: 700;
    text-shadow: 0 0 3px rgba(0,0,0,0.4);
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

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
}

</style>
</head>

<body>
<div class="glass-box text-center">

    <img src="escudo2.png" class="logo">

    <h2 class="mb-4">Iniciar Sesión</h2>

    <?php if ($login_error): ?>
    <div class="alert alert-danger">
        <?= $login_error ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3 text-start">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100 mt-2">Entrar</button>
    </form>

    <hr class="border-light">

    <p>¿No tenés cuenta?</p>
    <a href="registro.php" class="btn btn-outline-light w-100 mb-2">Registrarse</a>

    <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>

</div>
</body>
</html>
