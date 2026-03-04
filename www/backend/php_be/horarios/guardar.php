<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once __DIR__ . '/../conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data['Id_Jornada']) ||
    empty($data['Fecha']) ||
    empty($data['Hora_Entrada']) ||
    empty($data['Hora_Salida'])
) {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit;
}

$stmt = $conexion->prepare("
    INSERT INTO horarios 
    (Id_Jornada, Fecha, Hora_Entrada, Hora_Salida, Observaciones, Color)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssss",
    $data['Id_Jornada'],
    $data['Fecha'],
    $data['Hora_Entrada'],
    $data['Hora_Salida'],
    $data['Observaciones'],
    $data['Color']
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Guardado correctamente"]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
