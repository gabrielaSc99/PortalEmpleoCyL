/**
 * Archivo principal de JavaScript
 * Funciones globales de la aplicacion
 */

/**
 * Alternar favorito (agregar/eliminar)
 * @param {number} idOferta - ID de la oferta
 * @param {HTMLElement} boton - Boton pulsado
 */
async function alternarFavorito(idOferta, boton) {
    try {
        const icono = boton.querySelector('i');
        const esFavorito = icono.classList.contains('fas');

        const url = esFavorito ? 'index.php?ruta=api/favoritos/eliminar' : 'index.php?ruta=api/favoritos/agregar';

        const respuesta = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_oferta: idOferta })
        });

        const datos = await respuesta.json();

        if (datos.exito) {
            if (esFavorito) {
                icono.classList.remove('fas');
                icono.classList.add('far');
            } else {
                icono.classList.remove('far');
                icono.classList.add('fas');
            }
            mostrarNotificacion(datos.mensaje, 'success');
        } else {
            mostrarNotificacion(datos.mensaje, 'warning');
        }
    } catch (error) {
        mostrarNotificacion('Error al procesar la solicitud', 'danger');
    }
}

/**
 * Eliminar oferta de favoritos
 * @param {number} idOferta
 */
async function eliminarDeFavoritos(idOferta) {
    try {
        const respuesta = await fetch('index.php?ruta=api/favoritos/eliminar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_oferta: idOferta })
        });

        const datos = await respuesta.json();

        if (datos.exito) {
            // Eliminar tarjeta del DOM si existe
            const tarjeta = document.getElementById('favorito-' + idOferta);
            if (tarjeta) {
                tarjeta.remove();
            } else {
                location.reload();
            }
            mostrarNotificacion('Oferta eliminada de favoritos', 'success');
        }
    } catch (error) {
        mostrarNotificacion('Error al eliminar favorito', 'danger');
    }
}

/**
 * Cambiar estado de un favorito
 * @param {number} idOferta
 * @param {string} estado
 */
async function cambiarEstadoFavorito(idOferta, estado) {
    try {
        const respuesta = await fetch('index.php?ruta=api/favoritos/estado', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_oferta: idOferta, estado: estado })
        });

        const datos = await respuesta.json();

        if (datos.exito) {
            mostrarNotificacion('Estado actualizado a: ' + estado, 'success');
        }
    } catch (error) {
        mostrarNotificacion('Error al cambiar estado', 'danger');
    }
}

/**
 * Mostrar notificacion temporal tipo toast
 * @param {string} mensaje
 * @param {string} tipo - 'success', 'danger', 'warning', 'info'
 */
function mostrarNotificacion(mensaje, tipo = 'info') {
    // Crear contenedor de toasts si no existe
    let contenedor = document.getElementById('contenedor-notificaciones');
    if (!contenedor) {
        contenedor = document.createElement('div');
        contenedor.id = 'contenedor-notificaciones';
        contenedor.className = 'position-fixed top-0 end-0 p-3';
        contenedor.style.zIndex = '1100';
        document.body.appendChild(contenedor);
    }

    const toast = document.createElement('div');
    toast.className = `alert alert-${tipo} alert-dismissible fade show shadow`;
    toast.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    contenedor.appendChild(toast);

    // Auto-eliminar despues de 3 segundos
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
