<!-- Filtros -->
<section class="bg-light py-4">
    <div class="container">
        <h1 class="h3 mb-1"><i class="fas fa-search text-primary"></i> Ofertas de Empleo</h1>
        <p class="text-muted mb-3"><?= number_format($total ?? 0) ?> ofertas encontradas</p>

        <form class="row g-2 align-items-end" id="formularioFiltros" action="index.php" method="GET">
            <input type="hidden" name="ruta" value="ofertas">
            <div class="col-md-4">
                <input type="text" class="form-control" name="texto" id="filtroTexto"
                       placeholder="Buscar por titulo, empresa..."
                       value="<?= htmlspecialchars($filtros['texto'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="provincia" id="filtroProvincia">
                    <option value="">Todas las provincias</option>
                    <?php foreach ($provincias ?? [] as $prov): ?>
                        <option value="<?= htmlspecialchars($prov['provincia']) ?>"
                            <?= ($filtros['provincia'] ?? '') === $prov['provincia'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prov['provincia']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="categoria" id="filtroCategoria">
                    <option value="">Todas las categorias</option>
                    <?php foreach ($categorias ?? [] as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['categoria']) ?>"
                            <?= ($filtros['categoria'] ?? '') === $cat['categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Listado -->
<section class="py-4">
    <div class="container">
        <div class="row g-4" id="listaOfertas">
            <?php if (!empty($ofertas)): ?>
                <?php foreach ($ofertas as $oferta): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($oferta['titulo']) ?></h5>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-building"></i> <?= htmlspecialchars($oferta['empresa'] ?? 'Empresa no indicada') ?>
                                </p>
                                <div class="mb-2">
                                    <span class="badge bg-info text-dark">
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($oferta['provincia'] ?? 'N/A') ?>
                                    </span>
                                    <?php if (!empty($oferta['categoria'])): ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($oferta['categoria']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($oferta['tipo_contrato'])): ?>
                                        <span class="badge bg-success"><?= htmlspecialchars($oferta['tipo_contrato']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text small flex-grow-1">
                                    <?= htmlspecialchars(mb_substr($oferta['descripcion'] ?? '', 0, 150)) ?>...
                                </p>
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> <?= $oferta['fecha_publicacion'] ?? '' ?>
                                </small>
                                <div>
                                    <?php if (isset($_SESSION['id_usuario'])): ?>
                                        <button class="btn btn-outline-danger btn-sm me-1"
                                                onclick="alternarFavorito(<?= $oferta['id'] ?>, this)" title="Favorito">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    <?php endif; ?>
                                    <a href="index.php?ruta=ofertas/ver/<?= $oferta['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        Ver detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>No se encontraron ofertas</h4>
                    <p class="text-muted">Prueba con otros filtros de busqueda</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Paginacion -->
        <?php if (($paginas ?? 0) > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $paginas; $i++): ?>
                        <?php
                        $params = $_GET;
                        $params['pagina'] = $i;
                        $queryString = http_build_query($params);
                        ?>
                        <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?<?= $queryString ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<script src="js/busqueda.js"></script>
