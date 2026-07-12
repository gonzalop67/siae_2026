// Selectores de elementos
const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');
const inputIdNivel = document.getElementById('id_nivel');
const inputNombre = document.getElementById('nombre');
const buttonSubmit = document.getElementById('btn-submit');

// Reglas de validación
const expresiones = {
  nombre: /^[a-zA-ZÀ-ÿ.\s]{4,64}$/
};

// Función para validar un campo individualmente
const validarCampo = (expresion, input, campo) => {
  const errorEl = document.getElementById(`error-${campo}`);

  // Si no hay expresión definida para este campo, pasa la validación por defecto
  if (!expresion) return true;

  if (expresion.test(input.value)) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid'); // Buenas prácticas: marcar visualmente lo correcto
    if (errorEl) errorEl.style.display = 'none';
    return true;
  } else {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
    if (errorEl) {
      errorEl.textContent = `El campo ${campo} no cumple con el formato requerido.`;
      errorEl.style.display = 'block';
    }
    return false;
  }
};

// Validación en tiempo real (Keyup y Blur)
inputs.forEach((input) => {
  // Ignorar campos que no requieren validación de formato (hidden, botones, etc.)
  if (input.type === 'submit' || input.type === 'hidden' || input.type === 'button') return;

  const nombreCampo = input.name || input.id;
  if (!nombreCampo) return;

  // Obtener la expresión regular correspondiente si existe
  const expresion = expresiones[nombreCampo] || null;

  // Evento al escribir: valida constantemente y limpia el error si ya es correcto
  input.addEventListener('keyup', () => {
    validarCampo(expresion, input, nombreCampo);
  });

  // Evento al salir del campo: asegura que el formato sea el correcto al perder el foco
  input.addEventListener('blur', () => {
    validarCampo(expresion, input, nombreCampo);
  });
});

// Envío a la API 
async function fntProcesar() {
  const esActualizar = buttonSubmit.innerText.trim() === 'Actualizar';
  const urlPath = esActualizar ? `niveles/${inputIdNivel.value}/update` : 'niveles';
  const urlLimpia = `${base_url}/${urlPath}`;
  
  // 1. Guardar el texto original y bloquear el botón antes de la petición
  const textoOriginal = buttonSubmit.innerHTML;
  buttonSubmit.disabled = true;
  buttonSubmit.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;

  try {
    const formData = new FormData(formulario);
    let resp = await fetch(urlLimpia, {
      method: 'POST',
      cache: 'no-cache',
      body: formData,
    });

    const respuestaTexto = await resp.text();
    let json;

    try {
      json = JSON.parse(respuestaTexto);
    } catch (errorParseo) {
      console.error("El servidor envió HTML en lugar de JSON. Contenido recibido:", respuestaTexto);
      Swal.fire({
        title: 'Respuesta inválida del servidor',
        text: 'El backend devolvió una página web (HTML) en lugar de datos. Revisa la consola.',
        icon: 'error'
      });
      return;
    }

    if (json.ok || json.success) {
      Swal.fire({
        title: '¡Completado!',
        text: json.mensaje || 'Operación realizada con éxito.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
      }).then(() => {
        formulario.reset();
        window.location.href = base_url + '/niveles';
      });
    } else if (json.errors) {
      Object.keys(json.errors).forEach((campo) => {
        const mensajeError = json.errors[campo];
        const errorContainer = document.getElementById(`error-${campo}`);
        const elementosByName = document.getElementsByName(campo);
        let elemento = (elementosByName.length > 0) ? elementosByName[0] : document.getElementById(campo);

        if (errorContainer) {
          errorContainer.innerHTML = mensajeError;
          errorContainer.style.display = 'block';
        }

        if (elemento) {
          elemento.classList.remove('is-valid');
          elemento.classList.add('is-invalid');
        }
      });

      Swal.fire({
        title: 'Error de Validación',
        text: 'Por favor, corrige los campos remarcados en rojo.',
        icon: 'error'
      });
    } else if (json.mensaje || json.error) {
      Swal.fire({ 
        title: 'Error de Proceso', 
        text: json.mensaje || json.error, 
        icon: 'error' 
      });
    }
  } catch (error) {
    console.error('Error crítico en el servidor:', error);
    Swal.fire({ title: 'Error Crítico', text: 'No se pudo establecer comunicación con el servidor.', icon: 'error' });
  } finally {
    // 2. BLOQUE CRÍTICO: El bloque finally siempre se ejecuta, garantizando que el botón se reactive
    buttonSubmit.disabled = false;
    buttonSubmit.innerHTML = textoOriginal;
  }
}

// Evento Submit: Único punto de validación frontend
formulario.addEventListener('submit', (e) => {
  e.preventDefault();
  let formularioValido = true;

  inputs.forEach((input) => {
    // Evitar procesar inputs de tipo hidden o botones que no requieren validación de formato
    if (input.type === 'submit' || input.type === 'hidden' || input.type === 'button') return;

    const nombreCampo = input.name || input.id;
    if (!nombreCampo) return;

    const expresion = expresiones[nombreCampo] || null;
    const esValido = validarCampo(expresion, input, nombreCampo);
    
    if (!esValido) {
      formularioValido = false;
    }
  });

  if (formularioValido) {
    fntProcesar();
  } else {
    Swal.fire({
      title: 'Formulario incompleto',
      text: 'Por favor, revisa las casillas marcadas en rojo antes de continuar.',
      icon: 'error',
    });
  }
});
