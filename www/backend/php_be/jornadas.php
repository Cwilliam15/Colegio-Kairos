<?php
session_start();
include 'conexion.php';
require_once '../php/logger.php';
include 'generarID.php';

if (!$conexion) {
    die("❌ Error al conectar con la base de datos: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1) Recoger y sanear
    $tipo_jornada = trim($_POST['tipo_jornada']);

    // 2) Generar ID con prefijo "J-"
    $id_jornada = generarID($conexion, "jornadas", "id_jornada", "J-", true);
    if (! $id_jornada) {
        $_SESSION['mensaje'] = "❌ No se pudo generar el ID de la jornada.";
        $_SESSION['tipo']    = "error";
        header("Location: ../../frontend/html/ingreso_JSG.php");
        exit;
    }

    // 3) Verificar duplicados (ID o tipo)
    $verif = $conexion->prepare(
        "SELECT 1
           FROM jornadas
          WHERE id_jornada   = ?
             OR tipo_jornada = ?
          LIMIT 1"
    );
    $verif->bind_param("ss", $id_jornada, $tipo_jornada);
    $verif->execute();
    $verif->store_result();

    if ($verif->num_rows > 0) {
        $_SESSION['mensaje'] = "⚠️ Ya existe esta jornada o ID.";
        $_SESSION['tipo']    = "error";
    } else {
        // 4) Insertar dentro de transacción
        $conexion->begin_transaction();
        try {
            $ins = $conexion->prepare(
                "INSERT INTO jornadas (id_jornada, tipo_jornada) VALUES (?, ?)"
            );
            $ins->bind_param("ss", $id_jornada, $tipo_jornada);
            if (! $ins->execute()) {
                throw new Exception("Error al insertar: " . $ins->error);
            }

            // 5) Log y commit
            registrarLog("Se creó jornada con ID {$id_jornada} y tipo '{$tipo_jornada}'");
            $conexion->commit();

            $_SESSION['mensaje'] = "✅ Jornada guardada correctamente (ID: $id_jornada).";
            $_SESSION['tipo']    = "exito";
        } catch (Exception $e) {
            $conexion->rollback();
            $_SESSION['mensaje'] = "❌ " . $e->getMessage();
            $_SESSION['tipo']    = "error";
        }
    }

    // 6) Redirigir para mostrar mensaje
    header("Location: ../../frontend/html/ingreso_JSG.php");
    exit;
}
?>