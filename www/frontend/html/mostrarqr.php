<?php
if (isset($_GET['file'])) {
    $filename = basename($_GET['file']); // Esto previene rutas como ../../
    $filepath = __DIR__ . '/../../backend/temp/' . $filename;

    if (file_exists($filepath)) {
        header('Content-Type: image/png');
        readfile($filepath);
        exit;
    }
}

http_response_code(404);
echo "❌ No se pudo encontrar el código QR solicitado.";
?>