<?php
// estadistica.php
session_start();
include '../php_be/conexion.php';

// — 1) Totales para las tarjetas —
// Total de Grados
$res = mysqli_query($conexion, "SELECT COUNT(*) AS cnt FROM grados");
$totalGrados = mysqli_fetch_assoc($res)['cnt'];
// Total de Secciones
$res = mysqli_query($conexion, "SELECT COUNT(*) AS cnt FROM secciones");
$totalSecciones = mysqli_fetch_assoc($res)['cnt'];
// Total de Alumnos
$res = mysqli_query($conexion, "SELECT COUNT(*) AS cnt FROM alumnos");
$totalAlumnos = mysqli_fetch_assoc($res)['cnt'];
// Asistencias Hoy: presentes vs ausentes (Registro_Asistencia = 1 presente)
$res = mysqli_query($conexion, "
    SELECT
      SUM(CASE WHEN Registro_Asistencia=1 THEN 1 ELSE 0 END) AS presentes,
      SUM(CASE WHEN Registro_Asistencia=0 THEN 1 ELSE 0 END) AS ausentes
    FROM asistencias
    WHERE Fecha_Registro = CURDATE()
");
$row = mysqli_fetch_assoc($res);
$asistHoyPres = (int)$row['presentes'];
$asistHoyAus  = (int)$row['ausentes'];
$totalHoy     = $asistHoyPres + $asistHoyAus;
$porcAsistHoy = $totalHoy
  ? round($asistHoyPres / $totalHoy * 100, 1)
  : 0;
// Porcentaje de mujeres vs hombres (global)
$res = mysqli_query($conexion, "
    SELECT genero, COUNT(*) AS cnt
    FROM alumnos
    GROUP BY genero
");
$cntM = $cntF = 0;
while($r = mysqli_fetch_assoc($res)){
  if($r['genero']==='M') $cntM = $r['cnt'];
  if($r['genero']==='F') $cntF = $r['cnt'];
}
$porcMujeres = ($cntM+$cntF) 
  ? round($cntF/($cntM+$cntF)*100,1) 
  : 0;

// Promedio asistencia últimos 7 días
$res = mysqli_query($conexion, "
  SELECT Fecha_Registro,
    SUM(CASE WHEN Registro_Asistencia=1 THEN 1 ELSE 0 END) AS pres
  FROM asistencias
  WHERE Fecha_Registro >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
  GROUP BY Fecha_Registro
");
$total7 = $sumPres7 = 0;
while($r = mysqli_fetch_assoc($res)){
  $sumPres7 += $r['pres'];
  $total7++;
}
$prom7 = $total7 ? round($sumPres7/$total7,1) : 0;

// — 2) Datos para gráficos —
// Paleta vibrante
$palette = [
  '#FF6633','#FF33FF','#33FF57','#33FFF5',
  '#FF3333','#3333FF','#F4FF33','#FF33A8'
];

// 2a) Alumnos por grado
$res = mysqli_query($conexion, "
  SELECT G.Nombre_Grado AS label, COUNT(*) AS value
  FROM registro_alumnos RA
  JOIN grados G ON RA.Id_Grado = G.Id_Grado
  GROUP BY G.Nombre_Grado
");
$gradoLabels = $gradoData = [];
while($r = mysqli_fetch_assoc($res)){
  // Por esta versión abreviada:
$label = strlen($r['label']) > 30 
    ? substr($r['label'], 0, 27) . '...' 
    : $r['label'];
$gradoLabels[] = $label;
  $gradoData[]   = (int)$r['value'];
}

// 2b) Alumnos por sección
$res = mysqli_query($conexion, "
  SELECT S.Nombre_Seccion AS label, COUNT(*) AS value
  FROM registro_alumnos RA
  JOIN secciones S ON RA.Id_Seccion = S.Id_Seccion
  GROUP BY S.Nombre_Seccion
");
$secLabels = $secData = [];
while($r = mysqli_fetch_assoc($res)){
  $secLabels[] = $r['label'];
  $secData[]   = (int)$r['value'];
}

// 2c) Asistencias por jornada (hoy)
$res = mysqli_query($conexion, "
  SELECT J.Tipo_Jornada AS label,
    SUM(CASE WHEN A.Registro_Asistencia=1 THEN 1 ELSE 0 END) AS presentes
  FROM asistencias A
  JOIN detalle_alumnos DA ON A.Id_Detalle = DA.Id_Detalle
  JOIN registro_alumnos RA ON DA.Id_Registro_Alumno = RA.Id_Registro_Alumno
  JOIN jornadas J ON RA.Id_Jornada = J.Id_Jornada
  WHERE A.Fecha_Registro = CURDATE()
  GROUP BY J.Tipo_Jornada
");
$jornLabels = $jornData = [];
while($r = mysqli_fetch_assoc($res)){
  $jornLabels[] = $r['label'];
  $jornData[]   = (int)$r['presentes'];
}

// 2d) Tendencia últimos 7 días
$res = mysqli_query($conexion, "
  SELECT Fecha_Registro AS fecha,
    SUM(CASE WHEN Registro_Asistencia=1 THEN 1 ELSE 0 END) AS pres
  FROM asistencias
  WHERE Fecha_Registro >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
  GROUP BY Fecha_Registro
  ORDER BY Fecha_Registro
");
$trendDates = $trendData = [];
while($r = mysqli_fetch_assoc($res)){
  $trendDates[] = $r['fecha'];
  $trendData[]  = (int)$r['pres'];
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Colegio</title>
  <link rel="stylesheet" href="../../frontend/css/estadistica.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* Layout de tarjetas */
    .dashboard-wrapper {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1rem;
      padding: 1rem;
    }
    /* Grid de gráficos responsive */
    .charts-wrapper {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1rem;
      padding: 1rem;
    }
    /* Tarjeta de gráfico */
    .chart-card {
      background: rgba(18, 18, 30, 0.8);
      border-radius: 8px;
      padding: 1rem;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.7);
      height: 300px;
      position: relative;
      overflow: hidden;
    }
    .chart-card h3 {
      color: var(--neon-cyan);
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }
    /* Tendencia ocupa dos columnas en pantallas grandes */
    .chart-card.trend {
      grid-column: span 2;
    }
    /* Asegura que el canvas llene la tarjeta */
    .chart-card canvas {
      width: 100% !important;
      height: calc(100% - 1.5rem) !important; /* espacio para título */
    }
    /* Fondo general en modo oscuro */
    body.dark-mode {
      background: var(--bg-dark);
      color: var(--text-light);
    }
  </style>
</head>
<body class="dark-mode">
  <h2 class="dashboard-title">Dashboard de Estadísticas</h2>

  <!-- Tarjetas métricas -->
  <div class="dashboard-wrapper">
    <div class="card grado"><h3><?= $totalGrados ?></h3><p>Total Grados</p></div>
    <div class="card seccion"><h3><?= $totalSecciones ?></h3><p>Total Secciones</p></div>
    <div class="card total"><h3><?= $totalAlumnos ?></h3><p>Total Alumnos</p></div>
    <div class="card asist"><h3><?= $asistHoyPres ?></h3><p>Presentes Hoy (<?= $porcAsistHoy ?>%)</p></div>
    <div class="card hombres"><h3><?= 100 - $porcMujeres ?>%</h3><p>Hombres</p></div>
    <div class="card mujeres"><h3><?= $porcMujeres ?>%</h3><p>Mujeres</p></div>
  </div>

  <!-- Gráficos -->
  <div class="charts-wrapper">
    <div class="chart-card"><h3>Alumnos por Grado</h3><canvas id="chartGrado"></canvas></div>
    <div class="chart-card"><h3>Alumnos por Sección</h3><canvas id="chartSeccion"></canvas></div>
    <div class="chart-card"><h3>Asistencias por Jornada (Hoy)</h3><canvas id="chartJornada"></canvas></div>
    <div class="chart-card trend"><h3>Tendencia Últimos 7 Días</h3><canvas id="chartTrend"></canvas></div>
  </div>

  <!-- Script para Chart.js -->
  <script>
    const cssColor = name => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    const neonPalette = Array.from({length:8}, (_,i) => cssColor(`--chart-${i+1}`));

    const commonOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { labels: { color: '#fff', font: { size: 12 } } },
        tooltip: { backgroundColor: 'rgba(0,0,0,0.7)', titleColor: '#fff', bodyColor: '#ccc', cornerRadius: 4 }
      },
      scales: {
        x: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#fff' } },
        y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#fff', beginAtZero: true } }
      }
    };

// — Ahora: Bar en “Alumnos por Grado”
new Chart(document.getElementById('chartGrado').getContext('2d'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($gradoLabels) ?>,
    datasets: [{
      label: 'Estudiantes',
      data: <?= json_encode($gradoData) ?>,
      backgroundColor: neonPalette.slice(0, <?= count($gradoLabels) ?>),
      borderRadius: 6,
      maxBarThickness: 40
    }]
  },
  options: {
    ...commonOptions,
    plugins: { 
      ...commonOptions.plugins,
      legend: { display: false }
    }
  }
});

   // — Ahora: Doughnut en “Alumnos por Sección”
new Chart(document.getElementById('chartSeccion').getContext('2d'), {
  type: 'doughnut',
  data: {
    labels: <?= json_encode($secLabels) ?>,
    datasets: [{
      data: <?= json_encode($secData) ?>,
      backgroundColor: neonPalette,
      borderColor: 'rgba(255,255,255,0.2)',
      borderWidth: 2
    }]
  },
  options: {
    ...commonOptions,
    cutout: '60%',
    plugins: {
      legend: { position: 'bottom', labels: { color: '#ccc' } }
    }
  }
});
    // Bar chart jornadas
new Chart(document.getElementById('chartJornada').getContext('2d'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($jornLabels) ?>,
    datasets: [{
      label: 'Presentes',
      data: <?= json_encode($jornData) ?>,
      backgroundColor: neonPalette.slice(0, <?= count($jornLabels) ?>),
      borderRadius: 6,
      maxBarThickness: 40
    }]
  },
  options: {
    ...commonOptions,
    scales: {
      // mantenemos la configuración de commonOptions.scales…
      x: commonOptions.scales.x,
      y: {
        // heredamos estilo de líneas y labels…
        grid: commonOptions.scales.y.grid,
        ticks: {
          ...commonOptions.scales.y.ticks,
          beginAtZero: true       // obligar a empezar en 0
        },
        min: 0,                   // valor mínimo en 0
        max: 300,                  // valor máximo en 100
      }
    },
    plugins: {
      ...commonOptions.plugins,
      legend: { display: false }
    }
  }
});

    // Line chart trend
    // Line chart trend
const ctxT = document.getElementById('chartTrend').getContext('2d');
const gradT = ctxT.createLinearGradient(0, 0, 0, 300);
gradT.addColorStop(0, cssColor('--chart-3') + '80');
gradT.addColorStop(1, cssColor('--chart-3') + '20');

new Chart(ctxT, {
  type: 'line',
  data: {
    labels: <?= json_encode($trendDates) ?>,
    datasets: [{
      label: 'Asistencias',
      data: <?= json_encode($trendData) ?>,
      fill: true,
      tension: 0.4,
      backgroundColor: gradT,
      borderColor: cssColor('--chart-3'),
      borderWidth: 2,
      pointRadius: 5,
      pointBackgroundColor: '#fff'
    }]
  },
  options: {
    ...commonOptions,
    scales: {
      x: commonOptions.scales.x,
      y: {
        grid: commonOptions.scales.y.grid,
        ticks: {
          ...commonOptions.scales.y.ticks,
          beginAtZero: true       // empezar en 0
        },
        min: 0                    // mínimo en 0 (no habrá ejes negativos)
      }
    },
    plugins: {
      ...commonOptions.plugins,
      legend: { position: 'bottom' }
    }
  },
      scales: {
        x: {
        grid: { color: 'rgba(255,255,255,0.1)' },
        ticks: { color: '#fff' }
        },
        y: {
        grid: { color: 'rgba(255,255,255,0.1)' },
        ticks: { color: '#fff', beginAtZero: true }
        }
      }
      }
    );
  </script>
</body>
</html>