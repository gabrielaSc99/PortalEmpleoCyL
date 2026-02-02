<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal de Empleo Inteligente de Castilla y León - Busca ofertas de empleo con IA">
    <title><?= htmlspecialchars($titulo ?? 'Portal de Empleo CyL') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <!-- Navegacion con glassmorphism -->
    <nav class="navbar navbar-expand-lg navbar-portal sticky-top" id="navbarPrincipal" role="navigation" aria-label="Navegación principal">
        <div class="container">
            <a class="navbar-brand" href="index.php?ruta=inicio" aria-label="Inicio - Portal de Empleo CyL">
                <span class="icono-marca"><i class="fas fa-briefcase"></i></span>
                Empleo CyL
            </a>

            <button class="hamburguesa-btn d-lg-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navPrincipal" aria-controls="navPrincipal"
                    aria-expanded="false" aria-label="Abrir menú de navegación"
                    id="botonHamburguesa">
                <span></span><span></span><span></span>
            </button>

            <div class="collapse navbar-collapse" id="navPrincipal">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?ruta=inicio"><i class="fas fa-home"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?ruta=ofertas"><i class="fas fa-search"></i> Ofertas</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?ruta=favoritos"><i class="fas fa-heart"></i> Favoritos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link boton-nav-acento" href="index.php?ruta=chat-ia"><i class="fas fa-robot"></i> Chat IA</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['nombre_usuario']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="index.php?ruta=perfil"><i class="fas fa-user-edit"></i> Mi Perfil</a></li>
                                <?php if (($_SESSION['rol_usuario'] ?? '') === 'administrador'): ?>
                                    <li><a class="dropdown-item" href="index.php?ruta=admin"><i class="fas fa-cogs"></i> Administración</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?ruta=logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?ruta=login"><i class="fas fa-sign-in-alt"></i> Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link boton-nav-acento ms-lg-2" href="index.php?ruta=registro">
                                <i class="fas fa-user-plus"></i> Registro
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main>
        <?php if (isset($contenidoVista) && file_exists($contenidoVista)): ?>
            <?php require $contenidoVista; ?>
        <?php endif; ?>
    </main>

    <!-- Pie de pagina moderno -->
    <footer class="pie-pagina">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="marca-pie">
                        <span class="icono-marca-pie"><i class="fas fa-briefcase"></i></span>
                        Empleo CyL
                    </div>
                    <p class="descripcion-pie">Portal de empleo inteligente con datos abiertos de la Junta de Castilla y León. Búsqueda con IA y actualización automática.</p>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="titulo-columna-pie">Explorar</div>
                    <a href="index.php?ruta=ofertas" class="enlace-pie">Ofertas</a>
                    <a href="index.php?ruta=chat-ia" class="enlace-pie">Chat IA</a>
                </div>
                <div class="col-6 col-lg-2">
                    <div class="titulo-columna-pie">Cuenta</div>
                    <a href="index.php?ruta=login" class="enlace-pie">Iniciar sesión</a>
                    <a href="index.php?ruta=registro" class="enlace-pie">Crear cuenta</a>
                    <a href="index.php?ruta=perfil" class="enlace-pie">Mi perfil</a>
                </div>
                <div class="col-lg-4">
                    <div class="titulo-columna-pie">Datos abiertos</div>
                    <p class="descripcion-pie mb-0">Datos proporcionados por la Junta de Castilla y León a través de su portal de datos abiertos.</p>
                </div>
            </div>
            <div class="linea-inferior d-flex flex-wrap justify-content-between">
                <span>&copy; 2025 Portal de Empleo Inteligente - Proyecto Intermodular DAW</span>
                <span>Hecho con <i class="fas fa-heart" style="color: var(--peligro);"></i> en Castilla y León</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/aplicacion.js"></script>
    <script src="js/validacion.js"></script>
</body>
</html>
