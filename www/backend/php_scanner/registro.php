<?php
// 0) Mostrar errores para depurar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1) Cabecera JSON
header('Content-Type: application/json; charset=utf-8');

// 2) Leer raw input y validar JSON de entrada
$raw = file_get_contents('php://input');
error_log("RAW INPUT registro.php → $raw");
$data = json_decode($raw, true);
error_log("PARSED DATA registro.php → " . print_r($data, true));

if (
    !isset($data['codigo'], $data['lector'])
    || !preg_match('/^[A-Za-z]\d{3}[A-Za-z]{3}$/', $data['codigo'])
    || !is_numeric($data['lector'])
) {
    echo json_encode(
      ['success' => false, 'message' => 'Datos inválidos.'],
      JSON_UNESCAPED_UNICODE
    );
    exit;
}
$codigo   = $data['codigo'];
$idLector = (int)$data['lector'];

// 3) Fecha, hora y día de la semana
$tz        = new DateTimeZone('America/Guatemala');
$fh        = new DateTime('now', $tz);
$fecha     = $fh->format('Y-m-d');
$hora      = $fh->format('H:i:s');
$diaSemana = (int)$fh->format('N'); // 1 = Lunes … 7 = Domingo
if ($diaSemana < 1 || $diaSemana > 7) {
    echo json_encode(
      ['success' => false, 'message' => 'Día inválido.'],
      JSON_UNESCAPED_UNICODE
    );
    exit;
}

// 4) Conexión a la base de datos
include __DIR__ . '/conexion.php';
if (!isset($conexion) || $conexion->connect_errno) {
    echo json_encode(
      ['success' => false, 'message' => 'Error BD: ' . $conexion->connect_error],
      JSON_UNESCAPED_UNICODE
    );
    exit;
}
// **IMPORTANTE**: forzamos la codificación a UTF-8 para que json_encode no falle
if (!$conexion->set_charset('utf8mb4')) {
    error_log("Error al establecer charset utf8mb4: " . $conexion->error);
}

// 5) Obtener Id_Detalle y Tipo_Jornada (y nombre del alumno)
$stmt = $conexion->prepare("
    SELECT 
      DA.Id_Detalle, 
      J.Tipo_Jornada,
      A.Nombres_Alumno,
      A.Apellido1_Alumno
    FROM registro_alumnos RA
    JOIN detalle_alumnos DA
      ON DA.Id_Registro_Alumno = RA.Id_Registro_Alumno
    JOIN jornadas J
      ON RA.Id_Jornada = J.Id_Jornada
    JOIN alumnos A
      ON RA.Id_Alumno = A.Id_Alumno
   WHERE RA.Id_Alumno = ?
   LIMIT 1
");
if (!$stmt) {
    echo json_encode(
      ['success' => false, 'message' => 'SQL detalle: ' . $conexion->error],
      JSON_UNESCAPED_UNICODE
    );
    exit;
}
$stmt->bind_param('s', $codigo);
$stmt->execute();
$stmt->bind_result($idDetalle, $tipoJornada, $nombre, $apellido1);
if (!$stmt->fetch()) {
    echo json_encode(
      ['success' => false, 'message' => "Alumno $codigo no encontrado."],
      JSON_UNESCAPED_UNICODE
    );
    exit;
}
$stmt->close();
$nombreCompleto = trim("$nombre $apellido1");  // <-- aquí está tu nombre completo

// 6) Definir umbrales según jornada
$tipo = mb_strtolower(trim($tipoJornada), 'UTF-8');
switch ($tipo) {
    case 'matutina':
        if ($diaSemana > 5) {
            echo json_encode(
              ['success' => false, 'message' => 'Jornada Matutina sólo Lunes a Viernes.'],
              JSON_UNESCAPED_UNICODE
            );
            exit;
        }
        $corteIn = '07:00:00';
        break;
    case 'vespertina':
        if ($diaSemana > 5) {
            echo json_encode(
              ['success' => false, 'message' => 'Jornada Vespertina sólo Lunes a Viernes.'],
              JSON_UNESCAPED_UNICODE
            );
            exit;
        }
        $corteIn = '13:00:00';
        break;
    case 'fin de semana':
        if ($diaSemana !== 6) {
            echo json_encode(
              ['success' => false, 'message' => 'Jornada Fin de semana sólo sábados.'],
              JSON_UNESCAPED_UNICODE
            );
            exit;
        }
        $corteIn = '07:00:00';
        break;
    default:
        echo json_encode(
          ['success' => false, 'message' => "Jornada desconocida: $tipoJornada"],
          JSON_UNESCAPED_UNICODE
        );
        exit;
}

// 7) Comprobar si ya hay registro hoy
$stmt = $conexion->prepare("
    SELECT Id_Asistencia, Hora_Entrada, Hora_Salida
      FROM asistencias
     WHERE Id_Detalle     = ?
       AND Fecha_Registro = ?
");
if (!$stmt) {
    echo json_encode(
      ['success' => false, 'message' => 'SQL sel asist: ' . $conexion->error],
      JSON_UNESCAPED_UNICODE
    );
    exit;
}
$stmt->bind_param('ss', $idDetalle, $fecha);
$stmt->execute();
$stmt->bind_result($idAsis, $horaEnt, $horaSal);
$exists = $stmt->fetch();
$stmt->close();

if (!$exists) {
    // 7a) Registrar entrada
    $status = ($hora <= $corteIn) ? 'presente' : 'tarde';
    $stmt   = $conexion->prepare("
        INSERT INTO asistencias
          (Id_Detalle, Fecha_Registro, Hora_Entrada, Registro_Asistencia, Id_Lector)
        VALUES (?,?,?,?,?)
    ");
    if (!$stmt) {
        echo json_encode(
          ['success' => false, 'message' => 'SQL insert asist: ' . $conexion->error],
          JSON_UNESCAPED_UNICODE
        );
        exit;
    }
    $stmt->bind_param('ssssi', $idDetalle, $fecha, $hora, $status, $idLector);
    if (!$stmt->execute()) {
        echo json_encode(
          ['success' => false, 'message' => 'Error al guardar entrada: ' . $stmt->error],
          JSON_UNESCAPED_UNICODE
        );
        exit;
    }
    $stmt->close();

    // Aquí incluimos el nombre completo en el mensaje
    $msg = ($status === 'presente')
        ? "¡Bienvenido $nombreCompleto! Su asistencia ha sido registrada a las $hora."
        : "¡Bienvenido $nombreCompleto! Se ha registrado su llegada tarde a las $hora, Espere instrucciones de la Orientadora Academica.";

} else {
    // 7b) Registrar salida
    if ($horaSal !== null) {
        $msg = "Su salida ya ha sido registrada a las $horaSal. No es necesario registrarla de nuevo";
    } else {
        $entradaDT = DateTime::createFromFormat('H:i:s', $horaEnt, $tz);
        $interval = $entradaDT->diff($fh);
        $mins = $interval->h * 60 + $interval->i;

        if ($mins < 30) {
            $msg = "Aun no tiene permitido salir del establecimiento, espere su hora de salida.";
        } else {
            $stmt = $conexion->prepare("
                UPDATE asistencias
                   SET Hora_Salida = ?
                 WHERE Id_Asistencia = ?
            ");
            if (!$stmt) {
                echo json_encode(
                  ['success' => false, 'message' => 'Error SQL al actualizar salida: ' . $conexion->error],
                  JSON_UNESCAPED_UNICODE
                );
                exit;
            }
            $stmt->bind_param('si', $hora, $idAsis);
            $stmt->execute();
            $stmt->close();
            $msg = "Su salida ha sido registrada $nombreCompleto a las $hora, ¡Feliz regreso a casa!";
        }
    }
}

// 8) Devolvemos **solo** el JSON limpio
$response = ['success' => true, 'message' => $msg];
$json = json_encode($response, JSON_UNESCAPED_UNICODE);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON ERROR: " . json_last_error_msg());
}
if (ob_get_length()) {
    ob_clean();
}
echo $json;
exit;
?>