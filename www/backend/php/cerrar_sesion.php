<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'conexion_be.php';
require_once 'logger.php';  // Ajusta la ruta si es necesario

// Si hay un usuario logueado, registramos el cierre de sesión
if (isset($_SESSION['usuario_id'])) {
    registrarLog("Cierre de sesión");
}

// Limpiamos todas las variables de sesión y destruimos la sesión
session_unset();
session_destroy();

// Redirigimos al formulario de login
header("Location: ../../index.php");
exit();
?>
