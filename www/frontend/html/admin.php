<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php"); // Redirige al login (index.php está en la misma carpeta)
    die();
}

if ($_SESSION['rol'] != 'admin') {
    header("Location: menu.php"); // Ruta corregida
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
  <link rel="stylesheet" href="../css/admin.css?=v6.0" />
  <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
</head>

<body>
<?php if (isset($_GET['success'])): ?>
  <div class="toast toast-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <div class="toast toast-error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

        <!-- Sidebar de iconos -->
<div class="side-icons">
  <a href="#" id="toggleTheme" title="Cambiar tema">
    <i class="fas fa-moon"></i>
  </a>
  <a href="../html/menu.php" title="Volver al menú">
    <i class="fas fa-home"></i>
  </a>
  <a href="../../backend/php/cerrar_sesion.php" title="Cerrar sesión">
    <i class="fas fa-sign-out-alt"></i>
  </a>
</div>
  <div class="admin-container">
    <!-- Contenedor principal del panel de administración -->
    <h1>Gestión de Usuarios</h1>
    <!-- Título principal de la página -->
    <details class="form-section">
    <summary><h2>Agregar Usuario</h2></summary>
    <form action="../../backend/php/registro_usuario_be.php" method="POST">
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
    </details> 
    <details class="form-section">
      <summary><h2>Ver Usuarios Existentes</h2></summary>
      <iframe src="../../backend/php/ver_usuarios.php" class="usuarios-frame"></iframe>
    </details>
    <details class="form-section">
      <summary><h2>Ver Actividades de Usuario</h2></summary>
      <iframe src="../../backend/php/ver_logs.php" class="usuarios-frame"></iframe>
    </details>
      <details class="form-section">
      <summary><h2>Cambiar Contraseña</h2></summary>
      <form action="../../backend/php/cambiar_contrasena.php" method="POST">
      <input type="text" placeholder="Usuario" required name="usuario" />
      <input type="password" placeholder="Contraseña Actual" required name="contrasena_actual" />
      <input type="password" placeholder="Nueva Contraseña" required name="nueva_contrasena" />
      <button type="submit" class="btn-cambiar">Cambiar Contraseña</button>
      </form>
      </details>
      <details class="form-section">
      <!-- Elemento desplegable para la sección de eliminación -->
      <summary><h2>Eliminar Usuario</h2></summary>
      <!-- Título que funciona como botón de despliegue -->
      <form action="../../backend/php/eliminar_usuario.php" method="POST">
        <!-- Formulario que envía datos a eliminar_usuario.php usando POST -->
        <input type="text" placeholder="Usuario a eliminar" required name="usuario" />
        <!-- Campo para ingresar el usuario a eliminar (obligatorio) -->
        <button type="submit" class="btn-eliminar">Eliminar Usuario</button>
        <!-- Botón para enviar el formulario -->
      </form>
    </details>
  </div>
  <div class="floating-particles" id="particles"></div>
  <script>
// Referencias
const toggle = document.getElementById('toggleTheme');
const body   = document.body;

// Al cargar, mira qué tema tenemos guardado
const theme = localStorage.getItem('theme');
if (theme === 'dark') {
  body.classList.add('dark-mode');
  toggle.querySelector('i').classList.replace('fa-moon','fa-sun');
} else if (theme === 'light') {
  body.classList.add('light-mode');
  toggle.querySelector('i').classList.replace('fa-moon','fa-sun');
}

// Al hacer click alternamos entre light y dark
toggle.addEventListener('click', e => {
  e.preventDefault();

  // Si estoy en dark, quiero light. Si estoy en light o sin clase, quiero dark.
  if (body.classList.contains('dark-mode')) {
    body.classList.replace('dark-mode','light-mode');
    localStorage.setItem('theme','light');
  } else if (body.classList.contains('light-mode')) {
    body.classList.replace('light-mode','dark-mode');
    localStorage.setItem('theme','dark');
  } else {
    // estado inicial: pasamos a dark
    body.classList.add('dark-mode');
    localStorage.setItem('theme','dark');
  }

  // Cambiamos ícono luna/sol
  toggle.querySelector('i').classList.toggle('fa-moon');
  toggle.querySelector('i').classList.toggle('fa-sun');
});
</script>
<script>
  // Ocultar automáticamente el mensaje después de 5 segundos
  setTimeout(() => {
    const toast = document.querySelector('.toast');
    if (toast) {
      toast.style.transition = 'opacity 0.5s ease';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 500); // Elimina el nodo del DOM después
    }
  }, 5000);
</script>
</body>
</html>