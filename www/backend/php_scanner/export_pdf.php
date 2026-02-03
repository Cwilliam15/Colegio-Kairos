<?php
// export_pdf.php

require __DIR__ . '/vendor/autoload.php';
use Dompdf\Dompdf;

ini_set('display_errors',1);
error_reporting(E_ALL);

// 1) Conexión y zona horaria
include __DIR__ . '/../../backend/php_be/conexion.php';
date_default_timezone_set('America/Guatemala');

// 2) Leer filtros desde GET y escapar
$fechaSel   = $_GET['fecha']   ?? date('Y-m-d');
$gradoSel   = $_GET['grado']   ?? '';
$seccionSel = $_GET['seccion'] ?? '';
$jornadaSel = $_GET['jornada'] ?? '';

$safeFecha   = $conexion->real_escape_string($fechaSel);
$safeGrado   = $conexion->real_escape_string($gradoSel);
$safeSeccion = $conexion->real_escape_string($seccionSel);
$safeJornada = $conexion->real_escape_string($jornadaSel);

// 3) Construir los WHERE dinámicos

// 3a) Para métricas de asistencia (contar presentes)
$whereAsis = ["A.Fecha_Registro = '$safeFecha'"];
if ($gradoSel)   $whereAsis[] = "RA.Id_Grado   = '$safeGrado'";
if ($seccionSel) $whereAsis[] = "RA.Id_Seccion = '$safeSeccion'";
if ($jornadaSel) $whereAsis[] = "RA.Id_Jornada = '$safeJornada'";
$whereAsistencias = implode(' AND ', $whereAsis);

// 3b) Para total inscritos (grado, sección, jornada)
$whereTotal = ["1=1"];
if ($gradoSel)   $whereTotal[] = "RA.Id_Grado   = '$safeGrado'";
if ($seccionSel) $whereTotal[] = "RA.Id_Seccion = '$safeSeccion'";
if ($jornadaSel) $whereTotal[] = "RA.Id_Jornada = '$safeJornada'";
$whereTotalClause = implode(' AND ', $whereTotal);

// 4) Calcular métricas

// 4.1 Total inscritos
$sql = "SELECT COUNT(*) FROM registro_alumnos RA WHERE $whereTotalClause";
$total = (int)$conexion->query($sql)->fetch_row()[0];

// 4.2 Presentes
$sql = "
  SELECT COUNT(*) 
    FROM asistencias A
    JOIN detalle_alumnos DA ON A.Id_Detalle            = DA.Id_Detalle
    JOIN registro_alumnos RA ON DA.Id_Registro_Alumno   = RA.Id_Registro_Alumno
   WHERE $whereAsistencias
";
$presentes = (int)$conexion->query($sql)->fetch_row()[0];

// 4.3 Ausentes y porcentaje
$ausentes   = $total - $presentes;
$porcentaje = $total ? round($presentes / $total * 100, 1) : 0;

// 5) Consultar ausentes con LEFT JOIN + datos de encargado
$sql = "
  SELECT
    AL.Id_Alumno,
    CONCAT(AL.Nombres_Alumno,' ',AL.Apellido1_Alumno,' ',AL.Apellido2_Alumno) AS Alumno,
    DA.Nombres_Encargado,
    DA.Apellido1_Encargado,
    DA.Apellido2_Encargado,
    DA.Telefono_Encargado
  FROM registro_alumnos RA
  JOIN alumnos        AL ON RA.Id_Alumno           = AL.Id_Alumno
  JOIN detalle_alumnos DA ON RA.Id_Registro_Alumno = DA.Id_Registro_Alumno
  LEFT JOIN asistencias A
    ON A.Id_Detalle      = DA.Id_Detalle
   AND A.Fecha_Registro = '$safeFecha'
  WHERE A.Id_Asistencia IS NULL
    AND $whereTotalClause
  ORDER BY AL.Apellido1_Alumno, AL.Nombres_Alumno
";
$res2 = $conexion->query($sql);
if (!$res2) {
    die("Error en consulta de ausentes: " . $conexion->error);
}

// 6) Generar HTML con HEREDOC
$html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; font-size: 12px; }
    h1, p { text-align: center; margin: 0; }
    p { margin-top: .5rem; }
    table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    th, td { border: 1px solid #333; padding: 6px; }
    th { background: #eee; }
  </style>
</head>
<body>
  <h1>Reporte de Ausentes – {$fechaSel}</h1>
  <p>
    Total: {$total} |
    Presentes: {$presentes} |
    Ausentes: {$ausentes} |
    % Asistencia: {$porcentaje}%
  </p>
  <table>
    <thead>
      <tr>
        <th>CUI</th>
        <th>Alumno</th>
        <th>Encargado</th>
        <th>Teléfono</th>
      </tr>
    </thead>
    <tbody>
HTML;

while ($f = $res2->fetch_assoc()) {
    $cui       = htmlspecialchars($f['Id_Alumno']);
    $alumno    = htmlspecialchars($f['Alumno']);
    $enc       = htmlspecialchars("{$f['Nombres_Encargado']} {$f['Apellido1_Encargado']} {$f['Apellido2_Encargado']}");
    $telefono  = htmlspecialchars($f['Telefono_Encargado']);

    $html .= <<<ROW
      <tr>
        <td>{$cui}</td>
        <td>{$alumno}</td>
        <td>{$enc}</td>
        <td>{$telefono}</td>
      </tr>
ROW;
}

$html .= <<<HTML
    </tbody>
  </table>
</body>
</html>
HTML;

// 7) Renderizar el PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Reporte_Ausentes_{$fechaSel}.pdf", ["Attachment" => true]);
exit;
?>