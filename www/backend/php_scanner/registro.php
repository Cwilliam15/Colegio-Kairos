<?php
declare(strict_types=1);

/* =========================
   CONFIGURACIÓN INICIAL
========================= */

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

/* =========================
   VALIDAR INPUT JSON
========================= */

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (
    !isset($data['codigo'], $data['lector']) ||
    !preg_match('/^[A-Za-z]\d{3}[A-Za-z]{3}$/', $data['codigo']) ||
    !is_numeric($data['lector'])
) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$codigo   = strtoupper(trim($data['codigo']));
$idLector = (int)$data['lector'];

/* =========================
   FECHA Y HORA ACTUAL
========================= */

$tz    = new DateTimeZone('America/Guatemala');
$now   = new DateTime('now', $tz);
$fecha = $now->format('Y-m-d');
$hora  = $now->format('H:i:s');

/* =========================
   CONEXIÓN BD
========================= */

require __DIR__ . '/conexion.php';

if (!isset($conexion) || $conexion->connect_errno) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$conexion->set_charset('utf8mb4');

/* =========================
   OBTENER DATOS DEL ALUMNO
========================= */

$stmt = $conexion->prepare("
    SELECT 
        DA.Id_Detalle,
        RA.Id_Jornada,
        A.Nombres_Alumno,
        A.Apellido1_Alumno
    FROM registro_alumnos RA
    JOIN detalle_alumnos DA 
        ON DA.Id_Registro_Alumno = RA.Id_Registro_Alumno
    JOIN alumnos A 
        ON RA.Id_Alumno = A.Id_Alumno
    WHERE RA.Id_Alumno = ?
    LIMIT 1
");

if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al consultar alumno.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param('s', $codigo);
$stmt->execute();
$stmt->bind_result($idDetalle, $idJornada, $nombre, $apellido1);

if (!$stmt->fetch()) {
    echo json_encode([
        'success' => false,
        'message' => "Alumno no encontrado."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->close();

$nombreCompleto = trim("$nombre $apellido1");

/* =========================
   OBTENER HORARIO DEL DÍA
========================= */

$stmt = $conexion->prepare("
    SELECT Hora_Entrada, Hora_Salida
    FROM horarios
    WHERE Id_Jornada = ?
      AND Fecha = ?
      AND Estado = 1
    LIMIT 1
");

if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al consultar horario.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param('ss', $idJornada, $fecha);
$stmt->execute();
$stmt->bind_result($horaEntradaPermitida, $horaSalidaPermitida);

if (!$stmt->fetch()) {
    echo json_encode([
        'success' => false,
        'message' => 'No hay clases programadas para hoy.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->close();

/* =========================
   VERIFICAR SI YA EXISTE REGISTRO HOY
========================= */

$stmt = $conexion->prepare("
    SELECT Id_Asistencia, Hora_Entrada, Hora_Salida
    FROM asistencias
    WHERE Id_Detalle = ?
      AND Fecha_Registro = ?
");

$stmt->bind_param('ss', $idDetalle, $fecha);
$stmt->execute();
$stmt->bind_result($idAsistencia, $horaEntradaBD, $horaSalidaBD);
$existeRegistro = $stmt->fetch();
$stmt->close();

/* =========================
   REGISTRAR ENTRADA
========================= */

if (!$existeRegistro) {

    $estado = ($hora <= $horaEntradaPermitida) ? 1 : 0; // 1 = puntual, 0 = tarde

    $stmt = $conexion->prepare("
        INSERT INTO asistencias
        (Id_Detalle, Fecha_Registro, Hora_Entrada, Registro_Asistencia, Id_Lector)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param('sssii', $idDetalle, $fecha, $hora, $estado, $idLector);

    if (!$stmt->execute()) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al registrar entrada.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt->close();

    $mensaje = ($estado === 1)
        ? "¡Bienvenido $nombreCompleto! Entrada registrada a las $hora."
        : "¡Bienvenido $nombreCompleto! Llegada tarde registrada a las $hora.";

}
/* =========================
   REGISTRAR SALIDA
========================= */
else {

    if ($horaSalidaBD !== null) {
        $mensaje = "Su salida ya fue registrada a las $horaSalidaBD.";
    } 
    else {

        if ($hora < $horaSalidaPermitida) {

            $mensaje = "Aún no es hora de salida.";

        } else {

            $stmt = $conexion->prepare("
                UPDATE asistencias
                SET Hora_Salida = ?
                WHERE Id_Asistencia = ?
            ");

            $stmt->bind_param('si', $hora, $idAsistencia);
            $stmt->execute();
            $stmt->close();

            $mensaje = "Salida registrada $nombreCompleto a las $hora. ¡Feliz regreso!";
        }
    }
}

/* =========================
   RESPUESTA FINAL
========================= */

echo json_encode([
    'success' => true,
    'message' => $mensaje
], JSON_UNESCAPED_UNICODE);

exit;
