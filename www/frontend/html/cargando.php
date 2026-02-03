<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: ../../index.php");
    exit();
}
$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cargando...</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    /* Reseteo y variables */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
      --bg1:rgb(6, 7, 7);
      --bg2:rgb(0, 0, 0);
      --accent1:rgb(78, 220, 255);
      --accent2:rgb(69, 22, 129);
      --glass-bg: rgba(0, 0, 0, 0.1);
      --glass-border: rgba(0, 0, 0, 0.25);
    }
    body {
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      font-family: 'Montserrat', sans-serif;
      color: #fff;
      background: linear-gradient(135deg, var(--bg1), var(--bg2));
      background-size: 200% 200%;
      animation: bgShift 12s ease infinite;
    }

    @keyframes bgShift {
      0% { background-position: 0% 0%; }
      50% { background-position: 100% 100%; }
      100% { background-position: 0% 0%; }
    }

    .logo {
      width: 160px;
      animation: logoPulse 2s infinite ease-in-out;
      margin-bottom: 2rem;
    }
    @keyframes logoPulse {
      0%,100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    .glass {
      backdrop-filter: blur(8px);
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: 50px;
      padding: 1.5rem;
      width: 320px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }

    .progress-wrapper {
      position: relative;
      height: 20px;
      background: rgba(255,255,255,0.1);
      border-radius: 50px;
      overflow: hidden;
      margin-top: 1rem;
    }
    .progress-bar {
      height: 100%;
      width: 0%;
      background: linear-gradient(90deg, var(--accent1), var(--accent2));
      box-shadow: 0 0 10px var(--accent1), 0 0 20px var(--accent2);
      border-radius: 50px;
      transition: width 0.2s ease-out;
    }
    .progress-text {
      position: absolute;
      top: 0; left: 50%;
      transform: translateX(-50%);
      font-size: 0.9rem;
      font-weight: 500;
      color: #fff;
    }

    .mensaje {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 1.1rem;
      opacity: 0;
      animation: fadeUp 1.5s forwards 1;
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <!-- Logo -->
  <img src="../../frontend/image/logo_svw.jpg" alt="SVW Logo" class="logo">

  <!-- Contenedor glass -->
  <div class="glass">
    <div class="progress-wrapper">
      <div class="progress-bar" id="barra"></div>
      <div class="progress-text" id="porcentaje">0%</div>
    </div>
    <div class="mensaje">Bienvenido <?= htmlspecialchars($usuario) ?>, preparando tu panel…</div>
  </div>

  <script>
  const barra = document.getElementById('barra');
  const texto = document.getElementById('porcentaje');
  let progreso = 0;

  const interval = setInterval(() => {
    progreso++;
    barra.style.width = progreso + '%';
    texto.textContent = progreso + '%';

    if (progreso >= 100) {
      clearInterval(interval);
      // Redirige según rol
      window.location.href = 'menu.php';
    }
  }, 20);
</script>

</body>
</html>