<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['rol'])) {
    header('Location: ../frontend/html/menu.php');
    exit;
}
include __DIR__ . '/conexion.php';
$lectores = $conexion->query("SELECT Id_Lector, Ubicacion FROM lectores ORDER BY Ubicacion");
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenido al Colegio</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../frontend/css/scanner.css?=v8.0">
</head>
<body>
  <img id="icono" src="" alt="Estado" />
  <div class="container">
    <div class="logo">
      <img src="../../frontend/image/LogoCC.png" alt="Logo del Colegio">
    </div>
    <h1>¡Bienvenido al Colegio!</h1>

    <label for="lector">Selecciona el lector:</label>
    <select id="lector">
      <?php while($f = $lectores->fetch_assoc()): ?>
        <option value="<?= $f['Id_Lector'] ?>">
          <?= htmlspecialchars($f['Ubicacion']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <div id="mensaje">
      <span id="textoMensaje">Esperando lectura…</span>
    </div>

    <a href="../../frontend/html/menu.php" class="btn-volver">← Volver al menú</a>
    <input type="text" id="qrinput" autocomplete="off" />
  </div>

  <script>
    const input = document.getElementById('qrinput');
    const texto = document.getElementById('textoMensaje');
    const icono = document.getElementById('icono');
    const sel   = document.getElementById('lector');

    function enfocar() { input.focus(); }
    ['load','click','keydown'].forEach(evt => window.addEventListener(evt, enfocar));

    input.addEventListener('keypress', e => {
      if (e.key !== 'Enter') return;
      e.preventDefault();

      const codigo = input.value.trim();
      input.value = '';
      if (!codigo) return;

      icono.style.display = 'none';
      texto.textContent   = 'Procesando…';

      fetch('registro.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          codigo: codigo,
          lector: sel.value
        })
      })
      .then(resp => {
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        return resp.json();
      })
      .then(d => {
        icono.src = d.success
          ? '../../frontend/image/aprovacion1.png'
          : '../../frontend/image/desaprovacion1.png';
        icono.style.display = 'block';
        texto.textContent = d.message;

        setTimeout(() => {
          icono.style.display = 'none';
          texto.textContent = 'Esperando lectura…';
          enfocar();
        }, 4000);
      })
      .catch(err => {
        console.error('Fetch error:', err);
        icono.src = '../../frontend/image/desaprovacion1.png';
        icono.style.display = 'block';
        texto.textContent = 'Error de comunicación.';

        setTimeout(() => {
          icono.style.display = 'none';
          texto.textContent = 'Esperando lectura…';
          enfocar();
        }, 1500);
      });
  });
  </script>
</body>
</html>