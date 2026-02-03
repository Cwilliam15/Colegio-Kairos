<?php
// Mostrar el archivo QR desde la carpeta 'temp/'
// Obtener el nombre del archivo QR desde la URL
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    
    // Definir la ruta del archivo QR dentro de la carpeta 'temp/'
    $path = __DIR__ . '/temp' . $file;

    // Verificar si el archivo existe
    if (file_exists($path)) {
        // Establecer el encabezado para que se muestre como una imagen PNG
        header('Content-Type: image/png');
        // Leer y enviar el archivo al navegador
        readfile($path);
    } else {
        echo 'Archivo no encontrado.';
    }
} else {
    echo 'No se ha especificado un archivo.';
}
?>