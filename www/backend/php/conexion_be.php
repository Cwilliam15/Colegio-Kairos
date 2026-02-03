<?php
/*
=========================================
 CONEXIÓN PRODUCCIÓN (HOSTING)
=========================================
$conexion = mysqli_connect(
    "sdb-86.hosting.stackcp.net",
    "asistencia_alumno-3531303009b2",
    "admin123",
    "asistencia_alumno-3531303009b2"
);

// Verificar conexión en producción
if (!$conexion) {
    die("Error de conexión (producción): " . mysqli_connect_error());
}
*/

// =========================================
// CONEXIÓN LOCAL (localhost)
// =========================================
$conexion = mysqli_connect("db", "root", "root", "asistencia_alumno");

// Verificar conexión local
if (!$conexion) {
    die("Error de conexión (local): " . mysqli_connect_error());
}

$conexion->set_charset("utf8");
?>