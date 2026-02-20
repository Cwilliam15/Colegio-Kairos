<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['rol'])) {
    header('Location: ../html/menu.php');
    exit;
}

// 1) Incluye el logger y la conexión (ajusta rutas si es necesario)
require_once __DIR__ . '/../../backend/php/logger.php';
include     __DIR__ . '/../../backend/php_be/conexion.php';

if (!$conexion) {
    die("❌ Error al conectar con la base de datos: " . $conexion->connect_error);
}

// 2) Procesa eliminaciones y registra en logs

// Eliminar Jornada
if (isset($_GET['Id_Jornada'])) {
    $id = $conexion->real_escape_string($_GET['Id_Jornada']);

    $check = $conexion->query("SELECT COUNT(*) AS total FROM registro_alumnos WHERE Id_Jornada = '$id'");
    if (!$check) {
        die("❌ Error al verificar registros: " . $conexion->error);
    }

    $row = $check->fetch_assoc();

    if ($row['total'] > 0) {
        $_SESSION['mensaje'] = "⚠️ No se puede eliminar la jornada. Está asignada a uno o más alumnos.";
        $_SESSION['tipo'] = "mensaje_error";
    } else {
        if ($conexion->query("DELETE FROM jornadas WHERE Id_Jornada = '$id'")) {
            registrarLog("Se eliminó la jornada con ID: $id");
            $_SESSION['mensaje'] = "✅ Jornada eliminada correctamente.";
            $_SESSION['tipo'] = "mensaje_exito";
        } else {
            $_SESSION['mensaje'] = "❌ Error al eliminar jornada: " . $conexion->error;
            $_SESSION['tipo'] = "mensaje_error";
        }
    }

    header('Location: ingreso_JSG.php');
    exit;
}
// Eliminar Sección
if (isset($_GET['Id_Seccion'])) {
    $ids = $conexion->real_escape_string($_GET['Id_Seccion']);

    $check = $conexion->query("SELECT COUNT(*) AS total FROM registro_alumnos WHERE Id_Seccion = '$ids'");
    if (!$check) {
        die("❌ Error al verificar registros de sección: " . $conexion->error);
    }

    $row = $check->fetch_assoc();

    if ($row['total'] > 0) {
        $_SESSION['mensaje'] = "⚠️ No se puede eliminar la sección. Está asignada a uno o más alumnos.";
        $_SESSION['tipo'] = "mensaje_error";
    } else {
        if ($conexion->query("DELETE FROM secciones WHERE Id_Seccion = '$ids'")) {
            registrarLog("Se eliminó la sección con ID: $ids");
            $_SESSION['mensaje'] = "✅ Sección eliminada correctamente.";
            $_SESSION['tipo'] = "mensaje_exito";
        } else {
            $_SESSION['mensaje'] = "❌ Error al eliminar sección: " . $conexion->error;
            $_SESSION['tipo'] = "mensaje_error";
        }
    }

    header('Location: ingreso_JSG.php');
    exit;
}

// Eliminar Grado
if (isset($_GET['Id_Grado'])) {
    $idg = $conexion->real_escape_string($_GET['Id_Grado']);

    $check = $conexion->query("SELECT COUNT(*) AS total FROM registro_alumnos WHERE Id_Grado = '$idg'");
    if (!$check) {
        die("❌ Error al verificar registros de grado: " . $conexion->error);
    }

    $row = $check->fetch_assoc();

    if ($row['total'] > 0) {
        $_SESSION['mensaje'] = "⚠️ No se puede eliminar el grado. Está asignado a uno o más alumnos.";
        $_SESSION['tipo'] = "mensaje_error";
    } else {
        if ($conexion->query("DELETE FROM grados WHERE Id_Grado = '$idg'")) {
            registrarLog("Se eliminó el grado con ID: $idg");
            $_SESSION['mensaje'] = "✅ Grado eliminado correctamente.";
            $_SESSION['tipo'] = "mensaje_exito";
        } else {
            $_SESSION['mensaje'] = "❌ Error al eliminar grado: " . $conexion->error;
            $_SESSION['tipo'] = "mensaje_error";
        }
    }

    header('Location: ingreso_JSG.php');
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
    <link rel="stylesheet" href="../css/clases.css?=v6.0">
     <link rel="stylesheet"href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
     

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
                    <a id="home"  href="ingreso_JSG.php" title="Ingresar Clases">
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
                <a href="admin.php" title="Gestionar Usuarios">
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
    <!-- Mensaje de resultado -->
   <?php if (!empty($_SESSION['mensaje'])): ?>
  <div class="alert <?= $_SESSION['tipo'] ?>">
    <?= $_SESSION['mensaje'] ?>
  </div>
  <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
<?php endif; ?>
    <h2>Ingreso de Clases</h2>

    <!-- Formulario de alta -->
    <div  class="forms">
      <div class="form-box">
        <h3>Agregar Jornada</h3>
        <form action="../../backend/php_be/jornadas.php" method="POST">
         <div id="grupo__tipo_jornada" class="formulario__grupo">
  <input
    type="text"
    name="tipo_jornada"
    placeholder="Ejem: Matutina"
    required
  />
  <span class="formulario__input-error">
    Solo letras y espacios (3–13 caracteres).
  </span>
</div>
          <button type="submit">Guardar Jornada</button>
        </form>
      </div>

      <div class="form-box">
        <h3>Agregar Sección</h3>
        <form action="https://asistencia.colegiokairos.edu.gt/backend/php_be/secciones.php" method="POST">
          <div id="grupo__nombre_seccion" class="formulario__grupo">
          <input type="text" name="nombre_seccion" placeholder="Ejem: A1" required>
          <span class="formulario__input-error">
            Solo letras o números, sin espacios.
          </span>
          </div>
          <button type="submit">Guardar Sección</button>
        </form>
      </div>

      <div class="form-box">
        <h3>Agregar Grado</h3>
        <form action="../../backend/php_be/grados.php" method="POST">
          <div id="grupo__nombre_grado" class="formulario__grupo">
          <input type="text" name="nombre_grado" placeholder="Ejem: Primero Básico" required>
          <span class="formulario__input-error">
            Solo letras y espacios (3–70 caracteres).
          </span>
          </div>
          <button type="submit">Guardar Grado</button>
        </form>
      </div>
    </div>
<div class="tables">
      <!-- Jornadas -->
      <section class="table-box">
        <h3>Jornadas Registradas</h3>
        <?php
$res = $conexion->query(
    "SELECT *
       FROM jornadas
   ORDER BY CAST(SUBSTRING(id_jornada, 3) AS UNSIGNED) ASC"
);        if ($res && $res->num_rows):
        ?>
        <!-- Se agrega el id="tabla-jornadas" para que DataTables lo identifique exactamente -->
        <table id="tabla-jornadas">
          <thead>
            <tr><th>ID</th><th>Jornada</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            <?php while ($f = $res->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($f['Id_Jornada']) ?></td>
              <td><?= htmlspecialchars($f['Tipo_Jornada']) ?></td>
              <td>
                <a href="ingreso_JSG.php?Id_Jornada=<?= urlencode($f['Id_Jornada']) ?>"
                   class="btn-delete"
                   onclick="return confirm('¿Eliminar este registro?')">
                  Eliminar
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p>No hay jornadas registradas.</p>
        <?php endif; ?>
      </section>

      <!-- Secciones -->
      <section class="table-box">
        <h3>Secciones Registradas</h3>
        <?php
$res = $conexion->query(
    "SELECT * 
       FROM secciones
   ORDER BY CAST(SUBSTRING(id_seccion, 3) AS UNSIGNED) ASC"
);
        if ($res && $res->num_rows):
        ?>
        <!-- Se agrega el id="tabla-secciones" para que DataTables lo identifique exactamente -->
        <table id="tabla-secciones">
          <thead>
            <tr><th>ID</th><th>Sección</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            <?php while ($f = $res->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($f['Id_Seccion']) ?></td>
              <td><?= htmlspecialchars($f['Nombre_Seccion']) ?></td>
              <td>
                 <a href="ingreso_JSG.php?Id_Seccion=<?= urlencode($f['Id_Seccion']) ?>"
                   class="btn-delete"
                   onclick="return confirm('¿Eliminar este registro?')">
                  Eliminar
                </a>

              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p>No hay secciones registradas.</p>
        <?php endif; ?>
      </section>

      <!-- Grados -->
      <section class="table-box">
        <h3>Grados Registrados</h3>
        <?php
        $res = $conexion->query("
  SELECT *
    FROM grados
   ORDER BY CAST(SUBSTRING(id_grado, " . (strlen("G-")+1) . ") AS UNSIGNED) ASC
");
        if ($res && $res->num_rows):
        ?>
        <!-- Se agrega el id="tabla-grados" para que DataTables lo identifique exactamente -->
        <table id="tabla-grados">
          <thead>
            <tr><th>ID</th><th>Grado</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            <?php while ($f = $res->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($f['Id_Grado']) ?></td>
              <td><?= htmlspecialchars($f['Nombre_Grado']) ?></td>
              <td>
                <a href="ingreso_JSG.php?Id_Grado=<?= urlencode($f['Id_Grado']) ?>"
                   class="btn-delete"
                   onclick="return confirm('¿Eliminar este registro?')">
                  Eliminar
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p>No hay grados registrados.</p>
        <?php endif; ?>
      </section>
    </div>
  </main>
    <script>
  const alerta = document.getElementById("alerta-sistema");
  if (alerta) {
    setTimeout(() => {
      alerta.style.transition = "opacity 0.5s ease";
      alerta.style.opacity = "0";
      setTimeout(() => alerta.remove(), 500);
    }, 5000);
  }
</script>
    <script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../js/scrip.js"></script>
    <script src="../js/validacionesJSG.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- =====================================================================
         DATATABLES — Inicialización de las tres tablas
         Se ejecuta cuando el DOM está completamente cargado (document.ready).
    ====================================================================== -->
    <script>
    $(document).ready(function () {

        // Configuración compartida para las tres tablas
        // Se define como objeto para no repetir código
        var opcionesComunes = {

            // language: carga la traducción al español desde el CDN oficial de DataTables
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },

            // pageLength: cantidad de filas visibles por defecto al cargar la página
            // Se establece en 10 como solicitado
            pageLength: 10,

            // lengthMenu: opciones disponibles en el selector "Mostrar X registros"
            // El usuario puede cambiar entre 10, 20, 30, 50 o ver todos (All)
            lengthMenu: [
                [10, 20, 30, 50, -1],          // Valores numéricos (-1 = todos)
                [10, 20, 30, 50, 'Todos']       // Etiquetas que se muestran en el select
            ],

            // ordering: permite ordenar columnas al hacer clic en el encabezado
            // Se mantiene true para no quitar funcionalidad
            ordering: true,

            // searching: muestra el campo de búsqueda rápida en tiempo real
            // Se mantiene true para añadir valor sin afectar el diseño
            searching: true,

            // columnDefs: configuración por columna
            // Se desactiva el ordenamiento en la columna "Acciones" (última columna, índice 2)
            // para evitar que la columna del botón "Eliminar" sea ordenable sin sentido
            columnDefs: [
                { orderable: false, targets: -1 }   // -1 = última columna (Acciones)
            ]
        };

        // Inicializa DataTables en la tabla de Jornadas usando el id asignado en el HTML
        $('#tabla-jornadas').DataTable(opcionesComunes);

        // Inicializa DataTables en la tabla de Secciones usando el id asignado en el HTML
        $('#tabla-secciones').DataTable(opcionesComunes);

        // Inicializa DataTables en la tabla de Grados usando el id asignado en el HTML
        $('#tabla-grados').DataTable(opcionesComunes);

    }); // Fin de document.ready
    </script>

</body>
</html>