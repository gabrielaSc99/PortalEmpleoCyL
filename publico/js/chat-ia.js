/**
 * Chat de Inteligencia Artificial
 * Busqueda por lenguaje natural y recomendaciones personalizadas
 */

/**
 * Enviar consulta de busqueda por lenguaje natural
 */
async function enviarConsultaIA() {
    const campoConsulta = document.getElementById('consultaIA');
    const consulta = campoConsulta.value.trim();

    if (!consulta) return;

    // Mostrar mensaje del usuario en el chat
    agregarMensajeChat('usuario', consulta);
    campoConsulta.value = '';

    // Mostrar indicador de carga
    const idCarga = agregarMensajeChat('ia', '<div class="cargando-ia"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Buscando ofertas...</div>');

    try {
        const respuesta = await fetch('index.php?ruta=api/ia/buscar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ consulta: consulta })
        });

        const datos = await respuesta.json();

        // Eliminar indicador de carga
        eliminarMensajeChat(idCarga);

        if (datos.exito) {
            // Mostrar respuesta de la IA
            let contenidoRespuesta = '<p>' + datos.mensaje + '</p>';

            if (datos.ofertas && datos.ofertas.length > 0) {
                contenidoRespuesta += '<div class="ofertas-ia">';
                datos.ofertas.forEach(function(oferta) {
                    contenidoRespuesta += `
                        <div class="oferta-ia-tarjeta">
                            <h6><a href="index.php?ruta=ofertas/ver/${oferta.id}">${escaparHTML(oferta.titulo)}</a></h6>
                            <small class="text-muted">
                                <i class="fas fa-building"></i> ${escaparHTML(oferta.empresa || 'N/A')} |
                                <i class="fas fa-map-marker-alt"></i> ${escaparHTML(oferta.provincia || 'N/A')}
                            </small>
                        </div>`;
                });
                contenidoRespuesta += '</div>';
                contenidoRespuesta += `<p class="text-muted small mt-2">${datos.total} ofertas encontradas en total</p>`;
            } else {
                contenidoRespuesta += '<p class="text-muted">No se encontraron ofertas con esos criterios. Prueba con otra busqueda.</p>';
            }

            agregarMensajeChat('ia', contenidoRespuesta);
        } else {
            agregarMensajeChat('ia', '<p class="text-danger">Error: ' + (datos.mensaje || 'No se pudo procesar la consulta') + '</p>');
        }
    } catch (error) {
        eliminarMensajeChat(idCarga);
        agregarMensajeChat('ia', '<p class="text-danger">Error de conexion. Verifica tu conexion a internet.</p>');
    }
}

/**
 * Obtener recomendaciones personalizadas
 */
async function obtenerRecomendaciones() {
    const idCarga = agregarMensajeChat('ia', '<div class="cargando-ia"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Analizando tu perfil para recomendaciones...</div>');

    try {
        const respuesta = await fetch('index.php?ruta=api/ia/recomendar');
        const datos = await respuesta.json();

        eliminarMensajeChat(idCarga);

        if (datos.exito) {
            let contenido = '<p>' + datos.mensaje + '</p>';

            if (datos.recomendaciones && datos.recomendaciones.length > 0) {
                contenido += '<div class="ofertas-ia">';
                datos.recomendaciones.forEach(function(oferta, indice) {
                    contenido += `
                        <div class="oferta-ia-tarjeta recomendacion">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6><a href="index.php?ruta=ofertas/ver/${oferta.id}">${indice + 1}. ${escaparHTML(oferta.titulo)}</a></h6>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-building"></i> ${escaparHTML(oferta.empresa || 'N/A')} |
                                <i class="fas fa-map-marker-alt"></i> ${escaparHTML(oferta.provincia || 'N/A')}
                            </small>
                            ${oferta.razon_recomendacion ? '<p class="text-success small mt-1 mb-0"><i class="fas fa-lightbulb"></i> ' + escaparHTML(oferta.razon_recomendacion) + '</p>' : ''}
                        </div>`;
                });
                contenido += '</div>';
            } else {
                contenido += '<p class="text-muted">No hay suficientes datos para generar recomendaciones. Completa tu perfil.</p>';
            }

            agregarMensajeChat('ia', contenido);
        } else {
            agregarMensajeChat('ia', '<p class="text-danger">' + (datos.mensaje || 'Error al obtener recomendaciones') + '</p>');
        }
    } catch (error) {
        eliminarMensajeChat(idCarga);
        agregarMensajeChat('ia', '<p class="text-danger">Error de conexion.</p>');
    }
}

/**
 * Agregar mensaje al chat
 * @param {string} tipo - 'usuario' o 'ia'
 * @param {string} contenido - HTML del mensaje
 * @returns {string} ID del mensaje
 */
function agregarMensajeChat(tipo, contenido) {
    const contenedorChat = document.getElementById('mensajesChat');
    const idMensaje = 'msg-' + Date.now();

    const mensaje = document.createElement('div');
    mensaje.id = idMensaje;
    mensaje.className = 'mensaje-chat mensaje-' + tipo;

    const icono = tipo === 'usuario' ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';
    const etiqueta = tipo === 'usuario' ? 'Tu' : 'Asistente IA';

    mensaje.innerHTML = `
        <div class="mensaje-cabecera">
            ${icono} <strong>${etiqueta}</strong>
        </div>
        <div class="mensaje-contenido">${contenido}</div>
    `;

    contenedorChat.appendChild(mensaje);
    contenedorChat.scrollTop = contenedorChat.scrollHeight;

    return idMensaje;
}

/**
 * Eliminar mensaje del chat por ID
 * @param {string} idMensaje
 */
function eliminarMensajeChat(idMensaje) {
    const mensaje = document.getElementById(idMensaje);
    if (mensaje) mensaje.remove();
}

/**
 * Escapar HTML para evitar XSS
 * @param {string} texto
 * @returns {string}
 */
function escaparHTML(texto) {
    if (!texto) return '';
    const div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
}

// Enviar con Enter
document.addEventListener('DOMContentLoaded', function() {
    const campo = document.getElementById('consultaIA');
    if (campo) {
        campo.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                enviarConsultaIA();
            }
        });
    }
});
