<?php
session_start();
isset($_SESSION['rol']);
include '../php_be/conexion.php';

// ───── Consulta de alumnos con la nueva estructura ───────────────────────────
$sql = "
  SELECT
    A.Id_Alumno,
    A.Nombres_Alumno,
    A.Apellido1_Alumno,
    A.Apellido2_Alumno,
    A.genero,
    G.Nombre_Grado,
    S.Nombre_Seccion,
    J.Tipo_Jornada,
    DA.Nombres_Encargado,
    DA.Apellido1_Encargado,
    DA.Telefono_Encargado
  FROM registro_alumnos RA
  JOIN alumnos         A  ON RA.Id_Alumno           = A.Id_Alumno
  JOIN detalle_alumnos DA ON RA.Id_Registro_Alumno  = DA.Id_Registro_Alumno
  JOIN jornadas        J  ON RA.Id_Jornada          = J.Id_Jornada
  JOIN grados          G  ON RA.Id_Grado            = G.Id_Grado
  LEFT JOIN secciones  S  ON RA.Id_Seccion          = S.Id_Seccion
";
$result = mysqli_query($conexion, $sql);

// ───── Acumular totales y distribución ───────────────────────────────────────
$totalAlumnos      = 0;
$totalHombres      = 0;
$totalMujeres      = 0;
$filas             = [];
$alumnosPorJornada = [];

if ($result) {
    while ($f = mysqli_fetch_assoc($result)) {
        $filas[] = $f;
        $totalAlumnos++;
        if ($f['genero'] === 'M') {
            $totalHombres++;
        } elseif ($f['genero'] === 'F') {
            $totalMujeres++;
        }
        $alumnosPorJornada[$f['Tipo_Jornada']] = 
            ($alumnosPorJornada[$f['Tipo_Jornada']] ?? 0) + 1;
    }
}
mysqli_close($conexion);

// ───── Cálculo de porcentajes ────────────────────────────────────────────────
$porcentajeHombres = $totalAlumnos
    ? round($totalHombres / $totalAlumnos * 100, 2)
    : 0;
$porcentajeMujeres = $totalAlumnos
    ? round($totalMujeres / $totalAlumnos * 100, 2)
    : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../frontend/css/veralumnos.css?v=1.0">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Listado de Alumnos</title>
</head>
<body>
  <h2>Resumen General</h2>
  <div class="dashboard">
    <div class="card"><h3><?= $totalAlumnos ?></h3><p>Total Alumnos</p></div>
    <div class="card"><h3><?= $totalHombres ?></h3><p>Hombres (<?= $porcentajeHombres ?>%)</p></div>
    <div class="card"><h3><?= $totalMujeres ?></h3><p>Mujeres (<?= $porcentajeMujeres ?>%)</p></div>
  </div>

  <div style="margin: 1rem 0;">
    <button onclick="window.open('estadistica.php', '_blank')" class="btn" id="btnVerEstadistica">Ver Estadística</button>
  </div>

  <h2>Listado de Alumnos Registrados</h2>
  <div class="filters">
    <label>Filtrar por Grado:<br>
      <select id="filtroGrado"><option value="">Todos</option></select>
    </label>
    <label>Filtrar por Sección:<br>
      <select id="filtroSeccion"><option value="">Todos</option></select>
    </label>
    <label>Filtrar por Jornada:<br>
      <select id="filtroJornada"><option value="">Todos</option></select>
    </label>
    <button id="limpiarFiltros" class="btn_limpiar">Limpiar Filtros</button>
  </div>

  <table id="tablaAlumnos">
    <thead>
      <tr>
        <th>Código</th><th>Nombre</th><th>Grado</th><th>Sección</th><th>Jornada</th>
        <th>Encargado</th><th>Teléfono</th><th>Info</th>
        <?php if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'admin2'): ?>
        <th>Acciones</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
<?php
    $dirTemp    = __DIR__ . '/../../backend/temp/';
    $dirMassive = __DIR__ . '/../../backend/php_im+/qrs/';
    foreach ($filas as $fila):
      $cui               = htmlspecialchars($fila['Id_Alumno']);
      $nombreAlumno      = htmlspecialchars("{$fila['Nombres_Alumno']} {$fila['Apellido1_Alumno']} {$fila['Apellido2_Alumno']}");
      $grado             = htmlspecialchars($fila['Nombre_Grado']);
      $seccion           = htmlspecialchars($fila['Nombre_Seccion']);
      $jornada           = htmlspecialchars($fila['Tipo_Jornada']);
      $nombreEncargado   = htmlspecialchars("{$fila['Nombres_Encargado']} {$fila['Apellido1_Encargado']}");
      $telefonoEncargado = htmlspecialchars($fila['Telefono_Encargado']);

      // Rutas del QR
      $fnTemp    = "qr_{$cui}.png";
      $fnMassive = "{$cui}.png";
      $pathTemp    = $dirTemp    . $fnTemp;
      $pathMassive = $dirMassive . $fnMassive;

      if (file_exists($pathTemp)) {
        $ruta_qr = "../temp/" . $fnTemp;
      } elseif (file_exists($pathMassive)) {
        $ruta_qr = "../php_im+/qrs/" . $fnMassive;
      } else {
        $ruta_qr = "../../frontend/img/qr-placeholder.png";
      }
  ?>
    <tr data-grado="<?= $grado ?>" data-seccion="<?= $seccion ?>" data-jornada="<?= $jornada ?>">
      <td><?= $cui ?></td>
      <td><?= $nombreAlumno ?></td>
      <td><?= $grado ?></td>
      <td><?= $seccion ?></td>
      <td><?= $jornada ?></td>
      <td><?= $nombreEncargado ?></td>
      <td><?= $telefonoEncargado ?></td>
      <td>
        <button onclick="verMas(
          '<?= $cui ?>',
          '<?= $nombreAlumno ?>',
          '<?= $grado ?>',
          '<?= $seccion ?>',
          '<?= $jornada ?>',
          '<?= $nombreEncargado ?>',
          '<?= $telefonoEncargado ?>',
          '<?= $ruta_qr ?>'
        )">Ver más</button>
      </td>
      <td>
      <?php if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'admin2'): ?>
      <button class="btn-edit" data-id="<?= $cui ?>">✏️</button>
      <button class="btn-delete" data-id="<?= $cui ?>">🗑️</button>
      <?php endif; ?>
    </td>
    </tr>
  <?php endforeach; ?>
    </tbody>
  </table>

 <div id="paginationControls" style="text-align:center; margin:1rem 0;"></div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const allRows    = Array.from(document.querySelectorAll('#tablaAlumnos tbody tr'));
  const perPage    = 10;
  const controls   = document.getElementById('paginationControls');
  const selGrado   = document.getElementById('filtroGrado');
  const selSeccion = document.getElementById('filtroSeccion');
  const selJornada = document.getElementById('filtroJornada');
  const btnClear   = document.getElementById('limpiarFiltros');

  // ─── 0) Función para normalizar cadenas ───────────────────────────────────
  function normalize(str = '') {
    return str
      .normalize('NFD')                   // separa letras de sus tildes
      .replace(/[\u0300-\u036f]/g, '')    // quita las marcas de tilde
      .trim()
      .toLowerCase();
  }

  // ─── 1) Filtrado con includes() y normalize() ────────────────────────────
  function getFilteredRows() {
    const g = normalize(selGrado.value);
    const s = normalize(selSeccion.value);
    const j = normalize(selJornada.value);

    return allRows.filter(row => {
      const rg = normalize(row.dataset.grado);
      const rs = normalize(row.dataset.seccion);
      const rj = normalize(row.dataset.jornada);

      const okG = !g || rg.includes(g);
      const okS = !s || rs.includes(s);
      const okJ = !j || rj.includes(j);

      return okG && okS && okJ;
    });
  }

  // ─── 2) Mostrar una página de resultados ─────────────────────────────────
  function showPage(page, rowsArr) {
    const start = (page - 1) * perPage;
    const end   = start + perPage;

    allRows.forEach(r => r.style.display = 'none');
    rowsArr.slice(start, end).forEach(r => r.style.display = '');

    Array.from(controls.children).forEach((btn, idx) => {
      btn.classList.toggle('active', idx + 1 === page);
    });

    document
      .getElementById('tablaAlumnos')
      .scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  // ─── 3) Renderizar los botones de paginación ──────────────────────────────
  function renderPagination(rowsArr) {
    controls.innerHTML = '';
    const pageCount = Math.ceil(rowsArr.length / perPage) || 1;

    for (let i = 1; i <= pageCount; i++) {
      const btn = document.createElement('button');
      btn.textContent = i;
      btn.addEventListener('click', () => showPage(i, rowsArr));
      controls.appendChild(btn);
    }
  }

  // ─── 4) Llenar los select dinámicamente ──────────────────────────────────
  (function fillSelects() {
    // Limpio y agrego la opción "Todos"
    selGrado.innerHTML   = '<option value="">Todos</option>';
    selSeccion.innerHTML = '<option value="">Todos</option>';
    selJornada.innerHTML = '<option value="">Todos</option>';

    const grados    = new Set();
    const secciones = new Set();
    const jornadas  = new Set();

    allRows.forEach(r => {
      grados.add(r.dataset.grado);
      secciones.add(r.dataset.seccion);
      jornadas.add(r.dataset.jornada);
    });

    grados.forEach(g => {
      const opt = document.createElement('option');
      opt.value       = g;
      opt.textContent = g;
      selGrado.appendChild(opt);
    });
    secciones.forEach(s => {
      const opt = document.createElement('option');
      opt.value       = s;
      opt.textContent = s;
      selSeccion.appendChild(opt);
    });
    jornadas.forEach(j => {
      const opt = document.createElement('option');
      opt.value       = j;
      opt.textContent = j;
      selJornada.appendChild(opt);
    });
  })();

  // ─── 5) Listeners para filtros y botón "Limpiar" ──────────────────────────
  [selGrado, selSeccion, selJornada].forEach(sel =>
    sel.addEventListener('change', () => {
      const filtered = getFilteredRows();
      renderPagination(filtered);
      showPage(1, filtered);
    })
  );

  btnClear.addEventListener('click', () => {
    selGrado.value = '';
    selSeccion.value = '';
    selJornada.value = '';
    const filtered = getFilteredRows();
    renderPagination(filtered);
    showPage(1, filtered);
  });

  // ─── 6) Inicialización ────────────────────────────────────────────────────
  const initialRows = getFilteredRows();
  renderPagination(initialRows);
  showPage(1, initialRows);
});
</script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const isDark = localStorage.getItem('theme') === 'dark';
    if (isDark) {
      document.body.classList.add('dark-mode');
    }
  });
</script>
<script>
// Función Ver Más
function verMas(cui, nombre, grado, seccion, jornada, encargado, telefono, ruta_qr) {
  const ventana = window.open("", "DetalleAlumno", "width=500,height=700");
  ventana.document.write(`
    <html><head><title>Detalle del Alumno</title></head>
    <body style="font-family: Arial; text-align: center;">
      <h2 style="color: #007bff;">Información del Alumno</h2>
      <p><strong>Codigo del Estudiante:</strong> ${cui}</p>
      <p><strong>Nombre:</strong> ${nombre}</p>
      <p><strong>Grado:</strong> ${grado}</p>
      <p><strong>Sección:</strong> ${seccion}</p>
      <p><strong>Jornada:</strong> ${jornada}</p>
      <p><strong>Encargado:</strong> ${encargado}</p>
      <p><strong>Teléfono:</strong> ${telefono}</p>
      <h3>QR del Alumno</h3>
      <img src="${ruta_qr}" width="200"><br><br>
      <a href="${ruta_qr}" download="QR_${cui}.png" style="
          padding:8px 16px;
          background:#007bff;
          color:white;
          text-decoration:none;
          border-radius:5px;
        ">Descargar QR</a><br><br>
      <button onclick="window.close()" style="
          padding:8px 16px;
          background:#007bff;
          color:white;
          border:none;
          border-radius:5px;
        ">Cerrar</button>
    </body></html>
  `);
}
</script>

<script>
  // ELIMINAR
document.querySelectorAll('.btn-delete').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.id;
    if (!confirm(`¿Eliminar alumno ${id}? Esta operación es irreversible.`)) return;

    fetch('eliminar_alumno.php', {
      method: 'POST',
      headers: { 'Content-Type':'application/json' },
      body: JSON.stringify({ idAlumno: id })
    })
    .then(res => res.json())
    .then(json => {
      if (json.success) {
        alert(json.message);
        // opcional: recargar tabla o quitar la fila:
        btn.closest('tr').remove();
      } else {
        alert('Error: ' + json.message);
      }
    })
    .catch(err => {
      console.error(err);
      alert('No se pudo conectar al servidor: ' + err.message);
    });
  });
});

// EDITAR: redirige a un formulario de edición
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.id;
    window.location.href = `../../frontend/html/editar_alumno.php?id=${encodeURIComponent(id)}`;
  });
});
</script>