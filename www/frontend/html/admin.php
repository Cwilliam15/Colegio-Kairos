<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    die();
}

if ($_SESSION['rol'] != 'admin') {
    header("Location: menu.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel de Administrador</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/admin.css?=v8.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <link rel="stylesheet" href="../css/styles.css">
<script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>

</head>

<body>
<div class="menu">
        <svg name="menu-outline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-menu">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 8l16 0" />
            <path d="M4 16l16 0" />
        </svg>
        <svg name="close-outline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M18 6l-12 12" />
            <path d="M6 6l12 12" />
        </svg>
    </div>

    <div class="barra-lateral">

        <div>
            <div class="nombre-pagina">
                <script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>
                <animated-icons id="cloud" name="cloudy-outline" title="Esconder la barra lateral"
                    src="https://animatedicons.co/get-icon?name=Newspaper&style=minimalistic&token=0b32a86a-22b3-4bb9-bedb-95e454e0bdf6"
                    trigger="hover"
                    attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":1,"defaultColours":{"group-1":"#000000","group-2":"#536DFE","background":"#FFFFFF00"}}'
                    height="50"
                    width="50">
                </animated-icons>

                <span>Colegio Kairos</span>
            </div>
        </div>

        <nav class="navegacion">
            <ul>
              <li>
                <a href="../html/menu.php" title="Inicio">
                  <svg
                    name="home-outline"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-home">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                  </svg>
                  <span>Inicio</span>
                </a>
              </li>
              <?php if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'admin2'): ?>
                  <li>
                    <a   href="ingreso_JSG.php" title="Ingresar Clases">
              <svg
  xmlns="http://www.w3.org/2000/svg"
  width="24"
  height="24"
  viewBox="0 0 64 64"
  fill="none"
  stroke="currentColor"
  stroke-width="2"
  stroke-linecap="round"
  stroke-linejoin="round"
  class="icon icon-tabler icon-tabler-form-plus"
>
  <rect x="10" y="8" width="44" height="48" rx="4" />
  <line x1="18" y1="20" x2="46" y2="20" />
  <line x1="18" y1="28" x2="46" y2="28" />
  <line x1="18" y1="36" x2="34" y2="36" />
  <circle cx="48" cy="48" r="8" stroke="currentColor" />
  <line x1="48" y1="44" x2="48" y2="52" />
  <line x1="44" y1="48" x2="52" y2="48" />
</svg>

                      <span>Ingreso de Clases</span>
                    </a>
                  </li>
                <?php endif; ?>
              <li>
                <a href="ingresos_alumnos.php" title="Ingreso Estudiantes">
                  <svg
                    name="pencil-outline"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-pencil">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                      d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                    <path d="M13.5 6.5l4 4" />
                  </svg>
                  <span>Ingreso Estudiantes</span>
                </a>
              </li>
              <li>
                <a href="Registros.php" title="Ver los datos de los estudiantes">
                <svg
                  name="location-outline"
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="icon icon-tabler icons-tabler-outline icon-tabler-note">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M13 20h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8l4 4v10a2 2 0 0 1 -2 2h-2" />
                  <path d="M13 20v-4a2 2 0 0 1 2 -2h4" />
                </svg>
                  <span>Datos Estudiantes</span>
                </a>
              </li>
              <li>
                <a href="Reportes.php" title="Ver reportes de Inasistencias"> 
                <svg
                    name="pencil-outline"
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-report">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                    <path d="M18 14v4h4" />
                    <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" />
                    <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                    <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path d="M8 11h4" />
                    <path d="M8 15h3" />
                </svg>
                  <span>Reportes</span>
                </a>
              </li>
        <?php if ($_SESSION['rol'] == 'admin') { ?>
              <li>
                <a id="home" href="admin.php" title="Gestionar Usuarios">
                <svg
                  name="person-add-outline" 
                  xmlns="http://www.w3.org/2000/svg" 
                  width="24" 
                  height="24" 
                  viewBox="0 0 24 24" 
                  fill="none" 
                  stroke="currentColor" 
                  stroke-width="2" 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  class="icon icon-tabler icon-tabler-user-filled-circle">
                  <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none" />
                  <circle cx="12" cy="9.5" r="3" fill="currentColor" stroke="none" />
                  <path 
                  d="M6 18c2 -4 4 -5 6 -5s4 1 6 5v0.5c0 .3 -.2 .5 -.5 .5h-11c-.3 0 -.5 -.2 -.5 -.5z" 
                  fill="currentColor" 
                  stroke="none" />
                </svg>
                  <span>Gestion de Usuarios</span>
                </a>
              </li>
            </ul>
        <?php } ?>
          </nav>
        <div>
            <div class="linea">
                <div class="modo-oscuro">
                <div class="info">
            <svg
              class="moon"
              name="moon-outline"
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="currentColor"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="icon icon-tabler icons-tabler-outline icon-tabler-moon">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path
                d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
            </svg>
            <span>Modo Oscuro</span>
          </div>
                    <div class="switch">
                        <div class="base">
                            <div class="circulo">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="usuario">
                    <img src="../image/LogoCC.png" alt="CompuCentro Coban">
                    <div class="info-usuario">
                        <div class="nombre">
                            <span class="nombre">Colegio Kairos</span>
                        </div>
                        <ion-icon name="ellipsis-vertical-outline"></ion-icon>
                    </div>
                </div>
            </div>

        </div>
    </div>
 <main class="container">

<?php if (isset($_GET['success'])): ?>
  <div class="toast toast-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <div class="toast toast-error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<div class="gestion-container">
  <h1>Gestión de Usuarios</h1>

  <!-- Los 5 botones -->
  <div class="tabs-container">
    <a class="tab-btn active" onclick="showTab('agregar')" style="border-radius: 10px;">
<animated-icons
  src="https://animatedicons.co/get-icon?name=user%20profile&style=minimalistic&token=9b327b61-1433-451f-a476-148402217e82"
  trigger="hover"
  attributes='{"variationThumbColour":"#A4A7A9","variationName":"Gray Tone","variationNumber":3,"numberOfGroups":1,"strokeWidth":1.5,"backgroundIsGroup":true,"defaultColours":{"group-1":"#1e3a5f","background":"#BFCDE400"}}'
  height="60"
  width="60"
></animated-icons>
    Agregar Usuario
</a>

    <a class="tab-btn" onclick="showTab('ver')" style="border-radius: 10px;">
<animated-icons
  src="https://animatedicons.co/get-icon?name=Team&style=minimalistic&token=9c840f28-8274-4ee6-b5b3-7707bde20ce4"
  trigger="hover"
  attributes='{"variationThumbColour":"#A4A7A9","variationName":"Gray Tone","variationNumber":3,"numberOfGroups":1,"strokeWidth":1.5,"backgroundIsGroup":true,"defaultColours":{"group-1":"#1e3a5f","background":"#BFCDE400"}}'
  height="60"
  width="60"
></animated-icons>
      Ver Usuarios Existentes
</a>

    <a class="tab-btn" onclick="showTab('actividades')" style="border-radius: 10px;">
<animated-icons
  src="https://animatedicons.co/get-icon?name=Activity&style=minimalistic&token=59f1f68e-13c8-4253-a639-70e484ca7057"
  trigger="hover"
  attributes='{"variationThumbColour":"#A4A7A9","variationName":"Gray Tone","variationNumber":3,"numberOfGroups":1,"strokeWidth":1.5,"backgroundIsGroup":true,"defaultColours":{"group-1":"#1E3A5FFF","background":"#BFCDE400"}}'
  height="60"
  width="60"
></animated-icons>
      Ver Actividades de Usuario
</a>

    <a class="tab-btn" onclick="showTab('password')" style="border-radius: 10px;">
<animated-icons
  src="https://animatedicons.co/get-icon?name=Edit%20V2&style=minimalistic&token=59f1f68e-13c8-4253-a639-70e484ca7057"
  trigger="hover"
  attributes='{"variationThumbColour":"#A4A7A9","variationName":"Gray Tone","variationNumber":3,"numberOfGroups":1,"strokeWidth":1.5,"backgroundIsGroup":true,"defaultColours":{"group-1":"#1E3A5FFF","background":"#BFCDE400"}}'
  height="60"
  width="60"
></animated-icons>
      Cambiar Contraseña
</a>

    <a class="tab-btn" onclick="showTab('eliminar')" style="border-radius: 10px;">
<animated-icons
  src="https://animatedicons.co/get-icon?name=delete&style=minimalistic&token=c1352b7b-2e14-4124-b8fd-a064d7e44225"
  trigger="hover"
  attributes='{"variationThumbColour":"#A4A7A9","variationName":"Gray Tone","variationNumber":3,"numberOfGroups":1,"strokeWidth":1.5,"backgroundIsGroup":true,"defaultColours":{"group-1":"#1E3A5FFF","background":"#BFCDE400"}}'
  height="50"
  width="50"
></animated-icons>
      Eliminar Usuario
</a>
  </div>

  <!-- Contenedor grande de contenido -->
  <div class="tab-content-container">
    
    <!-- Agregar Usuario -->
    <div id="agregar" class="tab-content active">
      <form class="cuerpoFormularios" action="../../backend/php/registro_usuario_be.php" method="POST">
        <input type="text" placeholder="Nombre completo" required name="nombre_completo" />
        <input type="email" placeholder="Correo electrónico" required name="correo"/>
        <select name="rol" required>
          <option value="">Seleccione un rol</option>
          <option value="admin2">Subadministrador</option>
          <option value="usuario">Usuario de lectura</option>
        </select>
        <input type="text" placeholder="Nombre de usuario" required name="usuario"/>
        <input type="password" placeholder="Contraseña" required name="contrasena"/>
        <button type="submit" class="btn-agregar">Agregar Usuario</button>
      </form>
    </div>

    <!-- Ver Usuarios -->
    <div id="ver" class="tab-content">
      <iframe src="../../backend/php/ver_usuarios.php" class="usuarios-frame"></iframe>
    </div>

    <!-- Ver Actividades -->
    <div id="actividades" class="tab-content">
      <iframe src="../../backend/php/ver_logs.php" class="usuarios-frame"></iframe>
    </div>

    <!-- Cambiar Contraseña -->
    <div id="password" class="tab-content">
      <form class="cuerpoFormularios" action="../../backend/php/cambiar_contrasena.php" method="POST">
        <input type="text" placeholder="Usuario" required name="usuario" />
        <input type="password" placeholder="Contraseña Actual" required name="contrasena_actual" />
        <input type="password" placeholder="Nueva Contraseña" required name="nueva_contrasena" />
        <button type="submit" class="btn-cambiar">Cambiar Contraseña</button>
      </form>
    </div>

    <!-- Eliminar Usuario -->
    <div id="eliminar" class="tab-content">
      <form class="cuerpoFormularios" action="../../backend/php/eliminar_usuario.php" method="POST">
        <input type="text" placeholder="Usuario a eliminar" required name="usuario" />
        <button type="submit" class="btn-eliminar">Eliminar Usuario</button>
      </form>
    </div>

  </div>
</div>

<script>
// Función para cambiar entre pestañas
function showTab(tabId) {
  // Ocultar todos los contenidos
  const allContents = document.querySelectorAll('.tab-content');
  allContents.forEach(content => {
    content.classList.remove('active');
  });
  
  // Desactivar todos los botones
  const allButtons = document.querySelectorAll('.tab-btn');
  allButtons.forEach(btn => {
    btn.classList.remove('active');
  });
  
  // Mostrar el contenido seleccionado
  document.getElementById(tabId).classList.add('active');
  
  // Activar el botón correspondiente
  event.target.closest('.tab-btn').classList.add('active');
}
</script>

<script>
// Cambio de tema (sin tema por defecto)
const toggle = document.getElementById('toggleTheme');
const body = document.body;

// Solo aplicar tema si existe en localStorage
const theme = localStorage.getItem('theme');
if (theme === 'dark') {
  body.classList.add('dark-mode');
  toggle.querySelector('i').classList.replace('fa-moon','fa-sun');
} else if (theme === 'light') {
  body.classList.add('light-mode');
  toggle.querySelector('i').classList.replace('fa-moon','fa-sun');
}

toggle.addEventListener('click', e => {
  e.preventDefault();

  if (body.classList.contains('dark-mode')) {
    body.classList.replace('dark-mode','light-mode');
    localStorage.setItem('theme','light');
    toggle.querySelector('i').classList.replace('fa-sun','fa-moon');
  } else if (body.classList.contains('light-mode')) {
    body.classList.replace('light-mode','dark-mode');
    localStorage.setItem('theme','dark');
    toggle.querySelector('i').classList.replace('fa-moon','fa-sun');
  } else {
    // Primera vez: activar modo oscuro
    body.classList.add('dark-mode');
    localStorage.setItem('theme','dark');
    toggle.querySelector('i').classList.replace('fa-moon','fa-sun');
  }
});
</script>

<script>
// Toast messages
setTimeout(() => {
  const toast = document.querySelector('.toast');
  if (toast) {
    toast.style.transition = 'opacity 0.5s ease';
    toast.style.opacity = '0';
    setTimeout(() => toast.remove(), 500);
  }
}, 5000);
</script>
</main>
    <script src="../js/scrip.js"></script>
</body>
</html>