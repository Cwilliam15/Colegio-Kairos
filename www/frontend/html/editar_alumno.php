<?php
session_start();
if (!isset($_SESSION['rol'])) {
    header('Location: ../html/menu.php');
    exit;
}
include '../../backend/php_be/conexion.php';

// Si viene por POST, actualizamos via SP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = $_POST['id'] ?? '';
    $nombres    = $_POST['nombres'] ?? '';
    $apellido1  = $_POST['apellido1'] ?? '';
    $apellido2  = $_POST['apellido2'] ?? '';
    $genero     = $_POST['genero'] ?? '';
    $telefono   = $_POST['telefono'] ?? '';
    $direccion  = $_POST['direccion'] ?? '';

    // Llamada al procedimiento almacenado
    $stmt = $conexion->prepare("CALL Proced_actualizar_alumno(?,?,?,?,?,?,?)");
    $stmt->bind_param(
        'sssssss',
        $id,
        $nombres,
        $apellido1,
        $apellido2,
        $genero,
        $telefono,
        $direccion
    );
    if ($stmt->execute()) {
        $_SESSION['msg'] = "Alumno $id actualizado correctamente.";
    } else {
        $_SESSION['msg'] = "Error al actualizar: " . $stmt->error;
    }
    $stmt->close();
    $conexion->close();

    // Redirigir de vuelta al listado
    header('Location: ../../backend/php_Reg/ver_alumnos.php');
    exit;
}

// Si es GET, mostramos el formulario con datos existentes
$id = $_GET['id'] ?? '';
if (!$id) {
    header('Location: ../../backend/php_Reg/ver_alumnos.php');
    exit;
}

// Obtenemos datos del alumno
$sql = "
  SELECT
    A.Id_Alumno,
    A.Nombres_Alumno,
    A.Apellido1_Alumno,
    A.Apellido2_Alumno,
    A.genero,
    A.Telefono_Alumno,
    A.Direccion_Alumno
  FROM alumnos A
  WHERE A.Id_Alumno = ?
";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $id);
$stmt->execute();
$result = $stmt->get_result();
$alumno = $result->fetch_assoc();
$stmt->close();
$conexion->close();

if (!$alumno) {
    // Si no existe, regresamos al listado
    header('Location: ../../backend/php_Reg/ver_alumnos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <link rel="stylesheet" href="../css/editar.css?=v1.0">

<head>
  <title>Editar Alumno <?= htmlspecialchars($id) ?></title>
</head>
<body>
  <main>
  <div class="form-container">
  <h2>Editar Alumno <?= htmlspecialchars($alumno['Id_Alumno']) ?></h2>
  <form method="post" action="">
    <input type="hidden" name="id" value="<?= htmlspecialchars($alumno['Id_Alumno']) ?>">

    <div class="fields-grid">
      <div class="field-group">
        <label for="nombres">Nombres</label>
        <input type="text" id="nombres" name="nombres" required
               value="<?= htmlspecialchars($alumno['Nombres_Alumno']) ?>">
      </div>

      <div class="field-group">
        <label for="apellido1">Apellido Paterno</label>
        <input type="text" id="apellido1" name="apellido1" required
               value="<?= htmlspecialchars($alumno['Apellido1_Alumno']) ?>">
      </div>

      <div class="field-group">
        <label for="apellido2">Apellido Materno</label>
        <input type="text" id="apellido2" name="apellido2"
               value="<?= htmlspecialchars($alumno['Apellido2_Alumno']) ?>">
      </div>

      <div class="field-group">
        <label for="genero">Género</label>
        <select id="genero" name="genero" required>
          <option value="M" <?= $alumno['genero'] === 'M' ? 'selected' : '' ?>>Masculino</option>
          <option value="F" <?= $alumno['genero'] === 'F' ? 'selected' : '' ?>>Femenino</option>
        </select>
      </div>

      <div class="field-group">
        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono"
               value="<?= htmlspecialchars($alumno['Telefono_Alumno']) ?>">
      </div>

      <div class="field-group" style="grid-column: span 2;">
        <label for="direccion">Dirección</label>
        <textarea id="direccion" name="direccion" rows="3"><?= htmlspecialchars($alumno['Direccion_Alumno']) ?></textarea>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn submit">Actualizar Alumno</button>
     <a href="../../backend/php_Reg/ver_alumnos.php" class="btn cancel">Cancelar</a>
    </div>
  </form>
</div>
</main>
</body>
</html>