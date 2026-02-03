<?php
session_start();
if(isset($_SESSION['usuario'])) {
    header("location: ../frontend/html/cargando.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kairos</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <main>
    <div class="caja__login">
    <h2>Colegio Mixto Kairos</h2>
    <p class="typewriter subtitle">Sistema de Control de Asistencia</p>
        <img src="assets/images/LogoCC.png" alt="Logo del Colegio" class="logo__colegio rotate-scale-down-diag-2" />
        <form action="backend/php/login_usuario_be.php" method="POST">
            <div class="caja__login-campo">
                <input type="text" required name="usuario" />
                <label>Usuario</label>
            </div>
            <div class="caja__login-campo">
                <input type="password" required name="contrasena"/>
                <label>Contraseña</label>
            </div>
        <button type="submit">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
                Entrar
        </button>
        <div class="recuperar__contrasena">
            <a href="assets/recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
        </div>
        </form>
    </div>
    </main>
        <script src="assets/js/script.js"></script>
</body>
</html>