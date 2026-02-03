<?php
session_start();
if (!isset($_SESSION['rol'])) {
    header('Location: ../html/menu.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
  <meta charset="UTF-8">
  <title>Importar Alumnos Masivos</title>
  <link rel="stylesheet" href="../css/import.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <style>
    .mensaje { padding: .5rem; margin-bottom:1rem; border-radius:.3rem; }
    .mensaje.exito { background:#d4edda; color:#155724; }
    .mensaje.error { background:#f8d7da; color:#721c24; }
  </style>
</head>
<body>
  <div class="side-icons">
  <a href="../../frontend/html/ingresos_alumnos.php" id="toggleTheme" title="Volver">
    <i class="fas fa-arrow-left"></i>
  </a>
  <a href="../html/menu.php" title="Volver al menú">
    <i class="fas fa-home"></i>
  </a>
  <a href="../../backend/php/cerrar_sesion.php" title="Cerrar sesión">
    <i class="fas fa-sign-out-alt"></i>
  </a>
</div>
  <?php if(!empty($_SESSION['mensaje'])): ?>
    <div class="mensaje <?= $_SESSION['tipo'] ?>">
      <?= $_SESSION['mensaje'] ?>
    </div>
    <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
  <?php endif; ?>

<form id="form-import"
      action="../../backend/php_im+/procesar_import.php"
      method="POST"
      enctype="multipart/form-data">

  <div class="file-upload">
    <!-- 1.1 El “botón” que abre el selector -->
    <label for="archivo" class="custom-file-button">
      Seleccionar archivo
    </label>
    <!-- 1.2 El input real, lo escondemos un poco -->
    <input 
      type="file" 
      name="archivo" 
      id="archivo"
      accept=".xlsx,.xls,.csv" 
      required 
    />
    <!-- 1.3 Aquí mostraremos el nombre (o “Sin archivos seleccionados”) -->
    <span id="file-name">Sin archivos seleccionados</span>
  </div>

  <button type="submit">CARGAR Y PROCESAR</button>
</form>
  <script src="../js/validaciones_import.js"></script>
</body>
</html>