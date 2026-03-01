<?php 
session_start();
if (!isset($_SESSION['rol'])) {
    header("Location: menu.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Horarios</title>
    <link rel="stylesheet" href="../css/horarios.css">
</head>
<body>

<h1 class="titulo">GESTIÓN DE HORARIOS</h1>

<div class="contenedor-jornadas">

<?php 
$jornadas = [
    "J-1" => "Jornada Matutina",
    "J-2" => "Jornada Vespertina",
    "J-3" => "Jornada Fin de Semana"
];

foreach($jornadas as $id => $nombre): ?>

<div class="card-jornada" data-jornada="<?= $id ?>">
    
    <h2><?= $nombre ?></h2>

    <!-- =========================
         FORMULARIO NUEVO GRUPO
    ==========================-->

    <div class="form-grupo">

        <!-- Selector de días -->
        <div class="dias-container">
            <?php 
            $dias = ["Lunes","Martes","Miércoles","Jueves","Viernes","Sábado","Domingo"];
            foreach($dias as $dia): ?>
                <label class="dia">
                    <input type="checkbox" value="<?= $dia ?>">
                    <span><?= $dia ?></span>
                </label>
            <?php endforeach; ?>
        </div>

        <!-- Horas -->
        <div class="horas-container">
            <input type="time" class="entrada" title="Hora de entrada">
            <input type="time" class="salida" title="Hora de salida">
        </div>

        <!-- Observaciones -->
        <input type="text" class="obs" placeholder="Observaciones">

        <!-- Selector de color -->
        <div class="color-container">
            <label>Color del grupo:</label>
            <input type="color" class="color-grupo" value="#2563eb">
        </div>

        <!-- Botón -->
        <button class="btn-agregar">Añadir Grupo</button>

    </div>

    <!-- =========================
         LISTA DE GRUPOS GUARDADOS
    ==========================-->

    <div class="lista-grupos">
        <!-- Aquí se insertan dinámicamente los grupos -->
    </div>

</div>

<?php endforeach; ?>

</div>

<script src="../js/horarios.js"></script>
</body>
</html>
