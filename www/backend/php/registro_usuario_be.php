<?php
session_start();
include 'conexion_be.php';
require_once 'logger.php';

// 1) Recibir y sanitizar entradas
$nombre_completo = trim($_POST['nombre_completo']);
$correo          = trim($_POST['correo']);
$rol             = trim($_POST['rol']);
$usuario         = trim($_POST['usuario']);
$contrasena      = hash('sha512', $_POST['contrasena']);

// 2) Verificar si ya existe el correo
$verificar_correo = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
$verificar_correo->bind_param("s", $correo);
$verificar_correo->execute();
$verificar_correo->store_result();

if ($verificar_correo->num_rows > 0) {
    header("Location: ../../frontend/html/admin.php?error=Correo+ya+registrado");
    exit;
}
$verificar_correo->close();

// 3) Verificar si ya existe el usuario
$verificar_usuario = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$verificar_usuario->bind_param("s", $usuario);
$verificar_usuario->execute();
$verificar_usuario->store_result();

if ($verificar_usuario->num_rows > 0) {
    header("Location: ../../frontend/html/admin.php?error=Usuario+ya+en+uso");
    exit;
}
$verificar_usuario->close();

// 4) Verificar rol admin único
if (strtolower($rol) === 'admin' || strtolower($rol) === 'administrador') {
    $verificar_rol = $conexion->prepare("SELECT id FROM usuarios WHERE LOWER(rol) = 'admin' OR LOWER(rol) = 'administrador'");
    $verificar_rol->execute();
    $verificar_rol->store_result();

    if ($verificar_rol->num_rows > 0) {
        header("Location: ../../frontend/html/admin.php?error=Ya+existe+un+Administrador+General");
        exit;
    }
    $verificar_rol->close();
}

// 5) Insertar usuario
$stmt = $conexion->prepare("
    INSERT INTO usuarios 
      (nombre_completo, correo, rol, usuario, contrasena) 
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("sssss", $nombre_completo, $correo, $rol, $usuario, $contrasena);

if ($stmt->execute()) {
    registrarLog("Creó usuario '{$usuario}'");
    header("Location: ../../frontend/html/admin.php?success=Usuario+agregado+correctamente");
} else {
    header("Location: ../../frontend/html/admin.php?error=Error+al+guardar+el+usuario");
}

$stmt->close();
$conexion->close();
?>