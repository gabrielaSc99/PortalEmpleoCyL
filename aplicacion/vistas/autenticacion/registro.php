<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="tarjeta-auth">
                <div class="cabecera-auth">
                    <div class="icono-auth">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2>Crear Cuenta</h2>
                    <p style="color: var(--texto-secundario); font-size: 0.9rem;">Regístrate para guardar ofertas y recibir recomendaciones con IA</p>
                </div>

                <div class="cuerpo-auth">
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($errores as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?ruta=registro" method="POST" id="formularioRegistro">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre completo *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                   value="<?= htmlspecialchars($datos['nombre'] ?? '') ?>" placeholder="Tu nombre completo">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   value="<?= htmlspecialchars($datos['email'] ?? '') ?>" placeholder="tu@email.com">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contrasena" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control" id="contrasena" name="contrasena"
                                       required minlength="6" placeholder="Mínimo 6 caracteres">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirmar_contrasena" class="form-label">Confirmar *</label>
                                <input type="password" class="form-control" id="confirmar_contrasena"
                                       name="confirmar_contrasena" required placeholder="Repite la contraseña">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="provincia" class="form-label">Provincia</label>
                                <select class="form-select" id="provincia" name="provincia">
                                    <option value="">Seleccionar...</option>
                                    <option value="Ávila">Ávila</option>
                                    <option value="Burgos">Burgos</option>
                                    <option value="León">León</option>
                                    <option value="Palencia">Palencia</option>
                                    <option value="Salamanca">Salamanca</option>
                                    <option value="Segovia">Segovia</option>
                                    <option value="Soria">Soria</option>
                                    <option value="Valladolid">Valladolid</option>
                                    <option value="Zamora">Zamora</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sector" class="form-label">Sector profesional</label>
                                <input type="text" class="form-control" id="sector" name="sector"
                                       value="<?= htmlspecialchars($datos['sector'] ?? '') ?>" placeholder="Ej: Tecnología">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nivel_experiencia" class="form-label">Nivel de experiencia</label>
                            <select class="form-select" id="nivel_experiencia" name="nivel_experiencia">
                                <option value="sin_experiencia">Sin experiencia</option>
                                <option value="junior">Junior (0-2 años)</option>
                                <option value="intermedio">Intermedio (2-5 años)</option>
                                <option value="senior">Senior (5+ años)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-1"></i> Crear cuenta
                        </button>
                    </form>

                    <p class="text-center mt-3 mb-0" style="font-size: 0.875rem; color: var(--texto-secundario);">
                        ¿Ya tienes cuenta? <a href="index.php?ruta=login" style="color: var(--dorado); font-weight: 600;">Inicia sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
