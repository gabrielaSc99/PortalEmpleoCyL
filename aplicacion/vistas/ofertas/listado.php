<!-- Cabecera del listado -->
<section class="seccion-pagina" style="padding-bottom: 0;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h1 class="titulo-seccion" style="font-size: 1.5rem; margin-bottom: 0.15rem;">
                    <i class="fas fa-search" style="color: var(--dorado);"></i> Ofertas de Empleo
                </h1>
                <p id="contadorResultados" style="color: var(--texto-secundario); font-size: 0.9rem; margin: 0;">
                    <?= number_format($total ?? 0) ?> ofertas encontradas
                </p>
            </div>
        </div>

        <!-- Barra de filtros -->
        <div class="barra-filtros">
            <form class="row g-2 align-items-end" id="formularioFiltros" action="index.php" method="GET">
                <input type="hidden" name="ruta" value="ofertas">
                <div class="col-12 col-md-5">
                    <label class="form-label">Buscar</label>
                    <input type="text" class="form-control" name="texto" id="filtroTexto"
                           placeholder="Título, empresa, palabra clave..."
                           value="<?= htmlspecialchars($filtros['texto'] ?? '') ?>"
                           aria-label="Buscar ofertas">
                </div>
                <div class="col-6 col-md-4">
                    <label class="form-label">Provincia</label>
                    <select class="form-select" name="provincia" id="filtroProvincia" aria-label="Filtrar por provincia">
                        <option value="">Todas</option>
                        <?php foreach ($provincias ?? [] as $prov): ?>
                            <option value="<?= htmlspecialchars($prov['provincia']) ?>"
                                <?= ($filtros['provincia'] ?? '') === $prov['provincia'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prov['provincia']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>

            <!-- Filtros activos -->
            <?php
            $filtrosActivos = array_filter($filtros ?? []);
            if (!empty($filtrosActivos)):
            ?>
                <div class="filtros-activos-contenedor">
                    <small style="color: var(--texto-terciario); font-weight: 500;">Filtros:</small>
                    <?php foreach ($filtrosActivos as $clave => $valor): ?>
                        <span class="filtro-activo-chip">
                            <?= htmlspecialchars($valor) ?>
                            <a href="#" class="cerrar-filtro" onclick="limpiarFiltro('<?= $clave ?>'); return false;" aria-label="Quitar filtro <?= htmlspecialchars($valor) ?>">&times;</a>
                        </span>
                    <?php endforeach; ?>
                    <a href="index.php?ruta=ofertas" class="btn btn-outline-secondary btn-sm" style="font-size: 0.75rem;">Limpiar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Resultados -->
<section style="padding-bottom: 3rem;">
    <div class="container">
        <div class="row g-3" id="listaOfertas">
            <?php if (!empty($ofertas)): ?>
                <?php foreach ($ofertas as $oferta): ?>
                    <div class="col-md-6 col-lg-4 tarjeta-animada">
                        <div class="tarjeta-oferta">
                            <div class="cuerpo-tarjeta">
                                <h5 class="titulo-oferta">
                                    <a href="index.php?ruta=ofertas/ver/<?= $oferta['id'] ?>">
                                        <?= htmlspecialchars($oferta['titulo']) ?>
                                    </a>
                                </h5>
                                <div class="empresa-oferta">
                                    <i class="fas fa-building"></i>
                                    <?= htmlspecialchars($oferta['empresa'] ?? 'Empresa no indicada') ?>
                                </div>
                                <div class="etiquetas-oferta">
                                    <?php if (!empty($oferta['provincia'])): ?>
                                        <span class="etiqueta etiqueta-provincia">
                                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($oferta['provincia']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($oferta['categoria'])): ?>
                                        <span class="etiqueta etiqueta-categoria"><?= htmlspecialchars($oferta['categoria']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($oferta['tipo_contrato'])): ?>
                                        <span class="etiqueta etiqueta-contrato"><?= htmlspecialchars($oferta['tipo_contrato']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($oferta['salario'])): ?>
                                        <span class="etiqueta etiqueta-salario"><i class="fas fa-euro-sign"></i> <?= htmlspecialchars($oferta['salario']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="descripcion-oferta">
                                    <?= htmlspecialchars(mb_substr($oferta['descripcion'] ?? '', 0, 150)) ?>...
                                </p>
                            </div>
                            <div class="pie-tarjeta">
                                <span class="fecha-oferta">
                                    <i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($oferta['fecha_publicacion'] ?? '') ?>
                                </span>
                                <div class="d-flex gap-2 align-items-center">
                                    <?php if (isset($_SESSION['id_usuario'])): ?>
                                        <button class="boton-favorito" onclick="alternarFavorito(<?= $oferta['id'] ?>, this)"
                                                aria-label="Guardar en favoritos" title="Favorito">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    <?php endif; ?>
                                    <a href="index.php?ruta=ofertas/ver/<?= $oferta['id'] ?>" class="btn btn-primary btn-sm">
                                        Ver oferta
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-3x mb-3" style="color: var(--texto-terciario);"></i>
                    <h4>No se encontraron ofertas</h4>
                    <p style="color: var(--texto-secundario);">Prueba con otros filtros de búsqueda</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Paginación -->
        <nav class="mt-4" id="paginacionContenedor" aria-label="Paginación de ofertas">
            <?php if (($paginas ?? 0) > 1): ?>
                <ul class="pagination paginacion-moderna justify-content-center">
                    <?php if ($paginaActual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="irAPagina(<?= $paginaActual - 1 ?>); return false;" aria-label="Anterior">&laquo;</a>
                        </li>
                    <?php endif; ?>
                    <?php
                    $inicio = max(1, $paginaActual - 3);
                    $fin = min($paginas, $paginaActual + 3);
                    for ($i = $inicio; $i <= $fin; $i++):
                    ?>
                        <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                            <a class="page-link" href="#" onclick="irAPagina(<?= $i ?>); return false;"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($paginaActual < $paginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="irAPagina(<?= $paginaActual + 1 ?>); return false;" aria-label="Siguiente">&raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </nav>
    </div>
</section>

<script src="js/busqueda.js"></script>
<script>
/**
 * Limpiar un filtro especifico
 * @param {string} clave
 */
function limpiarFiltro(clave) {
    var mapa = {
        'texto': 'filtroTexto',
        'provincia': 'filtroProvincia'
    };
    var campo = document.getElementById(mapa[clave]);
    if (campo) {
        campo.value = '';
        buscarOfertasAjax();
    }
}
</script>
