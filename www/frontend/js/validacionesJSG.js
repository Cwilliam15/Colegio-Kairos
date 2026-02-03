// 1) Seleccionar todos los formularios e inputs
const formularios = document.querySelectorAll('.formulario__grupo form');
const inputs = document.querySelectorAll('.formulario__grupo input');

// 2) Expresiones regulares específicas
const expresiones = {
  tipo_jornada:  /^[A-Za-zÁÉÍÓÚáéíóúüÜ\s]{3,13}$/,
  nombre_seccion: /^[A-Za-z0-9]{1,2}$/,           // ej. A1, B12, C
  nombre_grado:   /^[A-Za-zÁÉÍÓÚáéíóúüÜ\s]{3,70}$/
};

// 3) Estado de cada campo
const campos = {
  tipo_jornada:  false,
  nombre_seccion:false,
  nombre_grado:  false
};

// 4) Función genérica de validación
function validarCampo(expresion, input, campo) {
  const grupo = document.getElementById(`grupo__${campo}`);
  const error = grupo.querySelector('.formulario__input-error');
  if (expresion.test(input.value.trim())) {
    grupo.classList.remove('formulario__grupo-incorrecto');
    grupo.classList.add('formulario__grupo-correcto');
    error.classList.remove('formulario__input-error-activo');
    campos[campo] = true;
  } else {
    grupo.classList.add('formulario__grupo-incorrecto');
    grupo.classList.remove('formulario__grupo-correcto');
    error.classList.add('formulario__input-error-activo');
    campos[campo] = false;
  }
}

// 5) Asignar eventos a cada input
inputs.forEach(input => {
  input.addEventListener('keyup',   e => validarFormulario(e));
  input.addEventListener('blur',    e => validarFormulario(e));
});

// 6) Dispatcher según el name
function validarFormulario(e) {
  const name = e.target.name;
  if (expresiones[name]) {
    validarCampo(expresiones[name], e.target, name);
  }
}

// 7) Evitar envío si hay errores
formularios.forEach(form => {
  form.addEventListener('submit', e => {
    // comprueba solo el campo de ese formulario
    const input = form.querySelector('input');
    const campo = input.name;
    if (!campos[campo]) {
    
      validarCampo(expresiones[campo], input, campo);
      alert('Corrige el campo antes de enviar.');
    }
  });
});