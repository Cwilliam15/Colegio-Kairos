<?php
session_start();
include('conexion_be.php');
include('logger.php'); // Importante incluirlo para poder usar registrarActividad()

// Sanitizar las entradas
$usuario = trim($_POST['usuario']);
$contrasena_actual = trim($_POST['contrasena_actual']);
$nueva_contrasena = trim($_POST['nueva_contrasena']);

// Encriptar contraseñas
$contrasena_actual_encriptada = hash('sha512', $contrasena_actual);
$nueva_contrasena_encriptada = hash('sha512', $nueva_contrasena);

// Verificar usuario y contraseña actual
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ? AND contrasena = ?");
$stmt->bind_param("ss", $usuario, $contrasena_actual_encriptada);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Usuario y contraseña actuales válidos
    $stmt->close();

    // Actualizar contraseña
    $update = $conexion->prepare("UPDATE usuarios SET contrasena = ? WHERE usuario = ?");
    $update->bind_param("ss", $nueva_contrasena_encriptada, $usuario);

    if ($update->execute()) {
        // ✅ Registrar en logs que cambió contraseña
        registrarLog("Cambio de contraseña exitoso");

        echo '<script>
                alert("✅ Contraseña actualizada exitosamente.");
                window.location = "../../frontend/html/admin.php";
              </script>';
    } else {
        // ❌ Registrar en logs que falló actualización
        registrarLog("❌ Error al intentar cambiar contraseña");

        echo '<script>
                alert("❌ Error al actualizar la contraseña.");
                window.location = "../../frontend/html/admin.php";
              </script>';
    }

    $update->close();
} else {
    // ⚠️ Registrar intento fallido por contraseña incorrecta
    registrarLog("⚠️ Intento fallido de cambio de contraseña (contraseña actual incorrecta)");

    echo '<script>
            alert("⚠️ Usuario o contraseña actual incorrectos. Intenta de nuevo.");
            window.location = "../../frontend/html/admin.php";
          </script>';
}

$conexion->close();
?>