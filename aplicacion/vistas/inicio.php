<!-- Hero -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-3">Encuentra tu empleo ideal en Castilla y Leon</h1>
        <p class="lead mb-4">Busqueda inteligente de ofertas de empleo con datos abiertos y asistencia de IA</p>
        <form action="index.php" method="GET" class="row g-2 justify-content-center">
            <input type="hidden" name="ruta" value="ofertas">
            <div class="col-md-6">
                <input type="text" name="texto" class="form-control form-control-lg" placeholder="Buscar ofertas de empleo...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-light btn-lg w-100">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Estadisticas -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-briefcase fa-2x text-primary mb-3"></i>
                        <h3 class="fw-bold"><?= number_format($estadisticas['total'] ?? 0) ?></h3>
                        <p class="text-muted">Ofertas disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                        <h3 class="fw-bold"><?= count($estadisticas['por_provincia'] ?? []) ?></h3>
                        <p class="text-muted">Provincias</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="fas fa-tags fa-2x text-primary mb-3"></i>
                        <h3 class="fw-bold"><?= count($estadisticas['por_categoria'] ?? []) ?></h3>
                        <p class="text-muted">Categorias</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ultimas ofertas -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Ultimas ofertas publicadas</h2>
        <div class="row g-4">
            <?php if (!empty($ultimasOfertas)): ?>
                <?php foreach ($ultimasOfertas as $oferta): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($oferta['titulo']) ?></h5>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-building"></i> <?= htmlspecialchars($oferta['empresa'] ?? 'Empresa no indicada') ?>
                                </p>
                                <span class="badge bg-info text-dark mb-2 align-self-start">
                                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($oferta['provincia'] ?? 'Sin especificar') ?>
                                </span>
                                <p class="card-text small flex-grow-1"><?= htmlspecialchars(mb_substr($oferta['descripcion'] ?? '', 0, 120)) ?>...</p>
                                <a href="index.php?ruta=ofertas/ver/<?= $oferta['id'] ?>" class="btn btn-outline-primary btn-sm mt-auto">Ver detalle</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay ofertas disponibles. Importa datos desde la API.</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="index.php?ruta=ofertas" class="btn btn-primary btn-lg">Ver todas las ofertas</a>
        </div>
    </div>
</section>
