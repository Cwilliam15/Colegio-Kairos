<link rel="stylesheet" href="../../frontend/css/ver_logs.css" />
<?php
session_start();
include 'conexion_be.php';

// Ajusta el nombre de tu tabla y columnas seg?n tu esquema
$sql = "
    SELECT 
      la.fecha,
      u.usuario,
      la.actividad,
      la.ip
    FROM logs_actividades AS la
    JOIN usuarios AS u
      ON la.usuario_id = u.id
    ORDER BY la.fecha DESC
    LIMIT 100
";

$result = $conexion->query($sql);
if (!$result) {
    die("Error al obtener actividades: " . $conexion->error);
}

// Estilos m?nimos para la tabla
echo '<style>
  .logs-table { width:100%; border-collapse: collapse; margin-top:1rem; }
  .logs-table th, .logs-table td { padding: 0.5rem; border: 1px solid #ccc; text-align: left; }
  .logs-table th { background: #f5f5f5; }
</style>';

echo '<table class="logs-table">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Actividad</th>
            <th>IP</th>
          </tr>
        </thead>
        <tbody>';

while ($row = $result->fetch_assoc()) {
    echo '<tr>
            <td>'.htmlspecialchars($row['fecha']).'</td>
            <td>'.htmlspecialchars($row['usuario']).'</td>
            <td>'.htmlspecialchars($row['actividad']).'</td>
            <td>'.htmlspecialchars($row['ip']).'</td>
          </tr>';
}

echo '  </tbody>
      </table>';
?>