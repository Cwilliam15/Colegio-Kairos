<?php
session_start();
if (!isset($_SESSION['rol'])) {
    header('Location: ../html/menu.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Kairos</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="menu">
        <svg name="menu-outline" xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-menu"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8l16 0" /><path d="M4 16l16 0" /></svg>
        <svg name="close-outline" xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
    </div>

    <div class="barra-lateral">
        
        <div>
            <div class="nombre-pagina">
                
        <script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>
        <animated-icons id="cloud" name="cloudy-outline"
            src="https://animatedicons.co/get-icon?name=Newspaper&style=minimalistic&token=0b32a86a-22b3-4bb9-bedb-95e454e0bdf6"
            trigger="click"
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
                <a href="../html/menu.php">
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
                <a href="ingresos_alumnos.php">
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
                  <span>Ingresos</span>
                </a>
              </li>
<?php endif; ?>
              <li>
                <a id="home" href="Registros.php">
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
                <a href="Reportes.php"> 
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
                <a href="admin.php">
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
<?php } ?>
            </ul>
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
<main class="texto-contenido cuerpo">
  <h2>Alumnos Registrados</h2>
  <iframe src="../../backend/php_Reg/ver_alumnos.php" frameborder="0" width="100%" id="iframeAlumnos" onload="ajustarAlturaIframe()"></iframe>

<script>
function ajustarAlturaIframe() {
    var iframe = document.getElementById('iframeAlumnos');
    iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
}
</script>
</main>
          
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../js/scrip.js"></script>
</body>
</html>