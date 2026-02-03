<?php
// logger.php
require_once 'conexion_be.php';   // sólo la conexión, no la sesión

function registrarLog(string $actividad): void {
    // Si alguien olvidó arrancar la sesión en el script principal, 
    // la arrancamos aquí de forma segura:
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    global $conexion;

    if (empty($_SESSION['usuario_id'])) {
        return;  // nada que loggear si no hay usuario en sesión
    }

    $usuario_id = $_SESSION['usuario_id'];
    $ip         = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    $stmt = $conexion->prepare("INSERT INTO logs_actividades (usuario_id, actividad, ip_usuario) VALUES (?, ?, ?)");
    if (! $stmt) {
        error_log("Logger error: " . $conexion->error);
        return;
    }

    $stmt->bind_param("iss", $usuario_id, $actividad, $ip);
    $stmt->execute();
    $stmt->close();
}
