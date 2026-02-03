<?php
session_start();

// 1) Conexión a BD (ajusta la ruta según tu proyecto)
require_once __DIR__ . '/../backend/php/conexion_be.php';

$error = '';
$token = $_GET['token'] ?? '';

if (!$token) {
    die('Token no proporcionado.');
}

// 2) Verificar que el token existe
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE token_recuperacion = ?");
if (!$stmt) {
    die('Error al preparar la consulta: ' . $conexion->error);
}
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die('Token inválido o ya usado.');
}

// 3) Si llegan datos por POST, procesar el cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = $_POST['pass1'] ?? '';
    $pass2 = $_POST['pass2'] ?? '';

    if (strlen($pass1) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($pass1 !== $pass2) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        // 4) Guardar nueva contraseña con hash('sha512') y anular token
        $hash = hash('sha512', $pass1);
        $upd  = $conexion->prepare(
            "UPDATE usuarios 
             SET contrasena = ?, token_recuperacion = NULL 
             WHERE token_recuperacion = ?"
        );
        $upd->bind_param("ss", $hash, $token);
        $upd->execute();

        $_SESSION['mensaje_exito'] = 'Contraseña restablecida correctamente.';
        header('Location: ../index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer Contraseña</title>
  <link rel="stylesheet" href="css/recuperar_contrasena.css">
</head>
<body>
<main>
  <div class="caja__login">
    <h2>Restablecer Contraseña</h2>

    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <div class="caja__login-campo">
        <input type="password" name="pass1" required />
        <label>Nueva contraseña</label>
      </div>
      <div class="caja__login-campo">
        <input type="password" name="pass2" required />
        <label>Repite la contraseña</label>
      </div>
      <button type="submit">Guardar nueva contraseña</button>
    </form>
  </div>
</main>
</body>
</html>
