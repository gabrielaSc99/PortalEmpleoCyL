/**
 * Funcionalidad de busqueda y filtros AJAX
 * Actualiza resultados sin recargar la pagina
 */

var temporizadorBusqueda = null;
var paginaActual = 1;

document.addEventListener('DOMContentLoaded', function() {
    var formulario = document.getElementById('formularioFiltros');
    if (!formulario) return;

    var filtroTexto = document.getElementById('filtroTexto');
    var filtroProvincia = document.getElementById('filtroProvincia');

    // Busqueda en tiempo real al escribir (con debounce de 400ms)
    if (filtroTexto) {
        filtroTexto.addEventListener('input', function() {
            clearTimeout(temporizadorBusqueda);
            temporizadorBusqueda = setTimeout(function() {
                paginaActual = 1;
                buscarOfertasAjax();
            }, 400);
        });
    }

    // Filtros instantaneos al cambiar selects
    [filtroProvincia].forEach(function(filtro) {
        if (filtro) {
            filtro.addEventListener('change', function() {
                paginaActual = 1;
                buscarOfertasAjax();
            });
        }
    });

    // Interceptar envio del formulario
    formulario.addEventListener('submit', function(e) {
        e.preventDefault();
        paginaActual = 1;
        buscarOfertasAjax();
    });
});

/**
 * Generar HTML de esqueletos de carga
 * @param {number} cantidad
 * @returns {string}
 */
function generarEsqueletos(cantidad) {
    var html = '';
    for (var i = 0; i < cantidad; i++) {
        html += '<div class="col-md-6 col-lg-4">' +
            '<div class="tarjeta-oferta" style="min-height: 220px;">' +
                '<div class="cuerpo-tarjeta">' +
                    '<div class="esqueleto" style="height: 1.2rem; width: 80%; margin-bottom: 0.75rem;"></div>' +
                    '<div class="esqueleto" style="height: 0.9rem; width: 50%; margin-bottom: 0.75rem;"></div>' +
                    '<div class="d-flex gap-2 mb-3">' +
                        '<div class="esqueleto" style="height: 1.5rem; width: 4rem; border-radius: 1rem;"></div>' +
                        '<div class="esqueleto" style="height: 1.5rem; width: 5rem; border-radius: 1rem;"></div>' +
                    '</div>' +
                    '<div class="esqueleto" style="height: 0.8rem; width: 100%; margin-bottom: 0.4rem;"></div>' +
                    '<div class="esqueleto" style="height: 0.8rem; width: 70%;"></div>' +
                '</div>' +
                '<div class="pie-tarjeta">' +
                    '<div class="esqueleto" style="height: 0.8rem; width: 5rem;"></div>' +
                    '<div class="esqueleto" style="height: 2rem; width: 5rem; border-radius: var(--radio-md);"></div>' +
                '</div>' +
            '</div>' +
        '</div>';
    }
    return html;
}

/**
 * Realizar busqueda AJAX y actualizar resultados
 */
async function buscarOfertasAjax() {
    var contenedor = document.getElementById('listaOfertas');
    var contadorResultados = document.getElementById('contadorResultados');
    var paginacionContenedor = document.getElementById('paginacionContenedor');
    if (!contenedor) return;

    // Obtener valores de filtros
    var parametros = new URLSearchParams();
    parametros.set('ruta', 'api/ofertas/buscar');
    parametros.set('pagina', paginaActual);

    var texto = document.getElementById('filtroTexto');
    var provincia = document.getElementById('filtroProvincia');

    if (texto && texto.value) parametros.set('texto', texto.value);
    if (provincia && provincia.value) parametros.set('provincia', provincia.value);

    // Mostrar esqueletos de carga
    contenedor.innerHTML = generarEsqueletos(6);

    try {
        var respuesta = await fetch('index.php?' + parametros.toString());
        var datos = await respuesta.json();

        if (datos.exito) {
            renderizarOfertas(datos.ofertas, contenedor);
            if (contadorResultados) {
                contadorResultados.textContent = number_format(datos.total) + ' ofertas encontradas';
            }
            if (paginacionContenedor) {
                renderizarPaginacion(datos.paginas, datos.pagina_actual, paginacionContenedor);
            }
        } else {
            contenedor.innerHTML =
                '<div class="col-12 text-center py-5">' +
                    '<i class="fas fa-exclamation-triangle fa-3x mb-3" style="color: var(--texto-terciario);"></i>' +
                    '<h4>Error al buscar ofertas</h4>' +
                '</div>';
        }
    } catch (error) {
        contenedor.innerHTML =
            '<div class="col-12 text-center py-5">' +
                '<i class="fas fa-exclamation-triangle fa-3x mb-3" style="color: var(--rojo-favorito);"></i>' +
                '<h4>Error de conexión</h4>' +
                '<p style="color: var(--texto-secundario);">No se pudo conectar con el servidor</p>' +
            '</div>';
    }
}

/**
 * Renderizar tarjetas de ofertas con el nuevo diseno
 * @param {Array} ofertas
 * @param {HTMLElement} contenedor
 */
function renderizarOfertas(ofertas, contenedor) {
    if (!ofertas || ofertas.length === 0) {
        contenedor.innerHTML =
            '<div class="col-12 text-center py-5">' +
                '<i class="fas fa-search fa-3x mb-3" style="color: var(--texto-terciario);"></i>' +
                '<h4>No se encontraron ofertas</h4>' +
                '<p style="color: var(--texto-secundario);">Prueba con otros filtros de búsqueda</p>' +
            '</div>';
        return;
    }

    var html = '';
    ofertas.forEach(function(oferta) {
        var descripcion = (oferta.descripcion || '').substring(0, 150);
        var empresa = escapeHtml(oferta.empresa || 'Empresa no indicada');

        var etiquetas = '';
        if (oferta.provincia) {
            etiquetas += '<span class="etiqueta etiqueta-provincia"><i class="fas fa-map-marker-alt"></i> ' + escapeHtml(oferta.provincia) + '</span>';
        }
        if (oferta.categoria) {
            etiquetas += '<span class="etiqueta etiqueta-categoria">' + escapeHtml(oferta.categoria) + '</span>';
        }
        if (oferta.tipo_contrato) {
            etiquetas += '<span class="etiqueta etiqueta-contrato">' + escapeHtml(oferta.tipo_contrato) + '</span>';
        }
        if (oferta.salario) {
            etiquetas += '<span class="etiqueta etiqueta-salario"><i class="fas fa-euro-sign"></i> ' + escapeHtml(oferta.salario) + '</span>';
        }

        html += '<div class="col-md-6 col-lg-4 tarjeta-animada">' +
            '<div class="tarjeta-oferta">' +
                '<div class="cuerpo-tarjeta">' +
                    '<h5 class="titulo-oferta">' +
                        '<a href="index.php?ruta=ofertas/ver/' + oferta.id + '">' + escapeHtml(oferta.titulo) + '</a>' +
                    '</h5>' +
                    '<div class="empresa-oferta">' +
                        '<i class="fas fa-building"></i> ' + empresa +
                    '</div>' +
                    '<div class="etiquetas-oferta">' + etiquetas + '</div>' +
                    '<p class="descripcion-oferta">' + escapeHtml(descripcion) + '...</p>' +
                '</div>' +
                '<div class="pie-tarjeta">' +
                    '<span class="fecha-oferta">' +
                        '<i class="fas fa-calendar-alt"></i> ' + (oferta.fecha_publicacion || '') +
                    '</span>' +
                    '<div class="d-flex gap-2 align-items-center">' +
                        '<button class="boton-favorito" onclick="alternarFavorito(' + oferta.id + ', this)" aria-label="Guardar en favoritos" title="Favorito">' +
                            '<i class="far fa-heart"></i>' +
                        '</button>' +
                        '<a href="index.php?ruta=ofertas/ver/' + oferta.id + '" class="btn btn-primary btn-sm">Ver oferta</a>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
    });

    contenedor.innerHTML = html;
}

/**
 * Renderizar paginacion moderna
 * @param {number} totalPaginas
 * @param {number} actual
 * @param {HTMLElement} contenedor
 */
function renderizarPaginacion(totalPaginas, actual, contenedor) {
    if (totalPaginas <= 1) {
        contenedor.innerHTML = '';
        return;
    }

    var html = '<ul class="pagination paginacion-moderna justify-content-center">';

    // Boton anterior
    if (actual > 1) {
        html += '<li class="page-item">' +
            '<a class="page-link" href="#" onclick="irAPagina(' + (actual - 1) + '); return false;" aria-label="Anterior">&laquo;</a>' +
        '</li>';
    }

    // Numeros de pagina
    var inicio = Math.max(1, actual - 3);
    var fin = Math.min(totalPaginas, actual + 3);

    for (var i = inicio; i <= fin; i++) {
        html += '<li class="page-item ' + (i === actual ? 'active' : '') + '">' +
            '<a class="page-link" href="#" onclick="irAPagina(' + i + '); return false;">' + i + '</a>' +
        '</li>';
    }

    // Boton siguiente
    if (actual < totalPaginas) {
        html += '<li class="page-item">' +
            '<a class="page-link" href="#" onclick="irAPagina(' + (actual + 1) + '); return false;" aria-label="Siguiente">&raquo;</a>' +
        '</li>';
    }

    html += '</ul>';
    contenedor.innerHTML = html;
}

/**
 * Navegar a una pagina especifica
 * @param {number} pagina
 */
function irAPagina(pagina) {
    paginaActual = pagina;
    buscarOfertasAjax();
    document.getElementById('listaOfertas').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/**
 * Escapar HTML para prevenir XSS
 * @param {string} texto
 * @returns {string}
 */
function escapeHtml(texto) {
    if (!texto) return '';
    var div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
}

/**
 * Formatear numero con separador de miles
 * @param {number} numero
 * @returns {string}
 */
function number_format(numero) {
    return Number(numero).toLocaleString('es-ES');
}
