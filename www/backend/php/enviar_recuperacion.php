<?php
// enviar_recuperacion.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// 1) Incluye autoload y conexión
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/conexion_be.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2) Obtén y valida el correo
    $correo = trim($_POST['correo'] ?? '');
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensaje'] = "❌ Correo inválido.";
        header('Location: ../../assets/recuperar_contrasena.php');
        exit;
    }

    // 3) Comprueba que ese correo existe
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
    if (!$stmt) {
        die("Error en prepare SELECT: " . $conexion->error);
    }
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $_SESSION['mensaje'] = "❌ El correo no está registrado.";
        $stmt->close();
        $conexion->close();
        header('Location: ../../assets/recuperar_contrasena.php');
        exit;
    }
    $stmt->close();

    // 4) Genera un token y guárdalo
    $token = bin2hex(random_bytes(32));
    $upd = $conexion->prepare(
        "UPDATE usuarios SET token_recuperacion = ? WHERE correo = ?"
    );
    if (!$upd) {
        die("Error en prepare UPDATE: " . $conexion->error);
    }
    $upd->bind_param("ss", $token, $correo);
    $upd->execute();
    $upd->close();

    // 5) Construye el enlace
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host      = $_SERVER['HTTP_HOST'];
    // ajusta si la carpeta principal no es esta
    $carpeta = dirname(dirname(dirname($_SERVER['PHP_SELF'])));
    $enlace    = "{$protocolo}://{$host}{$carpeta}/assets/restablecer_contrasena.php?token="
                 . urlencode($token);

    // 6) Envía el correo con PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug   = 0;                    // 2 para debug en desarrollo
        $mail->Debugoutput = 'html';

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'scanverifywatch@gmail.com';
        $mail->Password   = 'ehge iovp dinu cdxw';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('no-reply@kairos.com', 'Colegio Kairos');
        $mail->addAddress($correo);

        $mail->isHTML(false);
        $mail->Subject = 'Recuperación de Contraseña - Colegio Kairos';
        $mail->Body    = "Hola,\n\nPara restablecer tu contraseña haz clic aquí:\n"
                         . $enlace
                         . "\n\nSi no solicitaste esto, ignora este mensaje.";

        $mail->send();
        $_SESSION['mensaje'] = "✅ Revisa tu correo para restablecer la contraseña.";
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "❌ Error al enviar el correo: {$mail->ErrorInfo}";
    }

    $conexion->close();

    // 7) Redirige de vuelta al formulario
    header('Location: ../../assets/recuperar_contrasena.php');
    exit;
}
?>