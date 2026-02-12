<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../php_be/conexion.php';

date_default_timezone_set('America/Guatemala');

$fecha = date('Y-m-d');

/* =========================
   1) OBTENER JORNADAS ACTIVAS HOY
========================= */

$stmt = $conexion->prepare("
    SELECT Id_Jornada
    FROM horarios
    WHERE Fecha = ?
      AND Estado = 1
");

$stmt->bind_param('s', $fecha);
$stmt->execute();
$result = $stmt->get_result();

$jornadasActivas = [];

while ($row = $result->fetch_assoc()) {
    $jornadasActivas[] = $row['Id_Jornada'];
}

$stmt->close();

if (empty($jornadasActivas)) {
    echo json_encode(['count' => 0]);
    exit;
}

/* =========================
   2) CONTAR AUSENTES EN ESAS JORNADAS
========================= */

$placeholders = implode(',', array_fill(0, count($jornadasActivas), '?'));

$sql = "
    SELECT COUNT(*) 
    FROM registro_alumnos RA
    JOIN detalle_alumnos DA 
        ON RA.Id_Registro_Alumno = DA.Id_Registro_Alumno
    LEFT JOIN asistencias A
        ON A.Id_Detalle = DA.Id_Detalle
        AND A.Fecha_Registro = ?
    WHERE A.Id_Asistencia IS NULL
      AND RA.Id_Jornada IN ($placeholders)
";

$stmt = $conexion->prepare($sql);

$params = array_merge([$fecha], $jornadasActivas);
$types  = str_repeat('s', count($params));

$stmt->bind_param($types, ...$params);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

$conexion->close();

echo json_encode(['count' => (int)$count]);
exit;