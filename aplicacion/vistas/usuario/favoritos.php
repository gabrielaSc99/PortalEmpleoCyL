<div class="container py-4">
    <h1 class="h3 mb-4"><i class="fas fa-heart text-danger"></i> Mis Favoritos</h1>

    <!-- Filtro por estado -->
    <div class="btn-group mb-4">
        <a href="index.php?ruta=favoritos" class="btn btn-<?= empty($estadoFiltro) ? 'primary' : 'outline-primary' ?>">Todos</a>
        <a href="index.php?ruta=favoritos&estado=interesado" class="btn btn-<?= $estadoFiltro === 'interesado' ? 'primary' : 'outline-primary' ?>">Interesado</a>
        <a href="index.php?ruta=favoritos&estado=aplicado" class="btn btn-<?= $estadoFiltro === 'aplicado' ? 'success' : 'outline-success' ?>">Aplicado</a>
        <a href="index.php?ruta=favoritos&estado=descartado" class="btn btn-<?= $estadoFiltro === 'descartado' ? 'danger' : 'outline-danger' ?>">Descartado</a>
    </div>

    <?php if (!empty($favoritos)): ?>
        <div class="row g-4">
            <?php foreach ($favoritos as $fav): ?>
                <div class="col-md-6 col-lg-4" id="favorito-<?= $fav['id_oferta'] ?>">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0"><?= htmlspecialchars($fav['titulo']) ?></h5>
                                <span class="badge bg-<?= $fav['estado'] === 'aplicado' ? 'success' : ($fav['estado'] === 'descartado' ? 'danger' : 'primary') ?>">
                                    <?= ucfirst($fav['estado']) ?>
                                </span>
                            </div>
                            <p class="text-muted small">
                                <i class="fas fa-building"></i> <?= htmlspecialchars($fav['empresa'] ?? '') ?>
                                <br><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($fav['provincia'] ?? '') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex gap-1 flex-wrap">
                                <select class="form-select form-select-sm" style="width: auto;"
                                        onchange="cambiarEstadoFavorito(<?= $fav['id_oferta'] ?>, this.value)">
                                    <option value="interesado" <?= $fav['estado'] === 'interesado' ? 'selected' : '' ?>>Interesado</option>
                                    <option value="aplicado" <?= $fav['estado'] === 'aplicado' ? 'selected' : '' ?>>Aplicado</option>
                                    <option value="descartado" <?= $fav['estado'] === 'descartado' ? 'selected' : '' ?>>Descartado</option>
                                </select>
                                <a href="index.php?ruta=ofertas/ver/<?= $fav['id_oferta'] ?>" class="btn btn-outline-primary btn-sm">Ver</a>
                                <button class="btn btn-outline-danger btn-sm" onclick="eliminarDeFavoritos(<?= $fav['id_oferta'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-heart-broken fa-3x text-muted mb-3"></i>
            <h4>No tienes ofertas guardadas</h4>
            <p class="text-muted">Explora las ofertas y guarda las que te interesen</p>
            <a href="index.php?ruta=ofertas" class="btn btn-primary">Explorar ofertas</a>
        </div>
    <?php endif; ?>
</div>
