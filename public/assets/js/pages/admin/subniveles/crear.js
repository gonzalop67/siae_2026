// Selectores de elementos adaptados a Subniveles 
const formulario = document.getElementById('formulario'); 
const inputs = document.querySelectorAll('#formulario input, #formulario select'); 
const inputIdSubnivel = document.getElementById('id_subnivel'); 
const inputIdNivel = document.getElementById('nivel_id'); 
const inputNombre = document.getElementById('nombre'); 
const buttonSubmit = document.getElementById('btn-submit'); 

// Reglas de validación y nombres amigables para el usuario
const expresiones = { 
    // 💡 SOLUCIÓN: Se añade la coma (\,) y se asegura la aceptación de tildes (À-ÿ)
    nombre: /^[a-zA-ZÀ-ÿ0-9.\s\-\(\),ºªⅠⅡⅢⅣⅤⅥⅦⅧⅨⅩ]{3,100}$/ 
}; 

const nombresAmigables = {
    nivel_id: 'Nivel Educativo Padre',
    nombre: 'Nombre del Subnivel'
};

// Función para validar un campo individualmente 
const validarCampo = (expresion, input, campo) => { 
    const errorEl = document.getElementById(`error-${campo}`); 
    const labelCampo = nombresAmigables[campo] || campo;

    // Validación básica para elementos select u opcionales sin expresión regular 
    if (!expresion) { 
        if (input.required && input.value.trim() === "") { 
            input.classList.remove('is-valid'); 
            input.classList.add('is-invalid'); 
            if (errorEl) { 
                errorEl.textContent = `Por favor, seleccione un ${labelCampo} válido.`; 
                errorEl.style.display = 'block'; 
            } 
            return false; 
        } 
        input.classList.remove('is-invalid'); 
        input.classList.add('is-valid'); 
        if (errorEl) errorEl.style.display = 'none'; 
        return true; 
    } 

    if (expresion.test(input.value)) { 
        input.classList.remove('is-invalid'); 
        input.classList.add('is-valid'); 
        if (errorEl) errorEl.style.display = 'none'; 
        return true; 
    } else { 
        input.classList.remove('is-valid'); 
        input.classList.add('is-invalid'); 
        if (errorEl) { 
            errorEl.textContent = `El campo ${labelCampo} no cumple con el formato requerido.`; 
            errorEl.style.display = 'block'; 
        } 
        return false; 
    } 
}; 

// Validación en tiempo real (Keyup, Blur y Change) 
inputs.forEach((input) => { 
    if (input.type === 'submit' || input.type === 'hidden' || input.type === 'button') return; 
    
    const nombreCampo = input.name || input.id; 
    if (!nombreCampo) return; 
    
    const expresion = expresiones[nombreCampo] || null; 

    // Eventos de interacción
    input.addEventListener('keyup', () => { validarCampo(expresion, input, nombreCampo); }); 
    input.addEventListener('blur', () => { validarCampo(expresion, input, nombreCampo); }); 

    if (input.tagName === 'SELECT') { 
        input.addEventListener('change', () => { validarCampo(expresion, input, nombreCampo); }); 
    } 
}); 

// ========================================== 
// FUNCIÓN PARA REFRESCAR LA TABLA POR AJAX 
// ========================================== 
// crear.js
async function actualizarTablaHtml() {
    try {
        // 1. 💡 MEMORIA VISUAL: Detectar qué niveles estaban abiertos antes del refresco
        const nivelesAbiertosBefore = [];
        document.querySelectorAll('.nivel-padre').forEach(fila => {
            const idPadre = fila.getAttribute('data-id');
            const boton = fila.querySelector('.btn-toggle-subnivel');
            const icono = boton ? boton.querySelector('i') : null;
            
            // Si el ícono apunta hacia abajo, significa que este bloque estaba expandido
            if (icono && icono.className.includes('mdi-chevron-down')) {
                nivelesAbiertosBefore.push(idPadre);
            }
        });

        // 2. Realizar la petición AJAX al backend
        let resp = await fetch(`${base_url}/subniveles/tabla-html`, { 
            method: 'GET', 
            cache: 'no-cache' 
        }); 

        if (resp.ok) {
            const html = await resp.text();
            
            // 3. Reemplazar el componente en el DOM
            const contenedorTabla = document.getElementById('contenedor-tabla');
            if (contenedorTabla) {
                contenedorTabla.innerHTML = html;
                
                // 4. 🔄 RESTAURACIÓN AUTOMÁTICA: Reabrir los bloques que el usuario tenía expandidos
                nivelesAbiertosBefore.forEach(idPadre => {
                    // Buscar las nuevas filas hijas inyectadas
                    const filasHijas = document.querySelectorAll(`.subnivel-of${idPadre}`);
                    const filaPadre = document.querySelector(`.nivel-padre[data-id="${idPadre}"]`);
                    const boton = filaPadre ? filaPadre.querySelector('.btn-toggle-subnivel') : null;
                    const icono = boton ? boton.querySelector('i') : null;

                    // Forzar su visualización inmediata sin que el usuario tenga que dar clic de nuevo
                    filasHijas.forEach(fila => {
                        fila.style.display = 'table-row';
                    });
                    
                    if (icono) {
                        icono.className = 'mdi mdi-chevron-down';
                    }
                });
            }
        } 
    } catch (error) { 
        console.error('Error al actualizar el componente de la tabla:', error); 
    } 
} 

// Envío de información a la API 
async function fntProcesar() { 
    const textoBoton = buttonSubmit.innerText.trim();
    let urlPath = '';

    // 💡 DETECTOR TRIPARTITO DE ENDPOINTS
    if (textoBoton === 'Actualizar Nivel') {
        // Enrutamiento exclusivo hacia el nuevo método asíncrono de niveles
        urlPath = `niveles/${inputIdSubnivel.value}/actualizar-ajax`;
    } else if (textoBoton === 'Actualizar') {
        // Enrutamiento hacia la edición de subniveles
        urlPath = `subniveles/${inputIdSubnivel.value}/update`;
    } else {
        // Enrutamiento hacia la creación de subniveles nuevos
        urlPath = 'subniveles';
    }

    const urlLimpia = `${base_url}/${urlPath}`;
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
                text: 'El backend devolvió una respuesta inesperada. Revisa la consola del navegador.', 
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
                // 1. Limpiar el formulario y vaciar casillas ocultas
                formulario.reset();
                document.getElementById('id_subnivel').value = '';

                // 2. Cerrar la ventana modal de Bootstrap 4 de forma segura
                if (typeof jQuery !== 'undefined') {
                    jQuery('#modalSubnivel').modal('hide');
                }

                // 3. 🔄 EJECUTAR EL REFRESCO AJAX INVISIBLE
                actualizarTablaHtml(); 
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
        Swal.fire({ 
            title: 'Error Crítico', 
            text: 'No se pudo establecer comunicación con el servidor.', 
            icon: 'error' 
        }); 
    } finally { 
        buttonSubmit.disabled = false; 
        buttonSubmit.innerHTML = textoOriginal; 
    } 
} 

// Evento Submit: Único punto de validación frontend 
formulario.addEventListener('submit', (e) => { 
    e.preventDefault(); 
    let formularioValido = true; 

    inputs.forEach((input) => { 
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
