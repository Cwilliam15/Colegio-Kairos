<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/recuperar_contrasena.css">
</head>
<body>
<main>
    <div class="caja__login">
        <h2>Recuperar Contraseña</h2>
        <form action="../backend/php/enviar_recuperacion.php" method="POST">
            <div class="caja__login-campo">
                <input type="email" required name="correo"/>
                <label>Correo electrónico registrado</label>
            </div>
            <button type="submit">Enviar enlace de recuperación</button>
        </form>
    </div>
</main>
</body>
</html>