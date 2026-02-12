<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

$carnet = $data['carnet'] ?? null;

if (!$carnet) {
    echo json_encode(["estado" => "ERROR"]);
    exit;
}

/* 1️⃣ Obtener alumno */
$stmt = $conexion->prepare("
    SELECT Id_Alumno, Id_Jornada 
    FROM alumnos 
    WHERE Carnet = ?
");

$stmt->bind_param("s", $carnet);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["estado" => "NO_EXISTE"]);
    exit;
}

$alumno = $result->fetch_assoc();
$jornada = $alumno['Id_Jornada'];

/* 2️⃣ Buscar horario del día */
$fechaHoy = date("Y-m-d");
$horaActual = date("H:i:s");

$stmt2 = $conexion->prepare("
    SELECT Hora_Entrada, Hora_Salida
    FROM horarios
    WHERE Id_Jornada = ?
    AND Fecha = ?
");

$stmt2->bind_param("ss", $jornada, $fechaHoy);
$stmt2->execute();
$resHorario = $stmt2->get_result();

if ($resHorario->num_rows == 0) {
    echo json_encode(["estado" => "SIN_HORARIO"]);
    exit;
}

$horario = $resHorario->fetch_assoc();
$horaEntrada = $horario['Hora_Entrada'];
$horaSalida = $horario['Hora_Salida'];

/* 3️⃣ Validar */

if ($horaActual <= $horaEntrada) {
    echo json_encode(["estado" => "A_TIEMPO"]);
}

elseif ($horaActual > $horaEntrada && $horaActual <= $horaSalida) {
    echo json_encode(["estado" => "TARDE"]);
}

else {
    echo json_encode(["estado" => "FUERA"]);
}
