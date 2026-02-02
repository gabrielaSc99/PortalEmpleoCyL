<div class="container" style="padding: 2rem 0 3rem;">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="tarjeta-auth">
                <div class="cabecera-auth">
                    <div class="icono-auth">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <h2>Mi Perfil</h2>
                    <p style="color: var(--texto-secundario); font-size: 0.9rem;">Actualiza tus datos para mejorar las recomendaciones</p>
                </div>

                <div class="cuerpo-auth">
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-1"></i> <?= htmlspecialchars($mensaje) ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?ruta=perfil" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                   value="<?= htmlspecialchars($usuario['nombre']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" disabled
                                   style="background: var(--fondo-principal);">
                            <small style="color: var(--texto-terciario); font-size: 0.78rem;">El email no se puede cambiar</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="provincia" class="form-label">Provincia</label>
                                <select class="form-select" id="provincia" name="provincia">
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    $provinciasCyL = ['Ávila','Burgos','León','Palencia','Salamanca','Segovia','Soria','Valladolid','Zamora'];
                                    foreach ($provinciasCyL as $prov):
                                    ?>
                                        <option value="<?= $prov ?>" <?= ($usuario['provincia'] ?? '') === $prov ? 'selected' : '' ?>><?= $prov ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sector" class="form-label">Sector profesional</label>
                                <input type="text" class="form-control" id="sector" name="sector"
                                       value="<?= htmlspecialchars($usuario['sector'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nivel_experiencia" class="form-label">Nivel de experiencia</label>
                            <select class="form-select" id="nivel_experiencia" name="nivel_experiencia">
                                <option value="sin_experiencia" <?= ($usuario['nivel_experiencia'] ?? '') === 'sin_experiencia' ? 'selected' : '' ?>>Sin experiencia</option>
                                <option value="junior" <?= ($usuario['nivel_experiencia'] ?? '') === 'junior' ? 'selected' : '' ?>>Junior</option>
                                <option value="intermedio" <?= ($usuario['nivel_experiencia'] ?? '') === 'intermedio' ? 'selected' : '' ?>>Intermedio</option>
                                <option value="senior" <?= ($usuario['nivel_experiencia'] ?? '') === 'senior' ? 'selected' : '' ?>>Senior</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Guardar cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
