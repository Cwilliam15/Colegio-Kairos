document.addEventListener('DOMContentLoaded', () => {
  const mensaje = document.querySelector('.mensaje');
  if (!mensaje) return;

  // Tras 5 segundos, le añadimos la clase hide
  setTimeout(() => {
    mensaje.classList.add('hide');
    // (Opcional) si quieres luego eliminarla del DOM:
    // setTimeout(() => mensaje.remove(), 500);
  }, 5000);
});
    const fileInput = document.getElementById('archivo');
const fileNameSpan = document.getElementById('file-name');

fileInput.addEventListener('change', () => {
  const nombre = fileInput.files.length
    ? fileInput.files[0].name
    : 'Sin archivos seleccionados';
  fileNameSpan.textContent = nombre;
});