<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt text-primary"></i> Iniciar Sesion</h2>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?ruta=login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   placeholder="tu@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label"><i class="fas fa-lock"></i> Contrasena</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required
                                   placeholder="Tu contrasena">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt"></i> Entrar
                        </button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        No tienes cuenta? <a href="index.php?ruta=registro">Registrate aqui</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
