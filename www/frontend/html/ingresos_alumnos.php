<?php
session_start();
if (!isset($_SESSION['rol'])) {
    header('Location: ../html/menu.php');
    exit;
}
include('../../backend/php_be/conexion.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Colegio Kairos</title>
  <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css" />
</head>

<body>
  <div class="menu">
    <svg
      name="menu-outline"
      xmlns="http://www.w3.org/2000/svg"
      width="24"
      height="24"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      stroke-width="2"
      stroke-linecap="round"
      stroke-linejoin="round"
      class="icon icon-tabler icons-tabler-outline icon-tabler-menu">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M4 8l16 0" />
      <path d="M4 16l16 0" />
    </svg>

    <svg
      name="close-outline"
      xmlns="http://www.w3.org/2000/svg"
      width="24"
      height="24"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      stroke-width="2"
      stroke-linecap="round"
      stroke-linejoin="round"
      class="icon icon-tabler icons-tabler-outline icon-tabler-x">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M18 6l-12 12" />
      <path d="M6 6l12 12" />
    </svg>
  </div>

  <div class="barra-lateral">
    <div>
      <div class="nombre-pagina">
        <script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>
        <animated-icons
          id="cloud"
          name="cloudy-outline"
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
          <a href="menu.php" title="Menu">
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
                    <a href="ingreso_JSG.php" title="Ingreso de Clases">
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
          <a id="home" href="ingresos_alumnos.php" title="Ingreso Estudiantes">
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
          <a href="Registros.php" title="Datos Estudiantes">
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
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M13 20h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8l4 4v10a2 2 0 0 1 -2 2h-2" />
              <path d="M13 20v-4a2 2 0 0 1 2 -2h4" />
            </svg>
            <span>Datos Estudiantes</span>
          </a>
        </li>
        <li>
          <a href="Reportes.php" title="Reportes de Inasistencias">
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
            <a href="admin.php" title="Gestion de Usuarios">
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
              <div class="circulo"></div>
            </div>
          </div>
        </div>

        <div class="usuario">
          <img src="../image/LogoCC.png" alt="CompuCentro Coban" />
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
  <!------------------------------------------------------------CONTENIDO PRINCIPAL-------------------------------------------------------->
  <main class="texto-contenido cuerpo">
    <?php if (isset($_SESSION['mensaje'])): ?>
  <div id="notificationBar" class="notification-bar <?= $_SESSION['tipo']; ?>">
    <?= $_SESSION['mensaje']; ?>
  </div>
  <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
<?php endif; ?>
      <!-- Botón de Importar -->
 <a 
    href="import_alumnos.php" 
    class="btn-importar" 
    title="Importar Excel"
  >
<svg width="60" height="60" viewBox="0 0 64 80" xmlns="http://www.w3.org/2000/svg">
  <!-- Documento con fondo blanco, contorno tipo hoja y esquina doblada interna -->
  <path d="M12 0h36l12 12v66a2 2 0 0 1-2 2H12a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z" fill="#fff" stroke="#666" stroke-width="2"/>
  <path d="M48 0v12h12" fill="none" stroke="#666" stroke-width="2"/>

  <!-- Etiqueta EXCEL (más grande) -->
  <rect x="12" y="18" width="40" height="16" rx="3" ry="3" fill="#21A366"/>
  <text x="32" y="30" font-size="11" font-family="Arial, sans-serif" font-weight="bold" text-anchor="middle" fill="white">EXCEL</text>

  <!-- Flecha hacia arriba (grande, centrada en la parte inferior) -->
  <path d="M32 58 L22 68 H28 V76 H36 V68 H42 Z" fill="#21A366"/>
</svg>
  </a>

    <!-----------------------------------------FORMULARIO DE INGRESO DE REGISTROS DE ALUMNOS CON SUS DATOS---------------------------------->
    <h2 class="titulo-pagina">Ingreso de estudiantes</h2>


    <form accept-charset="UTF-8" action="../../backend/php_be/ingresos.php" method="POST" id="formulario" class="formulario">

      <div class="contenedor1">
        <h2>Datos del estudiante</h2>
        <div class="formulario__grupo" id="grupo__id_alumno">
          <label for="id_alumno" class="formulario__label">Código del estudiante:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="id_alumno" id="id_alumno" placeholder="Ejem: N356HKB" maxlength="7" required>
          </div>
          <p class="formulario__input-error">Este campo permite solo el formato de código del alumno asignado por el ministerio de educación. Por favor ingrese las letras en mayúscula,no ingrese símbolos o caracteres especiales y no deben ir espacios.</p>
        </div>

        <div class="formulario__grupo" id="grupo__nombres_alumno">
          <label for="nombres_alumno" class="formulario__label">Nombres del estudiante:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="nombres_alumno" id="nombres_alumno" placeholder="Ejem: José Martín" maxlength="50" required>
          </div>
          <p class="formulario__input-error">Debe colocar los nombres del alumno sin incluir: _-,-*/+, números o símbolos especiales.</p>
        </div>

        <div class="formulario__grupo" id="grupo__apellido1_alumno">
          <label for="apellido1_alumno" class="formulario__label">Primer apellido del estudiante::</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="apellido1_alumno" id="apellido1_alumno" placeholder="Ejem: Pérez" maxlength="20" required>
          </div>
          <p class="formulario__input-error">Debe colocar el primer apellido del alumno sin incluir: _-,-*/+, números o símbolos especiales</p>
        </div>

        <div class="formulario__grupo" id="grupo__apellido2_alumno">
          <label for="apellido2_alumno" class="formulario__label">Segundo Apellido del estudiante:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="apellido2_alumno" id="apellido2_alumno" placeholder="Ejem: Lémus" maxlength="15">
          </div>
          <p class="formulario__input-error">Debe colocar el segundo apellido del alumno sin incluir: _-,-*/+, números o símbolos especiales.</p>
        </div>


        <div class="formulario__grupo">
          <label for="genero" class="formulario__label">Género:</label>
          <div class="formulario__grupo-input">
            <select id="genero" name="genero" class="formulario__input" required>
              <option value="" disabled selected> Seleccione género </option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select><br>
          </div>
        </div>



        <div class="formulario__grupo" id="grupo__telefono_alumno">
          <label for="telefono_alumno" class="formulario__label">Teléfono:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="telefono_alumno" id="telefono_alumno" placeholder="Ej:502 9631-8523, 34565896, 3625-2596, 502 3536 2536." maxlength="15">
          </div>
          <p class="formulario__input-error">Este campo permite solo números, por favor no ingrese letras, símbolos o caracteres especiales.</p>
        </div>

        <div class="formulario__grupo" id="grupo__direccion_alumno">
          <label for="direccion_alumno" class="formulario__label">Dirección del estudiante:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input input-direccion"  name="direccion_alumno" id="direccion_alumno" placeholder="Ej:avenida, zona, barrio, municipio, departamento" maxlength="100" minlength="20" required>
          </div>
          <p class="formulario__input-error">Se recomienda que se agregue por lo menos el barrio, municipio y departamento.</p>
        </div>

        <div class="fila-horizontal">


          <!-- ===========================================ELEGIR LOS DATOS DE SECCÓN GRADO Y JORNADA ==============================================-->
          <!-----------------------------------------PARA ELEGIR UNA JORNADA. SE MUESTRAN LAS JORNADAS COMO LISTA-------------------------------------------->
        


          <div class="formulario__grupo">
            <label for="id_jornada" class="formulario__label">Jornada:</label>
            <div class="formulario__grupo-input">
              <select name="id_jornada" class="formulario__input" required>
                <option value="">Elige una jornada</option>
                <?php

                $resultado = $conexion->query("SELECT id_jornada, tipo_jornada FROM jornadas");

                if ($resultado && $resultado->num_rows > 0) {
                  while ($fila = $resultado->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($fila['id_jornada']) . "'>" . htmlspecialchars($fila['tipo_jornada']) . "</option>";
                  }
                } else {
                  echo "<option disabled>No hay jornadas registradas</option>";
                }
                ?>
              </select><br>
            </div>
          </div>
          <div class="formulario__grupo">
            <label for="id_seccion" class="formulario__label">Sección:</label>
            <div class="formulario__grupo-input">
              <select name="id_seccion" class="formulario__input">
                <option value="">Elige una sección</option>
                <?php

                //conectar con la tabla secciones de la base de datos
                $resultados = $conexion->query("SELECT id_seccion, nombre_seccion FROM secciones");
                while ($fila = $resultados->fetch_assoc()) {
                  echo "<option value='" . $fila['id_seccion'] . "'>" . htmlspecialchars($fila['nombre_seccion']) . "</option>";
                }
                ?>
              </select>
            </div>
          </div>

            <div class="formulario__grupo">
            <label for="id_grado" class="formulario__label">Grado:</label>
            <div class="formulario__grupo-input">
              <select name="id_grado" class="formulario__input select-grado"required>
                <option value="">Elige un grado</option>
                <?php

                //conectar con la tabla grados de la base de datos
                $resultadog = $conexion->query("SELECT id_grado, nombre_grado FROM grados");
                while ($fila = $resultadog->fetch_assoc()) {
                  echo "<option value='" . $fila['id_grado'] . "'>" . htmlspecialchars($fila['nombre_grado']) . "</option>";
                }
                ?>
              </select><br>
            </div>
          </div>
        </div><!--FIN DE LA FILA HORIZONTAL-->
      </div><!--FIN DEL CONTENEDOR 1-->
      <!-----------------------------------------------INICIO DE LOS DATOS DEL ENCARGADO------------------------------------------>
      <div class="contenedor2 ">
        <h2>Datos del encargado del estudiante</h2>

        <div class="formulario__grupo" id="grupo__cui_encargado">
          <label for="cui_encargado" class="formulario__label">CUI del encargado:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="cui_encargado" id="cui_encargado" placeholder="Ejem: 1236985423659" maxlength="13" required>
          </div>
          <p class="formulario__input-error">Este campo acepta solo números, por favor no ingrese letras, signos, espacios o símbolos especiales.</p>
        </div>

        <div class="formulario__grupo" id="grupo__nombres_encargado">
          <label for="nombres_encargado" class="formulario__label">Nombres del encargado:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="nombres_encargado" id="nombres_encargado" placeholder="Ejem: José Martín" maxlength="50" required>
          </div>
          <p class="formulario__input-error">Debe colocar al menos un nombre del encargado sin incluir: _-,-*/+, números o símbolos especiales.</p>
        </div>

        <div class="formulario__grupo" id="grupo__apellido1_encargado">
          <label for="apellido1_encargado" class="formulario__label">Primer apellido del encargado:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="apellido1_encargado" id="apellido1_encargado" placeholder="Ejem: Pérez" maxlength="15" required>
          </div>
          <p class="formulario__input-error">Debe colocar el primer apellido del encargado sin incluir: _-,-*/+, números o símbolos especiales.</p>
        </div>

        <div class="formulario__grupo" id="grupo__apellido2_encargado">
          <label for="apellido2_encargado" class="formulario__label">Segundo Apellido del encargado:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="apellido2_encargado" id="apellido2_encargado" placeholder="Ejem: López" maxlength="15">
          </div>
          <p class="formulario__input-error">Debe colocar el segundo apellido del encargado sin incluir: _-,-*/+, números o símbolos especiales.</p>
        </div>

        <div class="formulario__grupo" id="grupo__telefono_encargado">
          <label for="telefono_encargado" class="formulario__label">Teléfono del encargado:</label>
          <div class="formulario__grupo-input">
            <input type="text" class="formulario__input" name="telefono_encargado" id="telefono_encargado" placeholder="Ejem:5029631-8523, 3456-5896" maxlength="15" required>
          </div>
          <p class="formulario__input-error">Este campo permite solo números, por favor no ingrese letras, símbolos o caracteres especiales.</p>
        </div>

        <!-----------------------------------------PARA ELEGIREL TIPO DE PARENTESCO-------------------------------------------->
        <div class="formulario__grupo">
          <label for="id_parentesco" class="formulario__label">Parentesco con el estudiante:</label>
          <div class="formulario__grupo-input">
            <select id="id_parentesco" name="id_parentesco" class="formulario__input" required>
              <option value="" disabled selected> Seleccione parentesco </option>
              <option value="P-1">Mamá</option>
              <option value="P-2">Papá</option>
              <option value="P-3">Tío(a)</option>
              <option value="P-4">Hermano(a)</option>
              <option value="P-5">Abuelo(a)</option>
              <option value="P-6">Primo(a)</option>
              <option value="P-7">Tío(a) abuelo(a)</option>
            </select><br>
          </div>
        </div>
   <div class="formulario__grupo" id="grupo__direccion_encargado">
          <label for="direccion_encargado" class="formulario__label">Dirección del encargado:</label>
          <div class="formulario__grupo-textarea">
            <input type="text" class="formulario__input" name="direccion_encargado" id="direccion_encargado" placeholder="Ej:avenida, zona, barrio, municipio, departamento" maxlength="100" minlength="20" required>
          </div>
          <p class="formulario__input-error">Se recomienda que se agregue por lo menos el barrio, municipio y departamento.</p>
        </div>


      </div><!-- FIN DEL CONTENEDOR 2-->

      <div class="formulario__grupo formulario__grupo-btn-enviar">
        <button type="submit" class="formulario__btn">Enviar</button>
      </div>


    </form><!--FIN DEL FORMULARIO-->
  </main>
 <script>
document.addEventListener("DOMContentLoaded", () => {
  const bar = document.getElementById("notificationBar");
  if (!bar) return;

  // Mostrar
  bar.classList.add("show");

  // Auto-ocultar a los 5 s
  setTimeout(() => {
    bar.classList.remove("show");
  }, 5000);

  // (Opcional) Si quieres cerrar al hacer clic:
  bar.addEventListener("click", () => bar.classList.remove("show"));
});
</script>


  <script
    type="module"
    src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script
    nomodule
    src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="../js/scrip.js"></script>
  <script src="../js/validacion_alumnos.js"></script>
</body>
</html>