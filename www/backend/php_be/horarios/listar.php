<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

if (!isset($_GET['jornada'])) {
    echo json_encode([]);
    exit;
}

$jornada = $_GET['jornada'];

$stmt = $conexion->prepare("
    SELECT 
        Id_Horario,
        Fecha,
        Hora_Entrada,
        Hora_Salida,
        Observaciones,
        Color
    FROM horarios
    WHERE Id_Jornada = ?
    ORDER BY Hora_Entrada ASC
");

$stmt->bind_param("s", $jornada);
$stmt->execute();
$result = $stmt->get_result();

$horarios = [];

while ($row = $result->fetch_assoc()) {
    $horarios[] = [
        "id" => $row["Id_Horario"],
        "dias" => $row["Fecha"],
        "entrada" => substr($row["Hora_Entrada"], 0, 5),
        "salida" => substr($row["Hora_Salida"], 0, 5),
        "observaciones" => $row["Observaciones"],
        "color" => $row["Color"] ?? "#3498db"
    ];
}

echo json_encode($horarios);
