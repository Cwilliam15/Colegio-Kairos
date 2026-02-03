<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

// 1) Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode([
      'success' => false,
      'message' => 'No autorizado. Inicia sesión primero.'
    ]);
    exit;
}

// 2) Incluir Logger y la conexión a BD
require_once __DIR__ . '/../php/logger.php';
require_once __DIR__ . '/../php_be/conexion.php';

// 3) Leer y decodificar JSON de la petición
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// 3.1) Validar JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
      'success' => false,
      'message' => 'JSON inválido: ' . json_last_error_msg()
    ]);
    exit;
}

// 4) Extraer y validar el ID del alumno
$id = trim($data['idAlumno'] ?? '');
if ($id === '') {
    http_response_code(422);
    echo json_encode([
      'success' => false,
      'message' => 'Falta el parámetro idAlumno.'
    ]);
    exit;
}

try {
    // 5) Preparar y ejecutar el Stored Procedure
    $stmt = $conexion->prepare("CALL Proced_eliminar_alumno(?)");
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }
    $stmt->bind_param('s', $id);

    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar SP: " . $stmt->error);
    }

    // 6) Registrar en el log
    registrarLog("Se eliminó el alumno con ID: $id");

    // 7) Responder éxito
    echo json_encode([
      'success' => true,
      'message' => "Alumno $id eliminado correctamente."
    ]);
    
} catch (Exception $e) {
    // 8) Capturar cualquier excepción y devolver error
    http_response_code(500);
    echo json_encode([
      'success' => false,
      'message' => "Ha ocurrido un error: " . $e->getMessage()
    ]);
} finally {
    // 9) Liberar recursos
    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
    $conexion->close();
}
?>