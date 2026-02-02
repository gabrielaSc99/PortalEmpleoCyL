<section class="seccion-pagina">
    <div class="container">
        <div class="cabecera-seccion">
            <h1 class="titulo-seccion">
                <i class="fas fa-heart" style="color: var(--rojo-favorito);"></i> Mis Favoritos
            </h1>
        </div>

        <!-- Filtro por estado -->
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="index.php?ruta=favoritos" class="filtro-pill <?= empty($estadoFiltro) ? 'activo' : '' ?>">
                <i class="fas fa-layer-group"></i> Todos
            </a>
            <a href="index.php?ruta=favoritos&estado=interesado" class="filtro-pill <?= $estadoFiltro === 'interesado' ? 'activo' : '' ?>">
                <i class="fas fa-eye"></i> Interesado
            </a>
            <a href="index.php?ruta=favoritos&estado=aplicado" class="filtro-pill <?= $estadoFiltro === 'aplicado' ? 'activo' : '' ?>" style="<?= $estadoFiltro === 'aplicado' ? '--azul-medio: #16a34a;' : '' ?>">
                <i class="fas fa-paper-plane"></i> Aplicado
            </a>
            <a href="index.php?ruta=favoritos&estado=descartado" class="filtro-pill <?= $estadoFiltro === 'descartado' ? 'activo' : '' ?>" style="<?= $estadoFiltro === 'descartado' ? '--azul-medio: #dc2626;' : '' ?>">
                <i class="fas fa-times-circle"></i> Descartado
            </a>
        </div>

        <?php if (!empty($favoritos)): ?>
            <div class="row g-3">
                <?php foreach ($favoritos as $fav): ?>
                    <div class="col-md-6 col-lg-4 tarjeta-animada" id="favorito-<?= $fav['id_oferta'] ?>">
                        <div class="tarjeta-oferta">
                            <div class="cuerpo-tarjeta">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <h5 class="titulo-oferta" style="margin-bottom: 0;">
                                        <a href="index.php?ruta=ofertas/ver/<?= $fav['id_oferta'] ?>">
                                            <?= htmlspecialchars($fav['titulo']) ?>
                                        </a>
                                    </h5>
                                    <?php
                                    $colorEstado = match($fav['estado']) {
                                        'aplicado' => 'background: #16a34a;',
                                        'descartado' => 'background: #dc2626;',
                                        default => 'background: var(--azul-medio);'
                                    };
                                    ?>
                                    <span class="etiqueta" style="<?= $colorEstado ?> color: white; white-space: nowrap; font-size: 0.7rem;">
                                        <?= ucfirst($fav['estado']) ?>
                                    </span>
                                </div>
                                <div class="empresa-oferta">
                                    <i class="fas fa-building"></i> <?= htmlspecialchars($fav['empresa'] ?? 'Empresa no indicada') ?>
                                </div>
                                <div class="etiquetas-oferta">
                                    <?php if (!empty($fav['provincia'])): ?>
                                        <span class="etiqueta etiqueta-provincia">
                                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($fav['provincia']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="pie-tarjeta">
                                <select class="form-select form-select-sm" style="width: auto; font-size: 0.78rem;"
                                        onchange="cambiarEstadoFavorito(<?= $fav['id_oferta'] ?>, this.value)" aria-label="Cambiar estado">
                                    <option value="interesado" <?= $fav['estado'] === 'interesado' ? 'selected' : '' ?>>Interesado</option>
                                    <option value="aplicado" <?= $fav['estado'] === 'aplicado' ? 'selected' : '' ?>>Aplicado</option>
                                    <option value="descartado" <?= $fav['estado'] === 'descartado' ? 'selected' : '' ?>>Descartado</option>
                                </select>
                                <div class="d-flex gap-2 align-items-center">
                                    <button class="boton-favorito activo" onclick="eliminarDeFavoritos(<?= $fav['id_oferta'] ?>)"
                                            aria-label="Eliminar de favoritos" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <a href="index.php?ruta=ofertas/ver/<?= $fav['id_oferta'] ?>" class="btn btn-primary btn-sm">
                                        Ver oferta
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-heart-broken fa-3x mb-3" style="color: var(--texto-terciario);"></i>
                <h4>No tienes ofertas guardadas</h4>
                <p style="color: var(--texto-secundario);">Explora las ofertas y guarda las que te interesen</p>
                <a href="index.php?ruta=ofertas" class="btn btn-primary">Explorar ofertas</a>
            </div>
        <?php endif; ?>
    </div>
</section>
