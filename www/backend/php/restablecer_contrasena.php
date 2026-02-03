<?php
session_start();
if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['token']) {
    die("❌ Token inválido o expirado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
<main>
    <div class="caja__login">
        <h2>Restablecer Contraseña</h2>
        <form action="procesar_nueva_contrasena.php" method="POST">
            <div class="caja__login-campo">
                <input type="password" required name="nueva_contrasena"/>
                <label>Nueva contraseña</label>
            </div>
            <button type="submit">Restablecer</button>
        </form>
    </div>
</main>
</body>
</html>
