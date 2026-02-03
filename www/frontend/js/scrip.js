document.addEventListener('DOMContentLoaded', () => {
  const barraLateral = document.querySelector(".barra-lateral");
  const menu         = document.querySelector(".menu");
  const main         = document.querySelector("main");
  const spans        = document.querySelectorAll("span");
  const cloud        = document.getElementById("cloud");

  // Switch de modo oscuro
  const palanca = document.querySelector(".switch");
  const circulo = document.querySelector(".circulo");
  const body    = document.body;
  const iframe  = document.getElementById("iframeAlumnos");

  // ─── 1) Inicializar tema según localStorage ──────────────────────────────
  const savedTheme = localStorage.getItem("theme");
  const isDark     = savedTheme === "dark";

  if (isDark) {
    body.classList.add("dark-mode");
    circulo.classList.add("prendido");
    // Si hay iframe, aplica también
    if (iframe?.contentDocument?.body) {
      iframe.contentDocument.body.classList.add("dark-mode");
    }
  }

  // ─── 2) Función para cambiar tema y propagar ─────────────────────────────
  function toggleDarkMode() {
    const nowDark = body.classList.toggle("dark-mode");
    circulo.classList.toggle("prendido", nowDark);
    localStorage.setItem("theme", nowDark ? "dark" : "light");

    if (iframe?.contentDocument?.body) {
      iframe.contentDocument.body.classList.toggle("dark-mode", nowDark);
    }
  }

  // ─── 3) Listener en el switch ───────────────────────────────────────────
  palanca.addEventListener("click", toggleDarkMode);

  // ─── 4) Sidebar expand/collapse ──────────────────────────────────────────
  menu.addEventListener("click", () => {
    barraLateral.classList.toggle("max-barra-lateral");
    if (barraLateral.classList.contains("max-barra-lateral")) {
      menu.children[0].style.display = "none";
      menu.children[1].style.display = "block";
    } else {
      menu.children[0].style.display = "block";
      menu.children[1].style.display = "none";
    }
    if (window.innerWidth <= 320) {
      barraLateral.classList.add("mini-barra-lateral");
      main.classList.add("min-main");
      spans.forEach(span => span.classList.add("oculto"));
    }
  });

  // ─── 5) Mini-sidebar toggle ───────────────────────────────────────────────
  cloud.addEventListener("click", () => {
    barraLateral.classList.toggle("mini-barra-lateral");
    main.classList.toggle("min-main");
    spans.forEach(span => span.classList.toggle("oculto"));
  });
});
