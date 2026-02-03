<?php
if (!isset($_SESSION['rol'])) {
    header('Location: ../html/menu.php');
    exit;
}

include __DIR__ . '/../php_be/conexion.php';
date_default_timezone_set('America/Guatemala');

// ───── 1) Detectar jornada automática ───────────────────────────────────────
$ahora     = new DateTime();
$diaSemana = (int)$ahora->format('N');   // 1=Lun … 7=Dom
$horaMin   = (int)$ahora->format('Hi');  // ej. “12:58”→1258

if ($diaSemana >= 1 && $diaSemana <= 5) {
    // Lunes–Viernes
    $tipoAuto = ($horaMin < 1300) ? 'matutina' : 'vespertina';
} elseif ($diaSemana === 6 && $horaMin >= 1 && $horaMin <= 2300) {
    // Sábado
    $tipoAuto = 'fin de semana';
} else {
    $tipoAuto = '';  // domingo u horas fuera de rango
}

// Mapear nombre → Id_Jornada
$jornadaAuto = '';
if ($tipoAuto) {
    $p = $conexion->prepare(
      "SELECT Id_Jornada 
         FROM jornadas 
        WHERE LOWER(Tipo_Jornada)=?"
    );
    $p->bind_param('s', $tipoAuto);
    $p->execute();
    $p->bind_result($jornadaAuto);
    $p->fetch();
    $p->close();
}

// ───── 2) Leer filtros, con fallback a la jornada automática ────────────────
$fechaSel   = $_GET['fecha']    ?? date('Y-m-d');
$gradoSel   = $_GET['grado']    ?? '';
$seccionSel = $_GET['seccion']  ?? '';
$jornadaSel = $_GET['jornada']  ?? $jornadaAuto;

// ───── 3) WHERE dinámico para contar presentes ──────────────────────────────
$whereA   = ["A.Fecha_Registro = ?"];
$paramsA  = [$fechaSel];
$typesA   = "s";

if ($gradoSel) {
    $whereA[]  = "RA.Id_Grado   = ?";
    $paramsA[] = $gradoSel;
    $typesA   .= "s";
}
if ($seccionSel) {
    $whereA[]  = "RA.Id_Seccion = ?";
    $paramsA[] = $seccionSel;
    $typesA   .= "s";
}
if ($jornadaSel) {
    $whereA[]  = "RA.Id_Jornada = ?";
    $paramsA[] = $jornadaSel;
    $typesA   .= "s";
}
$whereAsistencias = implode(' AND ', $whereA);

// ───── 4) Total de alumnos (con filtros de grado/sección/jornada) ──────────
$whereRA = ["1=1"];
if ($gradoSel)   $whereRA[] = "RA.Id_Grado   = '" . $conexion->real_escape_string($gradoSel)   . "'";
if ($seccionSel) $whereRA[] = "RA.Id_Seccion = '" . $conexion->real_escape_string($seccionSel) . "'";
if ($jornadaSel) $whereRA[] = "RA.Id_Jornada = '" . $conexion->real_escape_string($jornadaSel) . "'";
$whereTotal = implode(' AND ', $whereRA);

$sqlTotal = "SELECT COUNT(*) FROM registro_alumnos RA WHERE $whereTotal";
$total    = (int) $conexion->query($sqlTotal)->fetch_row()[0];

// ───── 5) Contar presentes ───────────────────────────────────────────────────
$stmt = $conexion->prepare("
    SELECT COUNT(*)
      FROM asistencias A
      JOIN detalle_alumnos DA ON A.Id_Detalle           = DA.Id_Detalle
      JOIN registro_alumnos RA ON DA.Id_Registro_Alumno = RA.Id_Registro_Alumno
     WHERE $whereAsistencias
");
if (!$stmt) {
    die("Error al preparar presentes: " . $conexion->error);
}
$stmt->bind_param($typesA, ...$paramsA);
$stmt->execute();
$stmt->bind_result($presentes);
$stmt->fetch();
$stmt->close();

$ausentes   = $total - $presentes;
$porcentaje = $total ? round($presentes / $total * 100, 1) : 0;

// ───── 6) WHERE dinámico para listar ausentes ───────────────────────────────
$where2   = ["1=1"];
$params2  = [];
$types2   = "";

if ($gradoSel) {
    $where2[]  = "RA.Id_Grado   = ?";
    $params2[] = $gradoSel;
    $types2   .= "s";
}
if ($seccionSel) {
    $where2[]  = "RA.Id_Seccion = ?";
    $params2[] = $seccionSel;
    $types2   .= "s";
}
if ($jornadaSel) {
    $where2[]  = "RA.Id_Jornada = ?";
    $params2[] = $jornadaSel;
    $types2   .= "s";
}
$whereAusentes = implode(' AND ', $where2);

// ───── 7) Listado de ausentes (con Justificacion) ──────────────────────────
$sqlAusentes = "
  SELECT
    AL.Id_Alumno,
    A.Id_Asistencia,
    AL.Id_Alumno,
    DA.Id_Detalle,
    CONCAT(AL.Nombres_Alumno,' ',
           AL.Apellido1_Alumno,' ',
           AL.Apellido2_Alumno) AS Alumno,
    J.Tipo_Jornada   AS Jornada,
    G.Nombre_Grado   AS Grado,
    S.Nombre_Seccion AS Seccion,
    DA.Nombres_Encargado,
    DA.Apellido1_Encargado,
    DA.Apellido2_Encargado,
    DA.Telefono_Encargado,
    A.Justificacion
  FROM registro_alumnos RA
  JOIN alumnos         AL ON RA.Id_Alumno          = AL.Id_Alumno
  JOIN detalle_alumnos DA ON RA.Id_Registro_Alumno = DA.Id_Registro_Alumno
  JOIN jornadas        J  ON RA.Id_Jornada         = J.Id_Jornada
  JOIN grados          G  ON RA.Id_Grado           = G.Id_Grado
  LEFT JOIN secciones  S  ON RA.Id_Seccion         = S.Id_Seccion
  LEFT JOIN asistencias A  
    ON A.Id_Detalle      = DA.Id_Detalle
   AND A.Fecha_Registro = ?
  WHERE A.Id_Asistencia IS NULL
    AND $whereAusentes
  ORDER BY AL.Apellido1_Alumno, AL.Nombres_Alumno
";

$stmt2 = $conexion->prepare($sqlAusentes);
if (!$stmt2) {
    die("Error al preparar ausentes: " . $conexion->error);
}

$bindTypes2  = 's' . $types2;            
$bindParams2 = array_merge([$fechaSel], $params2);

$stmt2->bind_param($bindTypes2, ...$bindParams2);
$stmt2->execute();
$res2 = $stmt2->get_result();
$stmt2->close();

// ───── 8) Listas para filtros ───────────────────────────────────────────────
$gradosList    = $conexion->query("SELECT Id_Grado, Nombre_Grado    FROM grados    ORDER BY Nombre_Grado");
$seccionesList = $conexion->query("SELECT Id_Seccion, Nombre_Seccion FROM secciones ORDER BY Nombre_Seccion");
$jornadasList  = $conexion->query("SELECT Id_Jornada, Tipo_Jornada  FROM jornadas  ORDER BY Tipo_Jornada");

// Cerrar conexión
$conexion->close();
?>