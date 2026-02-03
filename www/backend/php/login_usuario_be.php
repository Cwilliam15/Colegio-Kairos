<?php
session_start();
include 'conexion_be.php';
require_once 'logger.php';

// Consulta preparada (asegúrate de también traer el id)
$stmt = $conexion->prepare("
    SELECT id, usuario, rol 
    FROM usuarios 
    WHERE usuario = ? 
      AND contrasena = ?
");
$stmt->bind_param("ss", $_POST['usuario'], hash('sha512', $_POST['contrasena']));
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['usuario']    = $user['usuario'];
    $_SESSION['rol']        = $user['rol'];
    $_SESSION['usuario_id'] = $user['id'];

    // === Llamada correcta ===
    registrarLog("Inicio de sesión exitoso");

    header("Location: ../../frontend/html/cargando.php");
    exit();
} else {
    // Si quieres loggear fallos, podrías hacer algo como:
    // registrarLog("Intento de login fallido para usuario “{$_POST['usuario']}”");
    echo '<script>
            alert("Usuario o contraseña incorrectos");
            window.location="../../index.php";
          </script>';
    exit();
}
$stmt->close();
$conexion->close(); // Cerrar la conexión a la base de datos
?>