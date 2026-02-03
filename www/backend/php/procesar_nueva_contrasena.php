<?php
session_start();
include('conexion_be.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['correo_recuperacion'])) {
    $correo = $_SESSION['correo_recuperacion'];
    $nueva_contrasena = hash('sha512', $_POST['nueva_contrasena']);

    // Actualizar la contraseña y limpiar el token
    $stmt = $conexion->prepare("UPDATE usuarios SET contrasena = ?, token_recuperacion = NULL WHERE correo = ?");
    $stmt->bind_param("ss", $nueva_contrasena, $correo);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Contraseña actualizada correctamente.";
        unset($_SESSION['correo_recuperacion']);
    } else {
        $_SESSION['mensaje'] = "❌ Error al actualizar la contraseña.";
    }

    $stmt->close();
    $conexion->close();
    header("Location: ../../index.php");
    exit();
}
?>
