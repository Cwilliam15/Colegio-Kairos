<?php
// ─── Mostrar todos los errores y hacer que mysqli lance excepciones ─────────
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header('Content-Type: text/html; charset=utf-8');
// ─── Inicialización y conexión ──────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../php_be/conexion.php';

if (!$conexion) {
    die("❌ Error al conectar con la base de datos.");
}

// ─── Lógica de inserción ────────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Datos del alumno
    $id_alumno         = strtoupper(trim($_POST['id_alumno']));
    $nombres_alumno    = trim($_POST['nombres_alumno']);
    $apellido1_alumno  = trim($_POST['apellido1_alumno']);
    $apellido2_alumno  = !empty($_POST['apellido2_alumno']) ? trim($_POST['apellido2_alumno']) : null;
    $genero_alumno     = $_POST['genero'];  // 'M' o 'F'
    $telefono_alumno   = !empty($_POST['telefono_alumno']) ? trim($_POST['telefono_alumno']) : null;
    $direccion_alumno  = trim($_POST['direccion_alumno']);

    // Datos del encargado
    $cui_encargado       = trim($_POST['cui_encargado']);
    $nombres_encargado   = trim($_POST['nombres_encargado']);
    $apellido1_encargado = trim($_POST['apellido1_encargado']);
    $apellido2_encargado = !empty($_POST['apellido2_encargado']) ? trim($_POST['apellido2_encargado']) : null;
    $telefono_encargado  = trim($_POST['telefono_encargado']);
    $direccion_encargado = trim($_POST['direccion_encargado']);

    // Listas desplegables
    $id_parentesco = $_POST['id_parentesco'];
    $id_jornada    = $_POST['id_jornada'];
    $id_grado      = $_POST['id_grado'];
    $id_seccion    = isset($_POST['id_seccion']) && $_POST['id_seccion'] !== ''
                    ? $_POST['id_seccion'] : null;

    // Generar IDs automáticos
    include 'generarID.php';
    $id_registro_alumno = generarID($conexion, "registro_alumnos", "id_registro_alumno", "R-");
    $id_detalle_alumno  = generarID($conexion, "detalle_alumnos",  "id_detalle",           "D-");

    // Verificar duplicados
    $v = $conexion->prepare("SELECT id_alumno FROM alumnos WHERE id_alumno = ?");
    $v->bind_param("s", $id_alumno);
    $v->execute();
    $v->store_result();
    if ($v->num_rows > 0) {
        $_SESSION['mensaje'] = "⚠️ Ya existe un alumno con ese ID. Por favor, revise sus datos.";
        $_SESSION['tipo']    = "error";
        header("Location: ../../frontend/html/ingresos_alumnos.php");
        exit;
    }

    try {
        // 1) Genera dinámicamente una lista de 19 "?"
$placeholders = implode(',', array_fill(0, 19, '?'));  
//    esto te da "?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?"

// 2) Monta tu CALL sin comentarios
$sql = "CALL Proced_insertar_alumno($placeholders)";

// 3) Prepáralo
$stmt = $conexion->prepare($sql);

// 4) Cadena de tipos: 19 "s"
$types = str_repeat('s', 19);

// 5) Haz el bind con las 19 variables, en el orden exacto
$stmt->bind_param($types,
    // 1–7: alumnos
    $id_alumno,
    $nombres_alumno,
    $apellido1_alumno,
    $apellido2_alumno,
    $genero_alumno,
    $telefono_alumno,
    $direccion_alumno,
    // 8–11: registro
    $id_registro_alumno,
    $id_jornada,
    $id_grado,
    $id_seccion,
    // 12–18: detalle
    $id_detalle_alumno,
    $cui_encargado,
    $nombres_encargado,
    $apellido1_encargado,
    $apellido2_encargado,
    $telefono_encargado,
    $direccion_encargado,
    // 19: parentesco
    $id_parentesco
);

// 6) Ejecuta
$stmt->execute();

        // Generar y guardar QR
        require_once __DIR__ . '/../php_be/phpqrcode/qrlib.php';
        $dir      = __DIR__ . '/../temp';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $filename = "$dir/qr_{$id_alumno}.png";
        QRcode::png($id_alumno, $filename, 'L', 5, 1);

        $_SESSION['mensaje'] = "✅ Registro guardado correctamente.";
        $_SESSION['tipo']    = "success";

    } catch (Exception $e) {
        $_SESSION['mensaje'] = "❌ Error al guardar: " . $e->getMessage();
        $_SESSION['tipo']    = "error";
    }

    // Redirigir con ruta del QR
    $rel = 'backend/temp/qr_' . urlencode($id_alumno) . '.png';
    header("Location: ../../frontend/html/ingresos_alumnos.php?qr=$rel");
    exit;
}
?>