<section class="seccion-pagina" style="padding-bottom: 2rem;">
    <div class="container-fluid" style="max-width: 1400px;">
        <div class="cabecera-seccion">
            <h1 class="titulo-seccion">
                <i class="fas fa-map" style="color: var(--dorado);"></i> Mapa de Ofertas de Empleo
            </h1>
            <p style="color: var(--texto-secundario); font-size: 0.9rem;">Distribución geográfica de ofertas en Castilla y León</p>
        </div>

        <div class="row g-3">
            <div class="col-lg-9">
                <div class="tarjeta-panel" style="padding: 0; overflow: hidden;">
                    <div id="mapaOfertas"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="tarjeta-panel" style="padding: 0;">
                    <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--borde-suave);">
                        <h5 style="font-family: 'Sora', sans-serif; font-weight: 600; font-size: 0.95rem; margin: 0;">
                            <i class="fas fa-chart-bar" style="color: var(--dorado);"></i> Ofertas por provincia
                        </h5>
                    </div>
                    <div class="lista-provincias-mapa">
                        <?php
                        $totalOfertas = 0;
                        foreach ($estadisticas['por_provincia'] ?? [] as $prov) {
                            $totalOfertas += $prov['total'];
                        }
                        ?>
                        <?php foreach ($estadisticas['por_provincia'] ?? [] as $prov):
                            $porcentaje = $totalOfertas > 0 ? round(($prov['total'] / $totalOfertas) * 100) : 0;
                        ?>
                            <a href="index.php?ruta=ofertas&provincia=<?= urlencode($prov['provincia']) ?>"
                               style="display: block; padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--borde-suave); text-decoration: none; color: var(--texto-principal); transition: background 0.2s;"
                               onmouseover="this.style.background='var(--fondo-principal)'" onmouseout="this.style.background='transparent'">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong style="font-size: 0.85rem;"><?= htmlspecialchars($prov['provincia']) ?></strong>
                                    <span class="etiqueta etiqueta-provincia" style="font-size: 0.7rem;"><?= $prov['total'] ?></span>
                                </div>
                                <div style="height: 4px; background: var(--borde-suave); border-radius: 2px; overflow: hidden;">
                                    <div style="height: 100%; width: <?= $porcentaje ?>%; background: var(--azul-medio); border-radius: 2px; transition: width 0.5s;"></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div style="padding: 0.75rem 1.25rem; text-align: center; border-top: 1px solid var(--borde-suave);">
                        <small style="color: var(--texto-terciario);"><strong><?= $totalOfertas ?></strong> ofertas en total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    #mapaOfertas {
        height: 600px;
        border-radius: var(--radio-lg);
        z-index: 1;
    }

    .marcador-provincia {
        background: none;
        border: none;
    }

    .popup-provincia {
        text-align: center;
        min-width: 160px;
    }

    .popup-provincia h6 {
        margin: 0 0 8px 0;
        font-family: 'Sora', sans-serif;
        font-size: 15px;
        color: var(--texto-principal);
    }

    .popup-provincia .numero-ofertas {
        font-size: 28px;
        font-weight: 700;
        color: var(--dorado);
        line-height: 1;
    }

    .popup-provincia .texto-ofertas {
        font-size: 12px;
        color: var(--texto-terciario);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .popup-provincia .btn-ver {
        display: inline-block;
        padding: 5px 16px;
        background: var(--azul-medio);
        color: white;
        border-radius: 20px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: background 0.2s;
    }

    .popup-provincia .btn-ver:hover {
        background: var(--azul-institucional);
        color: white;
    }

    .leaflet-popup-content-wrapper {
        border-radius: var(--radio-lg);
        box-shadow: var(--sombra-media);
    }

    @media (max-width: 767.98px) {
        #mapaOfertas {
            height: 350px;
        }
    }
</style>

<script>
// Inicializar mapa centrado en Castilla y Leon
const mapa = L.map('mapaOfertas', {
    zoomControl: false,
    scrollWheelZoom: true
}).setView([41.65, -4.0], 7);

// Control de zoom en la esquina derecha
L.control.zoom({ position: 'topright' }).addTo(mapa);

// Capa de tiles con estilo limpio
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
    maxZoom: 19
}).addTo(mapa);

// Cargar marcadores desde la API
fetch('index.php?ruta=api/ofertas/mapa')
    .then(function(respuesta) { return respuesta.json(); })
    .then(function(datos) {
        if (datos.exito && datos.marcadores) {
            var maxOfertas = Math.max.apply(null, datos.marcadores.map(function(m) { return m.total; }));

            datos.marcadores.forEach(function(m) {
                var clase = 'marcador-burbuja';
                if (m.total >= maxOfertas * 0.7) {
                    clase += ' grande';
                } else if (m.total >= maxOfertas * 0.4) {
                    clase += ' mediano';
                }

                var icono = L.divIcon({
                    className: 'marcador-provincia',
                    html: '<div class="' + clase + '">' + m.total + '</div>',
                    iconSize: [60, 60],
                    iconAnchor: [30, 30],
                    popupAnchor: [0, -30]
                });

                var marcador = L.marker([m.lat, m.lng], { icon: icono }).addTo(mapa);

                marcador.bindPopup(
                    '<div class="popup-provincia">' +
                        '<h6><i class="fas fa-map-marker-alt" style="color: var(--dorado);"></i> ' + m.provincia + '</h6>' +
                        '<div class="numero-ofertas">' + m.total + '</div>' +
                        '<div class="texto-ofertas">ofertas disponibles</div>' +
                        '<a href="index.php?ruta=ofertas&provincia=' + encodeURIComponent(m.provincia) + '" class="btn-ver">' +
                            '<i class="fas fa-search"></i> Ver ofertas' +
                        '</a>' +
                    '</div>'
                );
            });
        }
    });
</script>
