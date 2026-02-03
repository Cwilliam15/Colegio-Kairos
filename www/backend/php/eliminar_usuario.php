<?php
session_start();
include('conexion_be.php'); // Conexión a la base de datos
include('logger.php'); // Para registrar actividades

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);

    // Verifica que el usuario exista
    $verificar = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $verificar->bind_param("s", $usuario);
    $verificar->execute();
    $verificar->store_result();

    if($verificar->num_rows == 0){
        echo '<script>
                alert("El usuario no existe");
                window.location = "../../frontend/html/admin.php";
              </script>';
        $verificar->close();
        $conexion->close();
        exit();
    }
    $verificar->close();

    // Eliminar usuario
    $eliminar = $conexion->prepare("DELETE FROM usuarios WHERE usuario = ?");
    $eliminar->bind_param("s", $usuario);

    if ($eliminar->execute()) {
        // ✅ Registrar actividad
        registrarLog("Eliminó al usuario con ID $idUsuario");

        echo '<script>
                alert("Usuario eliminado exitosamente");
                window.location = "../../frontend/html/admin.php";
              </script>';
    } else {
        registrarLog($conexion, "❌ Error al intentar eliminar al usuario: " . $idUsuario);

        echo '<script>
                alert("Error al eliminar el usuario");
                window.location = "../../frontend/html/admin.php";
              </script>';
    }

    $eliminar->close();
    $conexion->close();
}
?>