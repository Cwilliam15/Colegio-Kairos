<?php
/**
 * generarID.php
 *
 * Función para generar un nuevo ID con prefijo, autoincrementando la parte numérica.
 */

/**
 * Genera el próximo ID con prefijo para una tabla.
 *
 * @param mysqli  $conexion    Conexión activa a la base de datos.
 * @param string  $tabla       Nombre de la tabla (p.ej. "grados").
 * @param string  $columna     Nombre de la columna PK (p.ej. "id_grado").
 * @param string  $prefijo     Prefijo del código (p.ej. "G-").
 * @param bool    $logErrores  Si es true, registrará errores en el log de PHP.
 * @return string|false        Nuevo ID (p.ej. "G-12") o false si hubo un error.
 */
function generarID($conexion, $tabla, $columna, $prefijo, $logErrores = false) {
    // 1) Validar parámetros
    if (!$conexion || !$tabla || !$columna || !$prefijo) {
        if ($logErrores) {
            error_log("generarID(): Parámetros inválidos.");
        }
        return false;
    }

    // 2) Calcular posición de inicio de la parte numérica en el ID
    //    SUBSTRING() en SQL empieza en 1, así que sumamos 1 al largo del prefijo.
    $lenPrefijo = strlen($prefijo);
    $posNumero  = $lenPrefijo + 1;

    // 3) Consultar el último ID existente, ordenando numéricamente
    $sql = "
      SELECT `$columna`
        FROM `$tabla`
       WHERE `$columna` LIKE '{$prefijo}%'
       ORDER BY CAST(SUBSTRING(`$columna`, {$posNumero}) AS UNSIGNED) DESC
       LIMIT 1
    ";
    $resultado = $conexion->query($sql);
    if (!$resultado) {
        if ($logErrores) {
            error_log("generarID(): Error en consulta SQL: " . $conexion->error);
        }
        return false;
    }

    // 4) Extraer la parte numérica y aumentar en 1
    if ($fila = $resultado->fetch_assoc()) {
        $ultimoId = $fila[$columna];                  // ej: "G-9" o "G-10"
        $numero   = (int) substr($ultimoId, $lenPrefijo); // convierte "9" o "10" a entero
        $siguiente = $numero + 1;
    } else {
        // si no hay registros previos, arrancamos en 1
        $siguiente = 1;
    }

    // 5) Devolver el nuevo ID (sin padding; si quieres 2 dígitos, usa sprintf('%02d', $siguiente))
    return $prefijo . $siguiente;
}
?>