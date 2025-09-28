class ValidadorGlobal {
    constructor() {
        this.patrones = {
            'no-especiales': /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]*$/, // Letras, números, espacios
            'alfanumerico': /^[a-zA-Z0-9]*$/, // Solo letras y números (sin espacios)
            'texto-simple': /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,!?]*$/, // Texto con puntuación básica
            'numero': /^[0-9]*$/, // Solo números
            'email': /^[a-zA-Z0-9._%+-@]*$/ // Email básico
        };
        
        this.caracteresBloqueados = ['*', '/', '-', '+', "'", '"', '\\', '|', '°', '!', '#', '$', '%', '&', '(', ')', '=', '?', '¿', '¡', '`', '~', '^', '[', ']', '{', '}'];
        
        this.inicializar();
    }

    inicializar() {
        // Aplicar a todos los inputs con data-validate
        document.addEventListener('DOMContentLoaded', () => {
            this.aplicarValidacionGlobal();
            this.prevenirSubmitFormularios();
        });
    }

    aplicarValidacionGlobal() {
        const inputs = document.querySelectorAll('[data-validate]');
        
        inputs.forEach(input => {
            // Validación en tiempo real
            input.addEventListener('input', (e) => {
                this.validarEnTiempoReal(e.target);
            });
            
            // Prevenir pegado de caracteres especiales
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
                const textoLimpio = this.limpiarTexto(textoPegado, input.dataset.validate);
                document.execCommand('insertText', false, textoLimpio);
            });
            
            // Validación al perder foco
            input.addEventListener('blur', (e) => {
                this.validarCampo(e.target);
            });
        });
    }

    validarEnTiempoReal(input) {
        const tipo = input.dataset.validate;
        const valor = input.value;
        
        if (!this.patrones[tipo].test(valor)) {
            this.mostrarError(input, 'Carácter no permitido detectado');
            input.value = this.limpiarTexto(valor, tipo);
        } else {
            this.ocultarError(input);
        }
    }

    validarCampo(input) {
        const tipo = input.dataset.validate;
        const valor = input.value.trim();
        
        if (valor && !this.patrones[tipo].test(valor)) {
            this.mostrarError(input, 'El campo contiene caracteres no permitidos');
            return false;
        } else {
            this.ocultarError(input);
            return true;
        }
    }

    limpiarTexto(texto, tipo) {
        switch(tipo) {
            case 'no-especiales':
                return texto.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/g, '');
            case 'alfanumerico':
                return texto.replace(/[^a-zA-Z0-9]/g, '');
            case 'texto-simple':
                return texto.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,!?]/g, '');
            case 'numero':
                return texto.replace(/[^0-9]/g, '');
            case 'email':
                return texto.replace(/[^a-zA-Z0-9._%+-@]/g, '');
            default:
                return texto;
        }
    }

    mostrarError(input, mensaje) {
        input.classList.add('error');
        input.classList.remove('success');
        
        // Crear o actualizar mensaje de error
        let mensajeError = input.nextElementSibling;
        if (!mensajeError || !mensajeError.classList.contains('mensaje-error')) {
            mensajeError = document.createElement('div');
            mensajeError.className = 'mensaje-error';
            input.parentNode.insertBefore(mensajeError, input.nextSibling);
        }
        
        mensajeError.textContent = mensaje;
        mensajeError.style.display = 'block';
    }

    ocultarError(input) {
        input.classList.remove('error');
        input.classList.add('success');
        
        const mensajeError = input.nextElementSibling;
        if (mensajeError && mensajeError.classList.contains('mensaje-error')) {
            mensajeError.style.display = 'none';
        }
    }

    prevenirSubmitFormularios() {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validarFormulario(form)) {
                    e.preventDefault();
                    alert('Por favor, corrige los campos con errores antes de enviar.');
                }
            });
        });
    }

    validarFormulario(form) {
        let esValido = true;
        const inputs = form.querySelectorAll('[data-validate]');
        
        inputs.forEach(input => {
            if (!this.validarCampo(input)) {
                esValido = false;
            }
        });
        
        return esValido;
    }

    // Método para agregar nuevos patrones dinámicamente
    agregarPatron(nombre, regex) {
        this.patrones[nombre] = regex;
    }

    // Método para aplicar validación a elementos dinámicos
    aplicarAElemento(input) {
        if (input.hasAttribute('data-validate')) {
            input.addEventListener('input', (e) => {
                this.validarEnTiempoReal(e.target);
            });
        }
    }
}

// Inicialización automática
const validador = new ValidadorGlobal();

// Para usar en otros scripts
window.ValidadorGlobal = validador;