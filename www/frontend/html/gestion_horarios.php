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

        <div class="calendario" id="cal-<?= $id ?>"></div>

        <div class="form-grupo">
            <input type="time" class="entrada" placeholder="Hora entrada">
            <input type="time" class="salida" placeholder="Hora salida">
            <input type="text" class="obs" placeholder="Observaciones">
            <button class="btn-agregar">Añadir Grupo</button>
        </div>

        <div class="lista-grupos"></div>
    </div>

    <?php endforeach; ?>

</div>

<script src="../js/horarios.js"></script>
</body>
</html>
