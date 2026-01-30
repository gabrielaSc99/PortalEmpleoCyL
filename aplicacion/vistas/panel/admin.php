<div class="container py-4">
    <h1 class="h3 mb-4"><i class="fas fa-cogs text-primary"></i> Panel de Administracion</h1>

    <!-- Estadisticas generales -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-primary shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-briefcase fa-2x text-primary mb-2"></i>
                    <h3><?= number_format($totalOfertas) ?></h3>
                    <p class="text-muted mb-0">Ofertas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                    <h3><?= $totalUsuarios ?></h3>
                    <p class="text-muted mb-0">Usuarios</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                    <h3><?= $totalFavoritos ?></h3>
                    <p class="text-muted mb-0">Favoritos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-leaf fa-2x text-info mb-2"></i>
                    <h3><?= $estadisticasCache['archivos'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Cache activa (<?= $estadisticasCache['tamano_total'] ?? '0 KB' ?>)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sincronizacion -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-sync"></i> Historial de sincronizaciones</h5>
                    <form action="index.php?ruta=admin/sincronizar" method="POST" class="d-inline">
                        <button type="submit" class="btn btn-primary btn-sm"
                                onclick="this.disabled=true; this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Sincronizando...'; this.form.submit();">
                            <i class="fas fa-sync"></i> Sincronizar ahora
                        </button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover tabla-admin mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Anadidas</th>
                                    <th>Actualizadas</th>
                                    <th>Estado</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sincronizaciones)): ?>
                                    <?php foreach ($sincronizaciones as $sync): ?>
                                        <tr>
                                            <td><?= $sync['fecha_sincronizacion'] ?></td>
                                            <td><span class="badge bg-success"><?= $sync['registros_anadidos'] ?></span></td>
                                            <td><span class="badge bg-info"><?= $sync['registros_actualizados'] ?></span></td>
                                            <td>
                                                <span class="estado-<?= $sync['estado'] ?>">
                                                    <i class="fas fa-<?= $sync['estado'] === 'exitoso' ? 'check-circle' : ($sync['estado'] === 'fallido' ? 'times-circle' : 'exclamation-circle') ?>"></i>
                                                    <?= ucfirst($sync['estado']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($sync['mensaje_error'] ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">No hay sincronizaciones registradas</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Green Coding info -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-leaf text-success"></i> Green Coding - Ahorro de recursos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <h4 class="text-success"><?= $estadisticasCache['peticiones_ahorradas_estimado'] ?? 0 ?></h4>
                            <p class="text-muted small">Consultas BD ahorradas</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="text-success">80%</h4>
                            <p class="text-muted small">Reduccion de consultas</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="text-success">15 min</h4>
                            <p class="text-muted small">TTL de cache</p>
                        </div>
                    </div>
                    <p class="small text-muted mt-2 mb-0">
                        <i class="fas fa-info-circle"></i> El sistema de cache almacena consultas frecuentes (provincias, categorias, estadisticas)
                        durante 15 minutos, evitando repetir consultas costosas a la base de datos. Esto reduce el consumo
                        energetico del servidor y mejora el tiempo de respuesta.
                    </p>
                </div>
            </div>
        </div>

        <!-- Usuarios recientes -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Ultimos usuarios</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($ultimosUsuarios as $usr): ?>
                            <div class="list-group-item">
                                <h6 class="mb-1"><?= htmlspecialchars($usr['nombre']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($usr['email']) ?></small>
                                <br><small class="text-muted"><?= htmlspecialchars($usr['provincia'] ?? '') ?> - <?= $usr['fecha_creacion'] ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
