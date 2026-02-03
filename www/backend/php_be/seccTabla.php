<?php
        //Verificar si hay datos en la tabla
        if ($resultado->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Código sección</th><th>Nombre de sección</th></tr>"; //encabezados de la tabla

            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>"; //nueva fila en la tabla
                echo "<td>" . htmlspecialchars($fila['Id_Seccion']) . "</td>"; //muestra el id del grado
                echo "<td>" . htmlspecialchars($fila['Nombre_Seccion']) . "</td>"; //muestra el nombre del grado
                
            }
            echo "</table>";
        } else 
        {
            echo "No hay secciones registradas.";
        }
?>