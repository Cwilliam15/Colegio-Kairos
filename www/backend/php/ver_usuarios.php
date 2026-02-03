<link rel="stylesheet" href="../../frontend/css/ver_usuarios.css" />
<?php
include "conexion_be.php";

$consulta   = "SELECT nombre_completo, correo, rol, usuario FROM usuarios";
$resultado  = mysqli_query($conexion, $consulta);

// S?lo si hay filas, mostramos la tabla
if (mysqli_num_rows($resultado) > 0) {
    // Contenedor para scroll horizontal si es necesario
    echo '<div class="usuarios-table-container">';
    // Tabla con clase para nuestros estilos
    echo '<table class="usuarios-table">';
    echo '<thead>';
    echo '  <tr>';
    echo '    <th>Nombre</th>';
    echo '    <th>Correo</th>';
    echo '    <th>Rol</th>';
    echo '    <th>Usuario</th>';
    echo '  </tr>';
    echo '</thead>';
    echo '<tbody>';
    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Escape de datos por seguridad
        $nombre  = htmlspecialchars($fila['nombre_completo'],   ENT_QUOTES, 'UTF-8');
        $correo  = htmlspecialchars($fila['correo'],            ENT_QUOTES, 'UTF-8');
        $rol     = htmlspecialchars($fila['rol'],               ENT_QUOTES, 'UTF-8');
        $usuario = htmlspecialchars($fila['usuario'],           ENT_QUOTES, 'UTF-8');
        echo "<tr>
                <td>{$nombre}</td>
                <td>{$correo}</td>
                <td>{$rol}</td>
                <td>{$usuario}</td>
              </tr>";
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // cierre de usuarios-table-container
} else {
    echo '<p>No hay usuarios registrados.</p>';
}

mysqli_close($conexion);
?>