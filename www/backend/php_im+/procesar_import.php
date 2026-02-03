<?php
session_start();
// Mostrar todos los errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/../php_be/conexion.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

// ——— Crear carpeta /qrs/ si no existe ———
$qrDir = __DIR__ . '/qrs/';
if (! is_dir($qrDir)) {
    mkdir($qrDir, 0755, true);
}

// ——— Validar subida de archivo ———
if (! isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['mensaje'] = "Error al subir el archivo.";
    $_SESSION['tipo']    = "error";
    header("Location: ../../frontend/html/import_alumnos.php");
    exit;
}

// ——— Leer Excel/CSV ———
try {
    $spreadsheet = IOFactory::load($_FILES['archivo']['tmp_name']);
} catch (\Throwable $e) {
    $_SESSION['mensaje'] = "No se pudo leer el archivo: " . $e->getMessage();
    $_SESSION['tipo']    = "error";
    header("Location: ../../frontend/html/import_alumnos.php");
    exit;
}

$sheet = $spreadsheet->getActiveSheet();
$rows  = $sheet->toArray(null, true, true, true);
unset($rows[1]); // quitar fila de encabezados

// ——— Expresiones regulares de validación ———
$patterns = [
    'id_alumno'           => '/^[A-Z]\d{3}[A-Z]{3}$/',
    'nombres_alumno'      => '/^([A-Za-zÀ-ÿ]+\s?){1,3}$/',
    'apellido1_alumno'    => '/^([A-Za-zÀ-ÿ]+\s?){1,2}$/',
    'apellido2_alumno'    => '/^([A-Za-zÀ-ÿ]+\s?){1,2}$/',
    'genero'              => '/^[MF]$/',
    'telefono_alumno'     => '/^(502|504)?\s?\d{4,8}\-?\d{4}$/',
    'direccion_alumno'    => '/^.{5,100}$/',
    'cui_encargado'       => '/^\d{13}$/',
    'nombres_encargado'   => '/^([A-Za-zÀ-ÿ]+\s?){1,3}$/',
    'apellido1_encargado' => '/^([A-Za-zÀ-ÿ]+\s?){1,2}$/',
    'apellido2_encargado' => '/^([A-Za-zÀ-ÿ]+\s?){0,2}$/',
    'telefono_encargado'  => '/^(502|504)?\s?\d{4,8}\-?\d{4}$/',
    'direccion_encargado' => '/^.{5,100}$/'
];

$insertados = 0;
$errores    = [];

foreach ($rows as $i => $row) {
    // ——— Mapear cada columna A→Q ———
     $data = [
        'id_alumno'           => trim($row['A']),
        'nombres_alumno'      => trim($row['B']),
        'apellido1_alumno'    => trim($row['C']),
        'apellido2_alumno'    => isset($row['D']) && trim($row['D']) !== '' ? trim($row['D']) : null,
        'genero'              => trim($row['E']),
        'telefono_alumno'     => isset($row['F']) && trim($row['F']) !== '' ? trim($row['F']) : null,
        'direccion_alumno'    => trim($row['G']),
        'id_grado'            => trim($row['H']),
        'id_jornada'          => trim($row['I']),
        'id_seccion'          => isset($row['J']) && trim($row['J']) !== '' ? trim($row['J']) : null,
        'cui_encargado'       => trim($row['K']),
        'nombres_encargado'   => trim($row['L']),
        'apellido1_encargado' => trim($row['M']),
        'apellido2_encargado' => isset($row['N']) && trim($row['N']) !== '' ? trim($row['N']) : null,
        'telefono_encargado'  => trim($row['O']),
        'id_parentesco'       => trim($row['P']),
        'direccion_encargado' => trim($row['Q']),
    ];
    // ——— Validar con regex ———
    $bad = [];
    foreach ($patterns as $field => $regex) {
        if (in_array($field, ['apellido2_alumno','apellido2_encargado']) && $data[$field] === '') {
            continue;
        }
        if (! preg_match($regex, $data[$field] ?? '')) {
            $bad[] = $field;
        }
    }
    if ($bad) {
        $errores[] = "Fila {$i}: formato inválido en (" . implode(', ', $bad) . ")";
        continue;
    }

    // ——— Validar FK: grados ———
    $chk = $conexion->prepare("SELECT 1 FROM grados WHERE id_grado = ?");
    $chk->bind_param("s", $data['id_grado']);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows === 0) {
        $errores[] = "Fila {$i}: no existe grado '{$data['id_grado']}'.";
        continue;
    }

    // ——— Validar FK: jornadas ———
    $chk = $conexion->prepare("SELECT 1 FROM jornadas WHERE id_jornada = ?");
    $chk->bind_param("s", $data['id_jornada']);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows === 0) {
        $errores[] = "Fila {$i}: no existe jornada '{$data['id_jornada']}'.";
        continue;
    }

    // ——— Validar FK: secciones ———
    $chk = $conexion->prepare("SELECT 1 FROM secciones WHERE id_seccion = ?");
    $chk->bind_param("s", $data['id_seccion']);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows === 0) {
        $errores[] = "Fila {$i}: no existe sección '{$data['id_seccion']}'.";
        continue;
    }

    // ——— Control de duplicado de alumno ———
    $chk = $conexion->prepare("SELECT 1 FROM alumnos WHERE id_alumno = ?");
    $chk->bind_param("s", $data['id_alumno']);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows) {
        $errores[] = "Fila {$i}: alumno '{$data['id_alumno']}' ya existe.";
        continue;
    }

    // ——— Transacción de inserciones ———
    $conexion->begin_transaction();
    try {
        // a) alumnos
        $st1 = $conexion->prepare("
            INSERT INTO alumnos
              (id_alumno,nombres_alumno,apellido1_alumno,apellido2_alumno,
               genero,telefono_alumno,direccion_alumno)
            VALUES (?,?,?,?,?,?,?)
        ");
        $st1->bind_param(
            "sssssss",
            $data['id_alumno'],
            $data['nombres_alumno'],
            $data['apellido1_alumno'],
            $data['apellido2_alumno'],
            $data['genero'],
            $data['telefono_alumno'],
            $data['direccion_alumno']
        );
        if (! $st1->execute()) {
            throw new Exception("alumnos failed: " . $st1->error);
        }

        // b) registro_alumnos (ID con random_bytes)
        $id_reg = 'R' . bin2hex(random_bytes(6));
        $st2    = $conexion->prepare("
            INSERT INTO registro_alumnos
              (id_registro_alumno,id_alumno,id_jornada,id_grado,id_seccion)
            VALUES (?,?,?,?,?)
        ");
        $st2->bind_param(
            "sssss",
            $id_reg,
            $data['id_alumno'],
            $data['id_jornada'],
            $data['id_grado'],
            $data['id_seccion']
        );
        if (! $st2->execute()) {
            throw new Exception("registro_alumnos failed: " . $st2->error);
        }

        // c) detalle_alumnos (ID con random_bytes)
        $id_det = 'D' . bin2hex(random_bytes(6));
        $st3    = $conexion->prepare("
            INSERT INTO detalle_alumnos
              (id_detalle,id_registro_alumno,cui_encargado,nombres_encargado,
               apellido1_encargado,apellido2_encargado,telefono_encargado,
               direccion_encargado,id_parentesco)
            VALUES (?,?,?,?,?,?,?,?,?)
        ");
        if (! $st3) {
            throw new Exception("prepare detalle_alumnos failed: " . $conexion->error);
        }
        $st3->bind_param(
            "sssssssss",
            $id_det,
            $id_reg,
            $data['cui_encargado'],
            $data['nombres_encargado'],
            $data['apellido1_encargado'],
            $data['apellido2_encargado'],
            $data['telefono_encargado'],
            $data['direccion_encargado'],
            $data['id_parentesco']
        );
        if (! $st3->execute()) {
            throw new Exception("detalle_alumnos failed: " . $st3->error);
        }

        // d) Generar y guardar QR
        $qr = Builder::create()
            ->writer(new PngWriter())
            ->data($data['id_alumno'])
            ->size(300)
            ->build();
        file_put_contents($qrDir . "{$data['id_alumno']}.png", $qr->getString());

        $conexion->commit();
        $insertados++;

    } catch (\Exception $e) {
        $conexion->rollback();
        $errores[] = "Fila {$i}: " . $e->getMessage();
    }
}

// ——— Preparar mensaje de retorno y redirigir ———
$msg = "Importación finalizada: {$insertados} insertados.";
if (! empty($errores)) {
    $msg           .= "<br>Errores:<br>" . implode("<br>", $errores);
    $_SESSION['tipo'] = "error";
} else {
    $_SESSION['tipo'] = "exito";
}
$_SESSION['mensaje'] = $msg;

header("Location: ../../frontend/html/import_alumnos.php");
exit;
?>