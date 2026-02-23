<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['rol'])) {
    header('Location: ../html/menu.php');
    exit;
}
include __DIR__ . '/../../backend/php_rep/reportes.php';
date_default_timezone_set('America/Guatemala');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reporte de Alumnos Ausentes</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/ausentes.css?=v1.0">
  <style>
    .cards { display:flex; gap:1rem; margin:2rem auto; max-width:1000px; }
    .card  { flex:1; background:#f5f5f5; padding:1rem; border-radius:.5rem; text-align:center; }
    .filters { display:flex; gap:1rem; flex-wrap:wrap; justify-content:center; margin-bottom:2rem; }
    .filters label { display:flex; flex-direction:column; font-size:.9rem; }
    table { width:90%; margin:auto; border-collapse:collapse; text-align:left; }
    th, td { padding:.5rem; border:1px solid #ccc; }
    thead tr { background:#eee; }
    .btn { background:#000; color:#fff; padding:.5rem 1rem; border-radius:.25rem; text-decoration:none; }
 </style>
 <script src="https://animatedicons.co/scripts/embed-animated-icons.js"></script>
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
<?php endif;?>
              <li>
                <a href="Registros.php">
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
                <a id="home"  href="Reportes.php"> 
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
<main class="dark-mode">
  <header style="display:flex; justify-content:space-between; align-items:center;">
    <h1>Reporte de Alumnos Ausentes</h1>
    <a href="../../backend/php_scanner/export_pdf.php?<?= http_build_query([
          'fecha'   => $fechaSel,
          'grado'   => $gradoSel,
          'seccion' => $seccionSel,
          'jornada' => $jornadaSel
        ]) ?>"
       class="btn"
     style="display: flex; flex-direction:row; align-items:center;"> 
<animated-icons
  src="https://animatedicons.co/get-icon?name=download&style=minimalistic&token=c041d11b-9e1b-4f4b-b4fe-782ea93cace6"
  trigger="hover"
  attributes='{"variationThumbColour":"#536DFE","variationName":"Two Tone","variationNumber":2,"numberOfGroups":2,"backgroundIsGroup":false,"strokeWidth":1,"defaultColours":{"group-1":"#FFFFFF00","group-2":"#12A178FF","background":"#FFFFFFFF"}}'
  height="35"
  width="35"
></animated-icons> Exportar a PDF </a>
  </header>


    <div class="cards">
      <div class="card"><h2><?= $total ?></h2><p>Total Alumnos</p></div>
      <div class="card"><h2><?= $presentes ?></h2><p>Presentes</p></div>
      <div class="card"><h2><?= $ausentes ?></h2><p>Ausentes</p></div>
      <div class="card"><h2><?= $porcentaje ?>%</h2><p>% Asistencia</p></div>
    </div>

    <form id="filtersForm" method="get" class="filters">
  <label>
    Fecha:<br>
    <input 
      type="date" 
      name="fecha" 
      value="<?= htmlspecialchars($fechaSel) ?>" 
      onchange="this.form.submit()"
    >
  </label>

  <label>
    Jornada:<br>
    <select name="jornada" onchange="this.form.submit()">
      <option value="">Todas</option>
      <?php while ($j = $jornadasList->fetch_assoc()): ?>
        <option 
          value="<?= $j['Id_Jornada'] ?>"
          <?= $j['Id_Jornada'] === $jornadaSel ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($j['Tipo_Jornada']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </label>

  <label>
    Grado:<br>
    <select name="grado" onchange="this.form.submit()">
      <option value="">Todos</option>
      <?php while ($g = $gradosList->fetch_assoc()): ?>
        <option 
          value="<?= $g['Id_Grado'] ?>"
          <?= $g['Id_Grado'] === $gradoSel ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($g['Nombre_Grado']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </label>

  <label>
    Sección:<br>
    <select name="seccion" onchange="this.form.submit()">
      <option value="">Todas</option>
      <?php while ($s = $seccionesList->fetch_assoc()): ?>
        <option 
          value="<?= $s['Id_Seccion'] ?>"
          <?= $s['Id_Seccion'] === $seccionSel ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($s['Nombre_Seccion']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </label>

  <!-- Botón sólo para limpiar -->
  <button 
    type="button" 
    class="btn_limpiar" 
    onclick="clearFilters()"
  >
    Limpiar filtros</button>
</form>

<script>
function clearFilters() {
  const form = document.getElementById('filtersForm');
  // Asigna vacío a cada campo
  form.elements['fecha'].value    = '';
  form.elements['jornada'].value  = '';
  form.elements['grado'].value    = '';
  form.elements['seccion'].value  = '';
  // Y vuelve a enviar
  form.submit();
}
</script>
<table>
     <thead>
      <tr>
        <th>Código</th>
        <th>Alumno</th>
        <th>Jornada</th>
        <th>Grado</th>
        <th>Sección</th>
        <th>Encargado</th>
        <th>Teléfono</th>
        <th>Justificación</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($res2->num_rows): ?>
        <?php while ($f = $res2->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($f['Id_Alumno']) ?></td>
            <td><?= htmlspecialchars($f['Alumno']) ?></td>
            <td><?= htmlspecialchars($f['Jornada']) ?></td>
            <td><?= htmlspecialchars($f['Grado']) ?></td>
            <td><?= htmlspecialchars($f['Seccion']) ?></td>
            <td>
              <?= htmlspecialchars("{$f['Nombres_Encargado']} {$f['Apellido1_Encargado']} {$f['Apellido2_Encargado']}") ?>
            </td>
            <?php
  // 1) Limpia el teléfono (solo dígitos) y añade código de país
  $telefono = preg_replace('/\D+/', '', $f['Telefono_Encargado']);  // p.ej. “50212345678”

  // 2) Prepara el texto que quieras enviar
$nombreEncargado = "{$f['Nombres_Encargado']} {$f['Apellido1_Encargado']} {$f['Apellido2_Encargado']}";
$textoPlain = sprintf(
  'Estimad@ %s, le informamos desde el Colegio Mixto Kairos que el alumno %s no ha asistio al establecimiento hoy %s. Por favor, comuníquese con la institución para justificar la inasistencia.',
  $nombreEncargado,
  $f['Alumno'],
  $fechaSel
);

  // 3) Codifícalo para URL
  $textoEncoded = rawurlencode($textoPlain);

  // 4) URL para web.whatsapp.com (es universal, funciona en móvil y escritorio)
  $linkWhats = "https://api.whatsapp.com/send?phone={$telefono}&text={$textoEncoded}";
?>
           <td>
  <a 
    href="<?= $linkWhats ?>" 
    target="_blank" 
    rel="noopener noreferrer"
    style="color:#25D366; text-decoration:none;"
    title="Enviar WhatsApp"
  >
    <?= htmlspecialchars($f['Telefono_Encargado']) ?>
  </a>
</td>
            <!-- Columna de Justificación -->
<td>
  <textarea
    data-asid     ="<?= $f['Id_Asistencia']   ?? '' ?>"
    data-detalle  ="<?= $f['Id_Detalle']      ?>"
    class="justif-text"
    rows="2"
  ><?= htmlspecialchars($f['Justificacion'] ?? '') ?></textarea>
  <button
    type="button"
    class="justif-save"
    data-asid    ="<?= $f['Id_Asistencia']   ?? '' ?>"
    data-detalle ="<?= $f['Id_Detalle']      ?>"
  >Guardar</button>
</td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" style="text-align:center;">
            ¡Ningún alumno ausente con esos filtros!
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
    <!-- Controles de paginación -->
  <div id="paginationControlsReport" style="text-align:center; margin:1rem 0;"></div>
</main>

<!-- Script para enviar la justificación -->
<script>
document.querySelectorAll('.justif-save').forEach(btn => {
  btn.addEventListener('click', () => {
    const idAsis    = btn.dataset.asid;
    const idDetalle = btn.dataset.detalle;
    const justif    = document.querySelector(`textarea[data-detalle="${idDetalle}"]`).value.trim();

    fetch('../../backend/php_rep/save_justif.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ idAsis, idDetalle, justif })
    })
    .then(response => {
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      return response.json();
    })
    .then(res => {
      if (res.success) {
        // 1) Marcar el botón como “saved”
        btn.classList.add('saved');
        btn.textContent = 'Guardado';

        // 2) Crear o recuperar el toast
        let toast = btn.parentElement.querySelector('.save-toast');
        if (!toast) {
          toast = document.createElement('span');
          toast.className = 'save-toast';
          btn.parentElement.appendChild(toast);
        }
        toast.textContent = res.message || 'Justificación guardada';
        // 3) Mostrar y luego ocultar
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => toast.classList.remove('show'),1000 );

           // 4) Limpiar el textarea relacionado
        const textarea = document.querySelector(`textarea[data-detalle="${idDetalle}"]`);// Selecciona el textarea
        if (textarea) {// Si existe el textarea
          textarea.value = '';// Limpiar el contenido
          textarea.style.height = 'auto'; // restablece la altura ya que este se extiende si el texto es grande
        }

      } else {
        alert('Error: ' + res.message);
      }
    })
    .catch(err => {
      console.error(err);
      alert('No se pudo conectar al servidor: ' + err.message);
    });
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const rows     = Array.from(document.querySelectorAll('main table tbody tr'));
  const perPage  = 10;  
  const controls = document.getElementById('paginationControlsReport');
  const pageCount = Math.ceil(rows.length / perPage);

  function showPage(page, shouldScroll = false) {
    // 1) Mostrar/ocultar filas
    const start = (page - 1) * perPage;
    const end   = start + perPage;
    rows.forEach((row, i) => {
      row.style.display = (i >= start && i < end) ? '' : 'none';
    });

    // 2) Marcar el botón activo
    Array.from(controls.children).forEach((btn, idx) => {
      btn.classList.toggle('active', idx + 1 === page);
    });

    // 3) Hacer scroll (solo si viene de un clic)
    if (shouldScroll) {
      document.querySelector('main table')
              .scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  // 4) Generar botones con evento que pasa shouldScroll = true
  for (let i = 1; i <= pageCount; i++) {
    const btn = document.createElement('button');
    btn.textContent = i;
    btn.addEventListener('click', () => showPage(i, true));
    controls.appendChild(btn);
  }

  // 5) Mostrar la página 1 SIN scroll inicial
  if (pageCount > 0) showPage(1, false);
});
</script>
<script>
document.querySelectorAll('.justif-text').forEach(textarea => {
  // Se expande en cada entrada de texto
  textarea.addEventListener('input', () => {
    textarea.style.height = 'auto'; // Reset
    textarea.style.height = textarea.scrollHeight + 'px'; // Ajusta al contenido
  });
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