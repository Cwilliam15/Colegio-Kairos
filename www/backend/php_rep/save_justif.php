<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/../php_be/conexion.php';
date_default_timezone_set('America/Guatemala');

// Leer JSON
$data       = json_decode(file_get_contents('php://input'), true);
$idAsis     = $data['idAsis']    ?? null;
$idDetalle  = $data['idDetalle'] ?? null;
$justif     = trim($data['justif'] ?? '');

if (!$idDetalle || $justif === '') {
    echo json_encode(['success'=>false,'message'=>'Datos inválidos.']);
    exit;
}

// 1) Si ya existe Id_Asistencia -> comprobaremos Justificacion previa
if ($idAsis) {
    // Buscamos la justificación actual
    $q = $conexion->prepare("
      SELECT Justificacion 
        FROM asistencias 
       WHERE Id_Asistencia = ?
      LIMIT 1
    ");
    $q->bind_param('i', $idAsis);
    $q->execute();
    $q->bind_result($prev);
    $q->fetch();
    $q->close();

    if ($prev !== null && trim($prev) !== '') {
        echo json_encode([
          'success' => false,
          'message' => 'Ya existe una justificación para este alumno.'
        ]);
        exit;
    }

    // Si no había justificación, hacemos UPDATE
    $stmt = $conexion->prepare("
      UPDATE asistencias
         SET Justificacion = ?
       WHERE Id_Asistencia = ?
    ");
    $stmt->bind_param('si', $justif, $idAsis);
    $ok = $stmt->execute();

// 2) Si no existe Id_Asistencia -> comprobamos por Id_Detalle + Fecha
} else {
    $fecha = date('Y-m-d');
    // ¿Ya hay un registro con justificación?
    $q = $conexion->prepare("
      SELECT COUNT(*) 
        FROM asistencias 
       WHERE Id_Detalle      = ?
         AND Fecha_Registro  = ?
         AND Justificacion <> ''
    ");
    $q->bind_param('ss', $idDetalle, $fecha);
    $q->execute();
    $q->bind_result($cnt);
    $q->fetch();
    $q->close();

    if ($cnt > 0) {
        echo json_encode([
          'success' => false,
          'message' => 'Ya existe una justificación para este alumno.'
        ]);
        exit;
    }

    // Insertamos un nuevo registro de ausencia + justificación
    $stmt = $conexion->prepare("
      INSERT INTO asistencias
        (Id_Detalle, Fecha_Registro, Hora_Entrada, Registro_Asistencia, Id_Lector, Justificacion)
      VALUES (?, ?, '00:00:00', 0, 1, ?)
    ");
    $stmt->bind_param('sss', $idDetalle, $fecha, $justif);
    $ok = $stmt->execute();
}

if (!$ok) {
    echo json_encode(['success'=>false,'message'=>$conexion->error]);
} else {
    echo json_encode(['success'=>true,'message'=>'Justificación guardada.']);
}

$conexion->close();
?>