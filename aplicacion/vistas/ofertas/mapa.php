<div class="container py-4">
    <h1 class="h3 mb-4"><i class="fas fa-map text-primary"></i> Mapa de Ofertas de Empleo</h1>

    <div class="row g-4">
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div id="mapaOfertas"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Por provincia</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($estadisticas['por_provincia'] ?? [] as $prov): ?>
                            <a href="index.php?ruta=ofertas&provincia=<?= urlencode($prov['provincia']) ?>"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($prov['provincia']) ?>
                                <span class="badge bg-primary rounded-pill"><?= $prov['total'] ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Inicializar mapa centrado en Castilla y Leon
const mapa = L.map('mapaOfertas').setView([41.8, -4.5], 7);

// Capa de tiles (OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(mapa);

// Cargar marcadores desde la API
fetch('index.php?ruta=api/ofertas/mapa')
    .then(function(respuesta) { return respuesta.json(); })
    .then(function(datos) {
        if (datos.exito && datos.marcadores) {
            datos.marcadores.forEach(function(m) {
                // Tamano del circulo proporcional al numero de ofertas
                var radio = Math.max(10, Math.min(40, m.total / 2));

                var circulo = L.circleMarker([m.lat, m.lng], {
                    radius: radio,
                    fillColor: '#0d6efd',
                    color: '#0a58ca',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.6
                }).addTo(mapa);

                circulo.bindPopup(
                    '<strong>' + m.provincia + '</strong><br>' +
                    '<i class="fas fa-briefcase"></i> ' + m.total + ' ofertas<br>' +
                    '<a href="index.php?ruta=ofertas&provincia=' + encodeURIComponent(m.provincia) + '">Ver ofertas</a>'
                );

                // Etiqueta con el numero
                L.marker([m.lat, m.lng], {
                    icon: L.divIcon({
                        className: 'etiqueta-mapa',
                        html: '<span style="background:#0d6efd;color:white;padding:2px 6px;border-radius:10px;font-size:12px;font-weight:bold;">' + m.total + '</span>',
                        iconSize: [30, 20],
                        iconAnchor: [15, 10]
                    })
                }).addTo(mapa);
            });
        }
    });
</script>
