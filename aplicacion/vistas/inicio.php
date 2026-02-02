<!-- Hero Section con buscador prominente -->
<section class="seccion-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 contenido-hero">
                <div class="contador-hero revelar">
                    <span class="punto-activo"></span>
                    <span class="numero-animado" data-valor="<?= $estadisticas['total'] ?? 0 ?>"><?= number_format($estadisticas['total'] ?? 0) ?></span>
                    ofertas disponibles
                </div>

                <h1 class="titulo-hero mt-3">
                    Encuentra tu empleo ideal en <span class="acento-dorado">Castilla y León</span>
                </h1>
                <p class="subtitulo-hero">
                    Búsqueda inteligente con IA, datos abiertos actualizados y recomendaciones personalizadas para tu perfil profesional.
                </p>

                <!-- Buscador estilo Google -->
                <form action="index.php" method="GET" class="buscador-hero d-flex gap-2" role="search" aria-label="Buscar ofertas de empleo">
                    <input type="hidden" name="ruta" value="ofertas">
                    <input type="text" name="texto" class="form-control input-busqueda flex-grow-1"
                           placeholder="Buscar por puesto, empresa o palabra clave..."
                           aria-label="Término de búsqueda"
                           autocomplete="off">
                    <button type="submit" class="btn boton-buscar-hero" aria-label="Buscar">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                </form>

                <!-- Filtros rapidos (pills) -->
                <div class="filtros-rapidos">
                    <?php
                    $provinciasRapidas = ['Valladolid', 'León', 'Burgos', 'Salamanca'];
                    foreach ($provinciasRapidas as $prov):
                    ?>
                        <a href="index.php?ruta=ofertas&provincia=<?= urlencode($prov) ?>" class="filtro-pill">
                            <i class="fas fa-map-marker-alt"></i> <?= $prov ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="index.php?ruta=chat-ia" class="filtro-pill">
                        <i class="fas fa-robot"></i> Buscar con IA
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mapa de ofertas -->
<section class="seccion-pagina" style="padding-bottom: 2rem;">
    <div class="container">
        <div class="cabecera-seccion">
            <h2 class="titulo-seccion">
                <i class="fas fa-map" style="color: var(--dorado);"></i> Mapa de Ofertas
            </h2>
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
        height: 450px;
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
            height: 300px;
        }
    }
</style>

<script>
// Inicializar mapa centrado en Castilla y León
var mapaInicio = L.map('mapaOfertas', {
    zoomControl: false,
    scrollWheelZoom: true
}).setView([41.65, -4.0], 7);

L.control.zoom({ position: 'topright' }).addTo(mapaInicio);

L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
    maxZoom: 19
}).addTo(mapaInicio);

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

                var marcador = L.marker([m.lat, m.lng], { icon: icono }).addTo(mapaInicio);

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

<!-- Últimas ofertas publicadas -->
<section class="seccion-pagina">
    <div class="container">
        <div class="cabecera-seccion d-flex justify-content-between align-items-end">
            <div>
                <h2 class="titulo-seccion">Últimas ofertas</h2>
                <p class="subtitulo-seccion mb-0">Las ofertas más recientes del portal de datos abiertos</p>
            </div>
            <a href="index.php?ruta=ofertas" class="enlace-ver-todo d-none d-md-inline-flex">
                Ver todas <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="row g-3">
            <?php if (!empty($ultimasOfertas)): ?>
                <?php foreach ($ultimasOfertas as $indice => $oferta): ?>
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
                                        <span class="etiqueta etiqueta-categoria">
                                            <?= htmlspecialchars($oferta['categoria']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($oferta['tipo_contrato'])): ?>
                                        <span class="etiqueta etiqueta-contrato">
                                            <?= htmlspecialchars($oferta['tipo_contrato']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($oferta['salario'])): ?>
                                        <span class="etiqueta etiqueta-salario">
                                            <i class="fas fa-euro-sign"></i> <?= htmlspecialchars($oferta['salario']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php
                                    // Badge "Nueva" si fue publicada en los ultimos 3 dias
                                    if (!empty($oferta['fecha_publicacion'])) {
                                        $diasDesde = (time() - strtotime($oferta['fecha_publicacion'])) / 86400;
                                        if ($diasDesde <= 3):
                                    ?>
                                        <span class="etiqueta etiqueta-nueva">
                                            <i class="fas fa-sparkles"></i> Nueva
                                        </span>
                                    <?php endif; } ?>
                                </div>
                                <p class="descripcion-oferta">
                                    <?= htmlspecialchars(mb_substr($oferta['descripcion'] ?? '', 0, 130)) ?>...
                                </p>
                            </div>
                            <div class="pie-tarjeta">
                                <span class="fecha-oferta">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?= htmlspecialchars($oferta['fecha_publicacion'] ?? '') ?>
                                </span>
                                <div class="d-flex gap-2 align-items-center">
                                    <?php if (isset($_SESSION['id_usuario'])): ?>
                                        <button class="boton-favorito" onclick="alternarFavorito(<?= $oferta['id'] ?>, this)"
                                                aria-label="Guardar en favoritos" title="Guardar en favoritos">
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
                    <i class="fas fa-inbox fa-3x mb-3" style="color: var(--texto-terciario);"></i>
                    <h4>No hay ofertas disponibles</h4>
                    <p style="color: var(--texto-secundario);">Importa datos desde el panel de administración para comenzar.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Boton ver todas (movil) -->
        <div class="text-center mt-4 d-md-none">
            <a href="index.php?ruta=ofertas" class="btn btn-primary">
                Ver todas las ofertas <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>


<!-- Call to action -->
<section class="seccion-pagina revelar">
    <div class="container">
        <div class="text-center" style="max-width: 600px; margin: 0 auto;">
            <div class="icono-estadistica dorado mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.4rem;">
                <i class="fas fa-robot"></i>
            </div>
            <h2 class="titulo-seccion">Busca con inteligencia artificial</h2>
            <p style="color: var(--texto-secundario);">
                Describe lo que buscas en lenguaje natural y nuestra IA encontrará las mejores ofertas para ti.
            </p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="index.php?ruta=chat-ia" class="btn btn-primary">
                    <i class="fas fa-robot me-1"></i> Probar Chat IA
                </a>
                <a href="index.php?ruta=registro" class="btn btn-outline-primary">
                    <i class="fas fa-user-plus me-1"></i> Crear cuenta gratis
                </a>
            </div>
        </div>
    </div>
</section>
