<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['jornada'], $data['dias'], $data['entrada'], $data['salida'])) {
    echo json_encode([
        "success" => false,
        "message" => "Datos incompletos"
    ]);
    exit;
}

$jornada = $data['jornada'];
$dias = $data['dias'];
$entrada = $data['entrada'];
$salida = $data['salida'];
$obs = $data['obs'] ?? null;

$stmt = $conexion->prepare("
    INSERT INTO horarios 
    (Id_Jornada, Fecha, Hora_Entrada, Hora_Salida, Observaciones)
    VALUES (?, ?, ?, ?, ?)
");

$errores = [];

foreach ($dias as $fecha) {
    $stmt->bind_param("sssss", $jornada, $fecha, $entrada, $salida, $obs);
    
    if (!$stmt->execute()) {
        $errores[] = $fecha;
    }
}

if (count($errores) > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Algunas fechas no se guardaron (posible duplicado)",
        "errores" => $errores
    ]);
} else {
    echo json_encode([
        "success" => true,
        "message" => "Horarios guardados correctamente"
    ]);
}
