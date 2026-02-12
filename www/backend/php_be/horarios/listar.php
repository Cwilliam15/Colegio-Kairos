<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

if (!isset($_GET['jornada'])) {
    echo json_encode([]);
    exit;
}

$jornada = $_GET['jornada'];

$stmt = $conexion->prepare("
    SELECT Id_Horario, Fecha, Hora_Entrada, Hora_Salida, Observaciones
    FROM horarios
    WHERE Id_Jornada = ?
    ORDER BY Fecha ASC
");

$stmt->bind_param("s", $jornada);
$stmt->execute();
$result = $stmt->get_result();

$horarios = [];

while ($row = $result->fetch_assoc()) {
    $horarios[] = $row;
}

echo json_encode($horarios);
