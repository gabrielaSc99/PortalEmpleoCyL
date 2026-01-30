<div class="container py-4">
    <a href="index.php?ruta=ofertas" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>

    <div class="card shadow">
        <div class="card-body">
            <h1 class="h3 mb-3"><?= htmlspecialchars($oferta['titulo']) ?></h1>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <?php if (!empty($oferta['empresa'])): ?>
                    <span class="badge bg-light text-dark border"><i class="fas fa-building"></i> <?= htmlspecialchars($oferta['empresa']) ?></span>
                <?php endif; ?>
                <?php if (!empty($oferta['provincia'])): ?>
                    <span class="badge bg-info text-dark"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($oferta['provincia']) ?></span>
                <?php endif; ?>
                <?php if (!empty($oferta['categoria'])): ?>
                    <span class="badge bg-secondary"><i class="fas fa-tag"></i> <?= htmlspecialchars($oferta['categoria']) ?></span>
                <?php endif; ?>
                <?php if (!empty($oferta['tipo_contrato'])): ?>
                    <span class="badge bg-success"><i class="fas fa-file-contract"></i> <?= htmlspecialchars($oferta['tipo_contrato']) ?></span>
                <?php endif; ?>
                <?php if (!empty($oferta['salario'])): ?>
                    <span class="badge bg-warning text-dark"><i class="fas fa-euro-sign"></i> <?= htmlspecialchars($oferta['salario']) ?></span>
                <?php endif; ?>
                <?php if (!empty($oferta['fecha_publicacion'])): ?>
                    <span class="badge bg-light text-dark border"><i class="fas fa-calendar"></i> <?= htmlspecialchars($oferta['fecha_publicacion']) ?></span>
                <?php endif; ?>
            </div>

            <hr>

            <h5>Descripcion</h5>
            <div class="mb-4">
                <?= nl2br(htmlspecialchars($oferta['descripcion'] ?? 'Sin descripcion disponible')) ?>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <?php if ($esFavorito): ?>
                        <button class="btn btn-danger" onclick="eliminarDeFavoritos(<?= $oferta['id'] ?>)">
                            <i class="fas fa-heart-broken"></i> Eliminar de favoritos
                        </button>
                    <?php else: ?>
                        <button class="btn btn-primary" onclick="alternarFavorito(<?= $oferta['id'] ?>, this)">
                            <i class="fas fa-heart"></i> Guardar en favoritos
                        </button>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($oferta['url'])): ?>
                    <a href="<?= htmlspecialchars($oferta['url']) ?>" target="_blank" class="btn btn-outline-primary">
                        <i class="fas fa-external-link-alt"></i> Ver oferta original
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
