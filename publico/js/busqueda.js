/**
 * Funcionalidad de busqueda y filtros AJAX
 */

// Aplicar filtros automaticamente al cambiar selects
document.addEventListener('DOMContentLoaded', function() {
    const filtroProvincia = document.getElementById('filtroProvincia');
    const filtroCategoria = document.getElementById('filtroCategoria');

    if (filtroProvincia) {
        filtroProvincia.addEventListener('change', function() {
            document.getElementById('formularioFiltros').submit();
        });
    }

    if (filtroCategoria) {
        filtroCategoria.addEventListener('change', function() {
            document.getElementById('formularioFiltros').submit();
        });
    }
});
