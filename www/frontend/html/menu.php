<?php
session_start();
isset($_SESSION['rol']);
if (!isset($_SESSION['rol'])) {
    header("Location: ../../index.php"); // o a donde debas redirigir si no hay sesión
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Kairos</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head> 
<body>
    <header>
        <div class="container header-container vidrio">
            <!-- Logo y nombre -->
            <div class="logo-container">
                <img src="../image/LogoCC.png" alt="Kairos">
                <h1 class="gradient-text">Colegio Mixto <span>Kairos</span></h1>
            </div>

            <!-- Iconos y botones -->
            <div class="icons-container">
                <!-- Icono de lector de códigos -->
            <a href="../../backend/php_scanner/scanner.php" class="icon-btn" title="Lector de códigos">
            <ion-icon name="qr-code-outline"></ion-icon>
            </a>
                <!-- Icono de lector de códigos -->
            <a class="icon-btn" title="Modo oscuro" id="darkModeToggle">
            <ion-icon name="moon-outline"></ion-icon>
            </a>
                  <!-- Icono de notificación -->
            <div class="notification-icon" id="notifBell" title="Ausentes del día">
                <i class="fas fa-bell icon-btn"></i>
                <span class="badge">0</span>
            </div>
                <!-- Botón de ayuda -->
                <a href="https://www.canva.com/design/DAGnoOAN_Vw/OyckA0hCC5GvXoZczzD8rA/view?utm_content=DAGnoOAN_Vw&utm_campaign=designshare&utm_medium=link2&utm_source=uniquelinks&utlId=hf3732d1807" target="_blank" class="icon-btn" title="Ayuda">
                    <i class="fas fa-question-circle"></i>
                </a>
                <!-- Icono de cerrar sesión -->
                <a href="../../backend/php/cerrar_sesion.php" class="icon-btn" title="Cerrar sesión">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
</header>

<main class="container">
<section class="menu-grid">
<?php if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'admin2'): ?>
  <div id="ingresoCard" class="menu-card">
    <div class="menu-icon">
<animated-icons
  src="https://animatedicons.co/get-icon?name=Blog&style=minimalistic&token=1869947d-0b96-4314-9dd7-a86ec7a829ff"
  trigger="hover"
  attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":2.12,"defaultColours":{"group-1":"#00BCD4FF","group-2":"#1836D7FF","background":"#17171700"}}'
  height="60"
  width="60"
></animated-icons>
    </div>
    <h4>Ingreso</h4>
  </div>
<?php endif; ?>
<a href="Registros.php" class="menu-card-link">
  <div class="menu-card">
    <div class="menu-icon">
      <animated-icons
        src="https://animatedicons.co/get-icon?name=Register&style=minimalistic&token=be93a354-eb41-497f-bb52-cdf419e7d920"
        trigger="hover"
        attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":2.5,"defaultColours":{"group-1":"#00BCD4FF","group-2":"#00BCD4FF","background":"#17171700"}}'
        height="60"
        width="60"
      ></animated-icons>
    </div>
    <h4>Datos Estudiantes</h4>
  </div>
</a>
<a href="Reportes.php" class="menu-card-link">
  <div class="menu-card">
    <div class="menu-icon">
      <animated-icons
        src="https://animatedicons.co/get-icon?name=Report%20V2&style=minimalistic&token=1869947d-0b96-4314-9dd7-a86ec7a829ff"
        trigger="hover"
        attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":2.12,"defaultColours":{"group-1":"#00BCD4FF","group-2":"#1836D7","background":"#17171700"}}'
        height="60"
        width="60"
      ></animated-icons>
    </div>
    <h4>Reportes</h4>
  </div>
</a>
<?php if ($_SESSION['rol'] == 'admin') { ?>
<a href="admin.php" class="menu-card-link">
  <div class="menu-card">
    <div class="menu-icon">
      <animated-icons
        src="https://animatedicons.co/get-icon?name=user%20profile&style=minimalistic&token=1869947d-0b96-4314-9dd7-a86ec7a829ff"
        trigger="hover"
        attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":2.5,"defaultColours":{"group-1":"#00BCD4FF","group-2":"#00BCD4FF","background":"#FFFFFF00"}}'
        height="60"
        width="60"
      ></animated-icons>
    </div>
    <h4>Gestion de Usuarios</h4>
  </div>
</a>
<?php } ?>
<div id="ingresoModal" class="modal-overlay" style="display:none;">
  <div class="modal-content">
    <button id="closeModal" class="close-btn">&times;</button>
    <h3>Seleccione acción</h3>
    <ul class="modal-list">
      <li><a href="ingreso_JSG.php">Ingresar Clases</a></li>
      <li><a href="ingresos_alumnos.php">Ingresar Estudiantes</a></li>
    </ul>
  </div>
</div>
</section>
        <section class="hero-section">
            <div class="hero-text">
                <h2 class="tech-font gradient-text">Sistema <span>de control</span> <span>de inasistencia</span></h2>
                <p>"Sistema de gestión de información estudiantil para registrar y realizar seguimiento de la anasistencia".</p>
            </div>
            <div class="hero-image">
                <img src="../image/SVW.png" alt="SVW">
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="../image/SVW.png" alt="SVW">
                    <p>Scan Verific Watch - SVW</p>
                </div>
                <div class="copyright">
                    <p>&copy; 2025 SVW. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>
    <script>
  // Obtener elementos
  const ingresoCard = document.getElementById('ingresoCard');
  const ingresoModal = document.getElementById('ingresoModal');
  const closeModal = document.getElementById('closeModal');

  // Al hacer click sobre la card → mostrar modal
  ingresoCard.addEventListener('click', () => {
    ingresoModal.style.display = 'flex';
  });

  // Cerrar modal:
  closeModal.addEventListener('click', () => {
    ingresoModal.style.display = 'none';
  });
  // También, al click fuera del contenido
  ingresoModal.addEventListener('click', e => {
    if (e.target === ingresoModal) {
      ingresoModal.style.display = 'none';
    }
  });
</script>
    <script>
  // Función que consulta el count de ausentes
  async function updateNotificationCount() {
    try {
      const res = await fetch('../../backend/php_scanner/notifications.php');
      const data = await res.json();
      const badge = document.querySelector('#notifBell .badge');
      badge.textContent = data.count;

      // Opcional: cambia el color si hay ausentes
      if (data.count > 0) {
        badge.style.background = 'red';
      } else {
        badge.style.background = '#ccc';
      }
    } catch (err) {
      console.error('Error al cargar notificaciones:', err);
    }
  }

  // Al hacer click en la campana, vamos al reporte de ausentes de hoy
  document.getElementById('notifBell').addEventListener('click', () => {
    const hoy = new Date().toISOString().slice(0,10);
    window.location.href = `Reportes.php?fecha=${hoy}`;
  });

  // Actualizar al cargar y cada minuto
  updateNotificationCount();
  setInterval(updateNotificationCount, 60000);

document.addEventListener("DOMContentLoaded", function() {
  const toggle = document.getElementById("darkModeToggle");
  const icon = toggle.querySelector("ion-icon");

  // Aplicar tema guardado
  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
    icon.setAttribute("name", "sunny-outline");
  }

  toggle.addEventListener("click", function() {
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
      icon.setAttribute("name", "sunny-outline");
      localStorage.setItem("theme", "dark");
    } else {
      icon.setAttribute("name", "moon-outline");
      localStorage.setItem("theme", "light");
    }
  });
});
</script>
<script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>