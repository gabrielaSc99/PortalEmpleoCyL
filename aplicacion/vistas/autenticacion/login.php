<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="tarjeta-auth">
                <div class="cabecera-auth">
                    <div class="icono-auth">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <h2>Iniciar Sesión</h2>
                    <p style="color: var(--texto-secundario); font-size: 0.9rem;">Accede a tu cuenta para gestionar tus ofertas</p>
                </div>

                <div class="cuerpo-auth">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?ruta=login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   placeholder="tu@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                   aria-label="Correo electrónico">
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required
                                   placeholder="Tu contraseña" aria-label="Contraseña">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-1"></i> Entrar
                        </button>
                    </form>

                    <p class="text-center mt-3 mb-0" style="font-size: 0.875rem; color: var(--texto-secundario);">
                        ¿No tienes cuenta? <a href="index.php?ruta=registro" style="color: var(--dorado); font-weight: 600;">Regístrate aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
