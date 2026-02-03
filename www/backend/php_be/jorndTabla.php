<?php
include 'conexion.php';

/*

 // Consulta para obtener los registros
    $resultado = $conexion->query("SELECT * FROM jornadas");

    if (!$resultado) {
        die("Error en la consulta: " . $conexion->error);
    }       

    // Verifica si hay datos en la tabla
    if ($resultado->num_rows > 0) {
        
        echo "<table border='1'>"; // Crea una tabla con borde
        echo "<tr><th>Código Jornada</th><th>Tipo de Jornada</th></tr>"; // Encabezados de la tabla

        // Recorre cada fila obtenida de la consulta
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>"; // Crea una nueva fila en la tabla
            echo "<td>" . htmlspecialchars($fila['Id_Jornada']) . "</td>"; // Muestra el id de la jornada
            echo "<td>" . htmlspecialchars($fila['Tipo_Jornada']) . "</td>"; // Muestra el tipo de jornada
            //----------------------------
            //  PARA ELIMINAR UN REGISTRO
            //----------------------------
            echo "<td>
                <a href='?Id_Jornada={$fila['Id_Jornada']}' class='btn btn-danger btn-sm'onclick=\"return confirm('¿Eliminar este registro?');\">Eliminar</a>
              </td>"; //HASTA AQUÍ LA ELIMINACIÓN
            echo "</tr>"; // Cierra la fila
        }

        echo "</table>"; // Cierra la tabla
    } else {
        echo "No hay jornadas registradas."; // Mensaje si no hay datos
    }
        */
?>