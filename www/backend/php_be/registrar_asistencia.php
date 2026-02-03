<?php
include('../../backend/php_be/conexion.php'); // Asegúrate de la ruta correcta

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cui'])) {
    $cui = trim($_POST['cui']);
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');

    // 1. Buscar el alumno en registro_alumnos
    $buscar = $conexion->prepare("SELECT Id_Registro_Alumno FROM registro_alumnos WHERE Id_Alumno = ?");
    $buscar->bind_param("s", $cui);
    $buscar->execute();
    $resultado = $buscar->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $idRegistroAlumno = $fila['Id_Registro_Alumno'];

        // 2. Insertar la asistencia
        $insertar = $conexion->prepare("INSERT INTO asistencias (Id_Registro_Alumno, Fecha_Registro, Hora_Entrada, Registro_Asistencia, Id_Lector)
                                        VALUES (?, ?, ?, 1, 1)");
        $insertar->bind_param("sss", $idRegistroAlumno, $fecha, $hora);

        if ($insertar->execute()) {
            echo "✅ Asistencia registrada correctamente.";
        } else {
            echo "❌ Error al registrar asistencia: " . $insertar->error;
        }

        $insertar->close();
    } else {
        echo "❌ CUI no encontrado en el sistema.";
    }

    $buscar->close();
    $conexion->close();
} else {
    echo "❌ Solicitud inválida.";
}
?>