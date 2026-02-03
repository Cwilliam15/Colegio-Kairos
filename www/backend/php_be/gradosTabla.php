<?php
        //Verificar si hay datos en la tabla
        if ($resultado->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Código grado</th><th>Nombre grado</th></tr>"; //encabezados de la tabla

            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>"; //nueva fila en la tabla
                echo "<td>" . htmlspecialchars($fila['Id_Grado']) . "</td>"; //muestra el id del grado
                echo "<td>" . htmlspecialchars($fila['Nombre_Grado']) . "</td>"; //muestra el nombre del grado
                
            }
            echo "</table>"; // Cierra la tabla
        } else {
            echo "No hay grados registrados.";
        }
?>