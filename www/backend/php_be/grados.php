<?php
session_start();
include 'conexion.php';
include 'generarID.php';

if (!$conexion) {
    die("❌ Error al conectar con la base de datos: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre_grado = trim($_POST['nombre_grado']);

    try {
        // Genera el nuevo ID con prefijo "G-"
        $id_grado = generarID($conexion, "grados", "id_grado", "G-");

        // Verifica si ya existe ese ID o el mismo nombre
        $stmt = $conexion->prepare(
            "SELECT 1
               FROM grados
              WHERE id_grado = ? OR nombre_grado = ?
              LIMIT 1"
        );
        $stmt->bind_param("ss", $id_grado, $nombre_grado);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['mensaje'] = "⚠️ Ya existe este grado o ID.";
            $_SESSION['tipo']    = "error";
        } else {
            // Inicia transacción
            $conexion->begin_transaction();

            $ins = $conexion->prepare(
                "INSERT INTO grados (id_grado, nombre_grado) VALUES (?, ?)"
            );
            $ins->bind_param("ss", $id_grado, $nombre_grado);

            if (! $ins->execute()) {
                throw new Exception("Error al guardar: " . $ins->error);
            }

            $conexion->commit();
            $_SESSION['mensaje'] = "✅ Grado guardado correctamente (ID: $id_grado).";
            $_SESSION['tipo']    = "exito";
        }

    } catch (Exception $e) {
        // Revierte si algo falla
        if ($conexion->errno) {
            $conexion->rollback();
        }
        $_SESSION['mensaje'] = "❌ " . $e->getMessage();
        $_SESSION['tipo']    = "error";
    }

    // Redirige de vuelta al formulario
    header("Location: ../../frontend/html/ingreso_JSG.php");
    exit;
}
?>
