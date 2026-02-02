/**
 * Archivo principal de JavaScript
 * Funciones globales de la aplicacion
 */

/* ============================================
   Efecto glassmorphism en navbar al hacer scroll
   ============================================ */
document.addEventListener('DOMContentLoaded', function() {
    var navbar = document.querySelector('.navbar-portal');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 30) {
                navbar.classList.add('con-cristal');
            } else {
                navbar.classList.remove('con-cristal');
            }
        });
    }

    /* ============================================
       Hamburguesa animada
       ============================================ */
    var botonHamburguesa = document.getElementById('botonHamburguesa');
    var menuNavegacion = document.getElementById('menuNavegacion');
    if (botonHamburguesa && menuNavegacion) {
        botonHamburguesa.addEventListener('click', function() {
            botonHamburguesa.classList.toggle('activo');
            menuNavegacion.classList.toggle('show');
        });
    }

    /* ============================================
       Scroll reveal - elementos con clase .revelar
       ============================================ */
    var elementosRevelar = document.querySelectorAll('.revelar, .tarjeta-animada, .tarjeta-estadistica, .tarjeta-panel');
    if (elementosRevelar.length > 0 && 'IntersectionObserver' in window) {
        var observador = new IntersectionObserver(function(entradas) {
            entradas.forEach(function(entrada) {
                if (entrada.isIntersecting) {
                    entrada.target.classList.add('visible');
                    observador.unobserve(entrada.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        elementosRevelar.forEach(function(el) {
            observador.observe(el);
        });
    }

    /* ============================================
       Efecto ripple en botones
       ============================================ */
    document.addEventListener('click', function(e) {
        var boton = e.target.closest('.btn-primary, .btn-outline-primary, .filtro-pill');
        if (!boton) return;

        var rect = boton.getBoundingClientRect();
        var onda = document.createElement('span');
        onda.className = 'efecto-onda';
        onda.style.left = (e.clientX - rect.left) + 'px';
        onda.style.top = (e.clientY - rect.top) + 'px';
        boton.style.position = 'relative';
        boton.style.overflow = 'hidden';
        boton.appendChild(onda);

        setTimeout(function() {
            onda.remove();
        }, 600);
    });
});

/* ============================================
   Alternar favorito (agregar/eliminar)
   ============================================ */
async function alternarFavorito(idOferta, boton) {
    try {
        var icono = boton.querySelector('i');
        var esFavorito = icono.classList.contains('fas');

        var url = esFavorito ? 'index.php?ruta=api/favoritos/eliminar' : 'index.php?ruta=api/favoritos/agregar';

        var respuesta = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_oferta: idOferta })
        });

        var datos = await respuesta.json();

        if (datos.exito) {
            if (esFavorito) {
                icono.classList.remove('fas');
                icono.classList.add('far');
                boton.classList.remove('activo');
            } else {
                icono.classList.remove('far');
                icono.classList.add('fas');
                boton.classList.add('activo');
            }
            mostrarNotificacion(datos.mensaje, 'exito');
        } else {
            mostrarNotificacion(datos.mensaje, 'aviso');
        }
    } catch (error) {
        mostrarNotificacion('Error al procesar la solicitud', 'error');
    }
}

/**
 * Eliminar oferta de favoritos
 * @param {number} idOferta
 */
async function eliminarDeFavoritos(idOferta) {
    try {
        var respuesta = await fetch('index.php?ruta=api/favoritos/eliminar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_oferta: idOferta })
        });

        var datos = await respuesta.json();

        if (datos.exito) {
            var tarjeta = document.getElementById('favorito-' + idOferta);
            if (tarjeta) {
                tarjeta.style.opacity = '0';
                tarjeta.style.transform = 'scale(0.9)';
                tarjeta.style.transition = 'all 0.3s ease';
                setTimeout(function() { tarjeta.remove(); }, 300);
            } else {
                location.reload();
            }
            mostrarNotificacion('Oferta eliminada de favoritos', 'exito');
        }
    } catch (error) {
        mostrarNotificacion('Error al eliminar favorito', 'error');
    }
}

/**
 * Cambiar estado de un favorito
 * @param {number} idOferta
 * @param {string} estado
 */
async function cambiarEstadoFavorito(idOferta, estado) {
    try {
        var respuesta = await fetch('index.php?ruta=api/favoritos/estado', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_oferta: idOferta, estado: estado })
        });

        var datos = await respuesta.json();

        if (datos.exito) {
            mostrarNotificacion('Estado actualizado a: ' + estado, 'exito');
        }
    } catch (error) {
        mostrarNotificacion('Error al cambiar estado', 'error');
    }
}

/**
 * Mostrar notificacion toast moderna
 * @param {string} mensaje
 * @param {string} tipo - 'exito', 'error', 'aviso', 'info'
 */
function mostrarNotificacion(mensaje, tipo) {
    tipo = tipo || 'info';

    var contenedor = document.getElementById('contenedor-notificaciones');
    if (!contenedor) {
        contenedor = document.createElement('div');
        contenedor.id = 'contenedor-notificaciones';
        contenedor.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem;';
        document.body.appendChild(contenedor);
    }

    var iconos = {
        'exito': 'fa-check-circle',
        'error': 'fa-times-circle',
        'aviso': 'fa-exclamation-triangle',
        'info': 'fa-info-circle',
        'success': 'fa-check-circle',
        'danger': 'fa-times-circle',
        'warning': 'fa-exclamation-triangle'
    };

    var toast = document.createElement('div');
    toast.className = 'notificacion-toast notificacion-' + tipo;
    toast.innerHTML = '<i class="fas ' + (iconos[tipo] || 'fa-info-circle') + '"></i> ' + mensaje;
    contenedor.appendChild(toast);

    // Forzar reflow para activar animacion
    toast.offsetHeight;
    toast.classList.add('visible');

    setTimeout(function() {
        toast.classList.remove('visible');
        setTimeout(function() { toast.remove(); }, 300);
    }, 3000);
}
