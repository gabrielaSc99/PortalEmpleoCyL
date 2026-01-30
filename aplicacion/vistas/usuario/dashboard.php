<div class="container py-4">
    <h1 class="h3 mb-4"><i class="fas fa-tachometer-alt text-primary"></i> Mi Panel</h1>

    <!-- Tarjetas resumen -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                    <h3><?= $totalFavoritos ?? 0 ?></h3>
                    <p class="text-muted mb-0">Ofertas guardadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-briefcase fa-2x text-success mb-2"></i>
                    <h3><?= $estadisticas['total'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Ofertas totales</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-map-marker-alt fa-2x text-info mb-2"></i>
                    <h3><?= htmlspecialchars($usuario['provincia'] ?? 'Sin definir') ?></h3>
                    <p class="text-muted mb-0">Tu provincia</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Favoritos recientes -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-heart text-danger"></i> Favoritos recientes</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($favoritosRecientes)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($favoritosRecientes as $fav): ?>
                                <a href="index.php?ruta=ofertas/ver/<?= $fav['id_oferta'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($fav['titulo']) ?></h6>
                                            <small class="text-muted"><?= htmlspecialchars($fav['empresa'] ?? '') ?> - <?= htmlspecialchars($fav['provincia'] ?? '') ?></small>
                                        </div>
                                        <span class="badge bg-<?= $fav['estado'] === 'aplicado' ? 'success' : ($fav['estado'] === 'descartado' ? 'danger' : 'primary') ?>">
                                            <?= ucfirst($fav['estado']) ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <a href="index.php?ruta=favoritos" class="btn btn-outline-primary btn-sm mt-3">Ver todos</a>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No tienes ofertas guardadas</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Estadisticas rapidas -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar text-primary"></i> Top provincias</h5>
                </div>
                <div class="card-body">
                    <?php foreach (array_slice($estadisticas['por_provincia'] ?? [], 0, 5) as $prov): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?= htmlspecialchars($prov['provincia']) ?></span>
                            <span class="badge bg-primary"><?= $prov['total'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user text-primary"></i> Mi perfil</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
                    <p><strong>Sector:</strong> <?= htmlspecialchars($usuario['sector'] ?? 'Sin definir') ?></p>
                    <p><strong>Experiencia:</strong> <?= htmlspecialchars($usuario['nivel_experiencia'] ?? 'Sin definir') ?></p>
                    <a href="index.php?ruta=perfil" class="btn btn-outline-primary btn-sm">Editar perfil</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafico de ofertas por provincia -->
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-chart-pie text-primary"></i> Ofertas por provincia</h5>
        </div>
        <div class="card-body">
            <canvas id="graficoProvincias" height="300"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafico de ofertas por provincia
const datosProvincias = <?= json_encode($estadisticas['por_provincia'] ?? []) ?>;
if (datosProvincias.length > 0) {
    const ctx = document.getElementById('graficoProvincias').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: datosProvincias.map(p => p.provincia),
            datasets: [{
                label: 'Ofertas por provincia',
                data: datosProvincias.map(p => p.total),
                backgroundColor: [
                    '#0d6efd', '#198754', '#0dcaf0', '#ffc107', '#dc3545',
                    '#6f42c1', '#fd7e14', '#20c997', '#6610f2'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
}
</script>
