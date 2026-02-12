<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode([
        "success" => false,
        "message" => "ID no recibido"
    ]);
    exit;
}

$id = $data['id'];

$stmt = $conexion->prepare("
    DELETE FROM horarios WHERE Id_Horario = ?
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Horario eliminado"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error al eliminar"
    ]);
}
