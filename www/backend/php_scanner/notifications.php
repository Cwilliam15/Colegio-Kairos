<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/../php_be/conexion.php';
date_default_timezone_set('America/Guatemala');

// 1) Determinar jornada actual
$ahora     = new DateTime();
$diaSemana = (int)$ahora->format('N');   // 1=Lun … 7=Dom
$horaMin   = (int)$ahora->format('Hi');  // “12:58”→1258

if ($diaSemana >= 1 && $diaSemana <= 5) {
    // Lunes–Viernes
    $tipoAuto = ($horaMin < 1300) ? 'matutina' : 'vespertina';
} elseif ($diaSemana === 6 && $horaMin >= 1 && $horaMin <= 2300) {
    // Sábado
    $tipoAuto = 'fin de semana';
} else {
    // Domingo o fuera de rango → no notificamos
    echo json_encode(['count' => 0]);
    exit;
}

// 2) Obtener el Id_Jornada correspondiente
$stmt = $conexion->prepare("
    SELECT Id_Jornada 
      FROM jornadas 
     WHERE LOWER(Tipo_Jornada) = ?
     LIMIT 1
");
$stmt->bind_param('s', $tipoAuto);
$stmt->execute();
$stmt->bind_result($jornadaId);
if (!$stmt->fetch()) {
    // Si no coincide, devolvemos cero
    echo json_encode(['count' => 0]);
    exit;
}
$stmt->close();

// 3) Contar ausentes HOY **en esa jornada**
$fecha = date('Y-m-d');
$sql = "
  SELECT COUNT(*) 
    FROM registro_alumnos RA
    JOIN detalle_alumnos DA 
      ON RA.Id_Registro_Alumno = DA.Id_Registro_Alumno
    LEFT JOIN asistencias A
      ON A.Id_Detalle      = DA.Id_Detalle
     AND A.Fecha_Registro = ?
   WHERE A.Id_Asistencia IS NULL
     AND RA.Id_Jornada    = ?
";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => $conexion->error]);
    exit;
}
// bind en orden de aparición: fecha y luego jornada
$stmt->bind_param('ss', $fecha, $jornadaId);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

// 4) Cerrar y devolver
$conexion->close();
echo json_encode(['count' => (int)$count]);
?>