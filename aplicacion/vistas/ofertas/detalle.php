<div class="container" style="padding: 2rem 0 3rem;">
    <a href="index.php?ruta=ofertas" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>

    <div class="detalle-oferta">
        <div class="cabecera-detalle">
            <h1 class="titulo-detalle"><?= htmlspecialchars($oferta['titulo']) ?></h1>

            <div class="d-flex flex-wrap gap-2">
                <?php if (!empty($oferta['empresa'])): ?>
                    <span class="etiqueta" style="background: var(--fondo-principal); color: var(--texto-principal); border: 1px solid var(--borde-suave);">
                        <i class="fas fa-building"></i> <?= htmlspecialchars($oferta['empresa']) ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($oferta['provincia'])): ?>
                    <span class="etiqueta etiqueta-provincia">
                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($oferta['provincia']) ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($oferta['categoria'])): ?>
                    <span class="etiqueta etiqueta-categoria">
                        <i class="fas fa-tag"></i> <?= htmlspecialchars($oferta['categoria']) ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($oferta['tipo_contrato'])): ?>
                    <span class="etiqueta etiqueta-contrato">
                        <i class="fas fa-file-contract"></i> <?= htmlspecialchars($oferta['tipo_contrato']) ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($oferta['salario'])): ?>
                    <span class="etiqueta etiqueta-salario">
                        <i class="fas fa-euro-sign"></i> <?= htmlspecialchars($oferta['salario']) ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($oferta['fecha_publicacion'])): ?>
                    <span class="etiqueta" style="background: var(--fondo-principal); color: var(--texto-secundario); border: 1px solid var(--borde-suave);">
                        <i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($oferta['fecha_publicacion']) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="cuerpo-detalle">
            <h5 style="font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1rem; margin-bottom: 0.75rem;">Descripción</h5>
            <div style="color: var(--texto-secundario); line-height: 1.75; font-size: 0.925rem;">
                <?= nl2br(htmlspecialchars($oferta['descripcion'] ?? 'Sin descripción disponible')) ?>
            </div>
        </div>

        <div class="acciones-detalle d-flex gap-2 flex-wrap">
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <?php if ($esFavorito): ?>
                    <button class="btn btn-outline-secondary" onclick="eliminarDeFavoritos(<?= $oferta['id'] ?>)" aria-label="Eliminar de favoritos">
                        <i class="fas fa-heart-broken me-1"></i> Eliminar de favoritos
                    </button>
                <?php else: ?>
                    <button class="btn btn-primary" onclick="alternarFavorito(<?= $oferta['id'] ?>, this)" aria-label="Guardar en favoritos">
                        <i class="fas fa-heart me-1"></i> Guardar en favoritos
                    </button>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($oferta['url'])): ?>
                <a href="<?= htmlspecialchars($oferta['url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i> Ver oferta original
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
