<?php
ob_start();
session_start();
include 'conexion.php';
include 'generarID.php';

if (!$conexion) {
    die("❌ Error al conectar con la base de datos: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1) Recoge y limpia
    $nombre_seccion = trim($_POST['nombre_seccion']);

    // 2) Genera ID con prefijo "S-"
    $id_seccion = generarID($conexion, "secciones", "id_seccion", "S-", true);
    if (! $id_seccion) {
        $_SESSION['mensaje'] = "❌ No se pudo generar el ID de la sección.";
        $_SESSION['tipo']    = "error";
        header("Location: /frontend/html/ingreso_JSG.php");
        exit;
    }

    // 3) Verifica duplicados (ID o nombre)
    $verif = $conexion->prepare(
        "SELECT 1 
           FROM secciones 
          WHERE id_seccion   = ? 
             OR nombre_seccion = ? 
          LIMIT 1"
    );
    $verif->bind_param("ss", $id_seccion, $nombre_seccion);
    $verif->execute();
    $verif->store_result();

    if ($verif->num_rows > 0) {
        $_SESSION['mensaje'] = "⚠️ Ya existe esta sección o ID.";
        $_SESSION['tipo']    = "error";
    } else {
        // 4) Inserta dentro de transacción
        $conexion->begin_transaction();
        try {
            $ins = $conexion->prepare(
                "INSERT INTO secciones (id_seccion, nombre_seccion) VALUES (?, ?)"
            );
            $ins->bind_param("ss", $id_seccion, $nombre_seccion);
            if (! $ins->execute()) {
                throw new Exception("Error al insertar: " . $ins->error);
            }
            $conexion->commit();

            $_SESSION['mensaje'] = "✅ Sección insertada (ID: $id_seccion).";
            $_SESSION['tipo']    = "exito";
        } catch (Exception $e) {
            $conexion->rollback();
            $_SESSION['mensaje'] = "❌ " . $e->getMessage();
            $_SESSION['tipo']    = "error";
        }
    }


}
header("Location: https://asistencia.colegiokairos.edu.gt/frontend/html/ingreso_JSG.php");
echo "<script>console.log('Redirigiendo...');</script>";
ob_end_flush();
    exit;
?>