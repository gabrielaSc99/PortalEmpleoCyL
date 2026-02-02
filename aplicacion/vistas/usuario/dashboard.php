<section class="seccion-pagina">
    <div class="container">
        <div class="cabecera-seccion">
            <h1 class="titulo-seccion">
                <i class="fas fa-tachometer-alt" style="color: var(--dorado);"></i> Mi Panel
            </h1>
        </div>

        <!-- Tarjetas resumen -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="tarjeta-estadistica">
                    <div class="icono-estadistica" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="valor-estadistica"><?= $totalFavoritos ?? 0 ?></div>
                    <div class="etiqueta-estadistica">Ofertas guardadas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="tarjeta-estadistica">
                    <div class="icono-estadistica">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="valor-estadistica"><?= $estadisticas['total'] ?? 0 ?></div>
                    <div class="etiqueta-estadistica">Ofertas totales</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="tarjeta-estadistica">
                    <div class="icono-estadistica" style="background: rgba(234, 179, 8, 0.1); color: #eab308;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <?php
                    $aplicadas = 0;
                    foreach ($estadisticasFavoritos ?? [] as $ef) {
                        if ($ef['estado'] === 'aplicado') $aplicadas = $ef['total'];
                    }
                    ?>
                    <div class="valor-estadistica"><?= $aplicadas ?></div>
                    <div class="etiqueta-estadistica">Aplicadas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="tarjeta-estadistica">
                    <div class="icono-estadistica" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="valor-estadistica" style="font-size: 1.25rem;"><?= htmlspecialchars($usuario['provincia'] ?? 'N/A') ?></div>
                    <div class="etiqueta-estadistica">Tu provincia</div>
                </div>
            </div>
        </div>

        <!-- Estado de favoritos -->
        <?php if (!empty($estadisticasFavoritos)): ?>
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="tarjeta-panel">
                    <h5 style="font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1rem; margin-bottom: 1rem;">
                        <i class="fas fa-chart-pie" style="color: var(--dorado);"></i> Estado de mis candidaturas
                    </h5>
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <canvas id="graficoFavoritos" height="200"></canvas>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-2">
                                <?php foreach ($estadisticasFavoritos as $ef): ?>
                                    <?php
                                    $colorEstado = match($ef['estado']) {
                                        'interesado' => '--azul-medio',
                                        'aplicado' => '#16a34a',
                                        'descartado' => '#dc2626',
                                        default => 'var(--texto-terciario)'
                                    };
                                    $iconoEstado = match($ef['estado']) {
                                        'interesado' => 'fa-eye',
                                        'aplicado' => 'fa-paper-plane',
                                        'descartado' => 'fa-times-circle',
                                        default => 'fa-question'
                                    };
                                    ?>
                                    <div class="col-4">
                                        <div style="text-align: center; padding: 0.75rem; border-radius: var(--radio-md); background: var(--fondo-principal);">
                                            <i class="fas <?= $iconoEstado ?> fa-lg" style="color: <?= $colorEstado ?>;"></i>
                                            <h4 style="margin: 0.25rem 0 0; font-family: 'Sora', sans-serif;"><?= $ef['total'] ?></h4>
                                            <small style="color: var(--texto-terciario);"><?= ucfirst($ef['estado']) ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-3">
            <!-- Favoritos recientes -->
            <div class="col-lg-8">
                <div class="tarjeta-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 style="font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1rem; margin: 0;">
                            <i class="fas fa-heart" style="color: var(--rojo-favorito);"></i> Favoritos recientes
                        </h5>
                        <?php if (!empty($favoritosRecientes)): ?>
                            <a href="index.php?ruta=favoritos" class="btn btn-outline-primary btn-sm">Ver todos</a>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($favoritosRecientes)): ?>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($favoritosRecientes as $fav): ?>
                                <a href="index.php?ruta=ofertas/ver/<?= $fav['id_oferta'] ?>"
                                   style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; border-radius: var(--radio-md); border: 1px solid var(--borde-suave); text-decoration: none; color: var(--texto-principal); transition: all 0.2s;"
                                   onmouseover="this.style.borderColor='var(--azul-medio)'; this.style.background='var(--fondo-principal)';"
                                   onmouseout="this.style.borderColor='var(--borde-suave)'; this.style.background='transparent';">
                                    <div>
                                        <h6 style="margin: 0 0 0.15rem; font-size: 0.9rem; font-weight: 600;"><?= htmlspecialchars($fav['titulo']) ?></h6>
                                        <small style="color: var(--texto-terciario);">
                                            <i class="fas fa-building"></i> <?= htmlspecialchars($fav['empresa'] ?? '') ?>
                                            <i class="fas fa-map-marker-alt ms-2"></i> <?= htmlspecialchars($fav['provincia'] ?? '') ?>
                                        </small>
                                    </div>
                                    <?php
                                    $bgEstado = match($fav['estado']) {
                                        'aplicado' => 'background: #16a34a;',
                                        'descartado' => 'background: #dc2626;',
                                        default => 'background: var(--azul-medio);'
                                    };
                                    ?>
                                    <span class="etiqueta" style="<?= $bgEstado ?> color: white; font-size: 0.7rem;">
                                        <?= ucfirst($fav['estado']) ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-heart-broken fa-2x mb-2" style="color: var(--texto-terciario);"></i>
                            <p style="color: var(--texto-secundario); margin-bottom: 0.5rem;">No tienes ofertas guardadas</p>
                            <a href="index.php?ruta=ofertas" class="btn btn-primary btn-sm">Explorar ofertas</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Columna lateral -->
            <div class="col-lg-4 d-flex flex-column gap-3">
                <!-- Perfil -->
                <div class="tarjeta-panel">
                    <h5 style="font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1rem; margin-bottom: 0.75rem;">
                        <i class="fas fa-user" style="color: var(--dorado);"></i> Mi perfil
                    </h5>
                    <p style="margin: 0 0 0.25rem; font-size: 0.875rem;"><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
                    <p style="margin: 0 0 0.25rem; font-size: 0.875rem;"><strong>Sector:</strong> <?= htmlspecialchars($usuario['sector'] ?? 'Sin definir') ?></p>
                    <p style="margin: 0 0 0.75rem; font-size: 0.875rem;"><strong>Experiencia:</strong> <?= htmlspecialchars($usuario['nivel_experiencia'] ?? 'Sin definir') ?></p>
                    <a href="index.php?ruta=perfil" class="btn btn-outline-primary btn-sm w-100">Editar perfil</a>
                </div>

                <!-- Top provincias -->
                <div class="tarjeta-panel">
                    <h5 style="font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1rem; margin-bottom: 0.75rem;">
                        <i class="fas fa-map" style="color: var(--dorado);"></i> Top provincias
                    </h5>
                    <?php foreach (array_slice($estadisticas['por_provincia'] ?? [], 0, 5) as $prov): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-size: 0.875rem;"><?= htmlspecialchars($prov['provincia']) ?></span>
                            <span class="etiqueta etiqueta-provincia" style="font-size: 0.7rem;"><?= $prov['total'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Accesos rapidos -->
                <div class="tarjeta-panel">
                    <h5 style="font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1rem; margin-bottom: 0.75rem;">
                        <i class="fas fa-bolt" style="color: var(--dorado);"></i> Accesos rapidos
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="index.php?ruta=ofertas" class="btn btn-outline-primary btn-sm"><i class="fas fa-search"></i> Buscar ofertas</a>
                        <a href="index.php?ruta=chat-ia" class="btn btn-outline-primary btn-sm" style="border-color: var(--dorado); color: var(--dorado);"><i class="fas fa-robot"></i> Chat IA</a>
                        <a href="index.php?ruta=mapa" class="btn btn-outline-primary btn-sm"><i class="fas fa-map"></i> Ver mapa</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graficos -->
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="tarjeta-panel">
                    <h5 style="font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1rem; margin-bottom: 1rem;">
                        <i class="fas fa-chart-bar" style="color: var(--dorado);"></i> Ofertas por provincia
                    </h5>
                    <canvas id="graficoProvincias" height="280"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tarjeta-panel">
                    <h5 style="font-family: 'Sora', sans-serif; font-weight: 600; font-size: 1rem; margin-bottom: 1rem;">
                        <i class="fas fa-chart-pie" style="color: #16a34a;"></i> Ofertas por sector
                    </h5>
                    <canvas id="graficoSectores" height="280"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Colores del sistema de diseno
var coloresGrafico = [
    '#F59E0B', '#1E293B', '#334155', '#FCD34D', '#64748B',
    '#8b5cf6', '#f97316', '#14b8a6', '#475569', '#ec4899'
];

// Grafico de ofertas por provincia
var datosProvincias = <?= json_encode($estadisticas['por_provincia'] ?? []) ?>;
if (datosProvincias.length > 0) {
    new Chart(document.getElementById('graficoProvincias').getContext('2d'), {
        type: 'bar',
        data: {
            labels: datosProvincias.map(function(p) { return p.provincia; }),
            datasets: [{
                label: 'Ofertas',
                data: datosProvincias.map(function(p) { return p.total; }),
                backgroundColor: coloresGrafico,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
}

// Grafico de ofertas por sector/categoria
var datosSectores = <?= json_encode(array_slice($estadisticas['por_categoria'] ?? [], 0, 8)) ?>;
if (datosSectores.length > 0) {
    new Chart(document.getElementById('graficoSectores').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: datosSectores.map(function(c) { return c.categoria; }),
            datasets: [{
                data: datosSectores.map(function(c) { return c.total; }),
                backgroundColor: coloresGrafico,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11, family: 'Inter' }, padding: 12 }
                }
            }
        }
    });
}

// Grafico de estado de favoritos
var datosFavoritos = <?= json_encode($estadisticasFavoritos ?? []) ?>;
if (datosFavoritos.length > 0) {
    var coloresEstado = {
        'interesado': '#F59E0B',
        'aplicado': '#16a34a',
        'descartado': '#dc2626'
    };
    new Chart(document.getElementById('graficoFavoritos').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: datosFavoritos.map(function(e) { return e.estado.charAt(0).toUpperCase() + e.estado.slice(1); }),
            datasets: [{
                data: datosFavoritos.map(function(e) { return e.total; }),
                backgroundColor: datosFavoritos.map(function(e) { return coloresEstado[e.estado] || '#94a3b8'; }),
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
}
</script>
