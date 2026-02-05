/**
 * Validacion de formularios en el lado cliente
 * Validacion en tiempo real con feedback visual
 */

document.addEventListener('DOMContentLoaded', function() {
    const formularioRegistro = document.getElementById('formularioRegistro');
    if (formularioRegistro) {
        inicializarValidacionRegistro();
    }
});

/**
 * Inicializar validacion del formulario de registro
 */
function inicializarValidacionRegistro() {
    const campos = {
        nombre: document.getElementById('nombre'),
        email: document.getElementById('email'),
        contrasena: document.getElementById('contrasena'),
        confirmar: document.getElementById('confirmar_contrasena')
    };

    // Validar nombre al perder foco
    if (campos.nombre) {
        campos.nombre.addEventListener('blur', function() {
            if (this.value.trim().length < 2) {
                marcarInvalido(this, 'El nombre debe tener al menos 2 caracteres');
            } else {
                marcarValido(this);
            }
        });
    }

    // Validar email
    if (campos.email) {
        campos.email.addEventListener('blur', function() {
            var patronEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!patronEmail.test(this.value)) {
                marcarInvalido(this, 'Introduce un email valido');
            } else {
                marcarValido(this);
            }
        });
    }

    // Validar contrasena
    if (campos.contrasena) {
        campos.contrasena.addEventListener('input', function() {
            var fuerza = calcularFuerzaContrasena(this.value);
            mostrarFuerzaContrasena(this, fuerza);

            if (this.value.length < 6) {
                marcarInvalido(this, 'Minimo 6 caracteres');
            } else {
                marcarValido(this);
            }

            // Revalidar confirmacion si ya tiene valor
            if (campos.confirmar && campos.confirmar.value) {
                validarConfirmacion(campos.contrasena, campos.confirmar);
            }
        });
    }

    // Validar confirmacion de contrasena
    if (campos.confirmar) {
        campos.confirmar.addEventListener('input', function() {
            validarConfirmacion(campos.contrasena, this);
        });
    }

    // Validar antes de enviar
    var formulario = document.getElementById('formularioRegistro');
    if (formulario) {
        formulario.addEventListener('submit', function(e) {
            var valido = true;

            if (campos.nombre && campos.nombre.value.trim().length < 2) {
                marcarInvalido(campos.nombre, 'El nombre es obligatorio');
                valido = false;
            }

            if (campos.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(campos.email.value)) {
                marcarInvalido(campos.email, 'Email invalido');
                valido = false;
            }

            if (campos.contrasena && campos.contrasena.value.length < 6) {
                marcarInvalido(campos.contrasena, 'Minimo 6 caracteres');
                valido = false;
            }

            if (campos.confirmar && campos.contrasena && campos.contrasena.value !== campos.confirmar.value) {
                marcarInvalido(campos.confirmar, 'Las contrasenas no coinciden');
                valido = false;
            }

            if (!valido) {
                e.preventDefault();
            }
        });
    }
}

/**
 * Validar que las contrasenas coinciden
 */
function validarConfirmacion(campoContrasena, campoConfirmar) {
    if (campoContrasena.value !== campoConfirmar.value) {
        marcarInvalido(campoConfirmar, 'Las contrasenas no coinciden');
    } else if (campoConfirmar.value.length > 0) {
        marcarValido(campoConfirmar);
    }
}

/**
 * Calcular fuerza de la contrasena
 * @param {string} contrasena
 * @returns {object} {nivel: string, porcentaje: number}
 */
function calcularFuerzaContrasena(contrasena) {
    var puntos = 0;
    if (contrasena.length >= 6) puntos++;
    if (contrasena.length >= 10) puntos++;
    if (/[A-Z]/.test(contrasena)) puntos++;
    if (/[0-9]/.test(contrasena)) puntos++;
    if (/[^A-Za-z0-9]/.test(contrasena)) puntos++;

    var niveles = ['Muy debil', 'Debil', 'Aceptable', 'Fuerte', 'Muy fuerte'];
    var colores = ['#dc3545', '#fd7e14', '#ffc107', '#198754', '#0d6efd'];

    return {
        nivel: niveles[Math.min(puntos, 4)],
        porcentaje: (puntos / 5) * 100,
        color: colores[Math.min(puntos, 4)]
    };
}

/**
 * Mostrar indicador de fuerza de contrasena
 */
function mostrarFuerzaContrasena(campo, fuerza) {
    var indicadorId = 'fuerza-contrasena';
    var indicador = document.getElementById(indicadorId);

    if (!indicador) {
        indicador = document.createElement('div');
        indicador.id = indicadorId;
        indicador.className = 'mt-1';
        campo.parentNode.appendChild(indicador);
    }

    if (campo.value.length === 0) {
        indicador.innerHTML = '';
        return;
    }

    indicador.innerHTML = `
        <div class="progress" style="height: 5px;">
            <div class="progress-bar" style="width: ${fuerza.porcentaje}%; background-color: ${fuerza.color};"></div>
        </div>
        <small style="color: ${fuerza.color};">${fuerza.nivel}</small>
    `;
}

/**
 * Marcar campo como invalido
 */
function marcarInvalido(campo, mensaje) {
    campo.classList.remove('is-valid');
    campo.classList.add('is-invalid');

    // Eliminar mensaje anterior si existe
    var anterior = campo.parentNode.querySelector('.invalid-feedback');
    if (anterior) anterior.remove();

    var div = document.createElement('div');
    div.className = 'invalid-feedback';
    div.textContent = mensaje;
    campo.parentNode.appendChild(div);
}

/**
 * Marcar campo como valido
 */
function marcarValido(campo) {
    campo.classList.remove('is-invalid');
    campo.classList.add('is-valid');

    var anterior = campo.parentNode.querySelector('.invalid-feedback');
    if (anterior) anterior.remove();
}
