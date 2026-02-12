<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'], $data['entrada'], $data['salida'])) {
    echo json_encode([
        "success" => false,
        "message" => "Datos incompletos"
    ]);
    exit;
}

$id = $data['id'];
$entrada = $data['entrada'];
$salida = $data['salida'];
$obs = $data['obs'] ?? null;

$stmt = $conexion->prepare("
    UPDATE horarios
    SET Hora_Entrada = ?, 
        Hora_Salida = ?, 
        Observaciones = ?
    WHERE Id_Horario = ?
");

$stmt->bind_param("sssi", $entrada, $salida, $obs, $id);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Horario actualizado"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error al actualizar"
    ]);
}
