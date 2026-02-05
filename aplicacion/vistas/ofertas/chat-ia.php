<section class="seccion-pagina">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Cabecera del chat -->
                <div class="cabecera-chat d-flex justify-content-between align-items-center">
                    <div>
                        <h2 style="font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1.25rem; margin: 0;">
                            <i class="fas fa-robot" style="color: var(--dorado);"></i> Asistente de Empleo IA
                        </h2>
                        <p style="color: var(--texto-terciario); font-size: 0.8rem; margin: 0;">Búsqueda inteligente con lenguaje natural</p>
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="obtenerRecomendaciones()">
                        <i class="fas fa-magic me-1"></i> Recomendaciones
                    </button>
                </div>

                <div class="contenedor-chat">
                    <!-- Area de mensajes -->
                    <div id="mensajesChat" class="area-chat">
                        <!-- Mensaje de bienvenida -->
                        <div class="mensaje-chat mensaje-ia">
                            <div class="mensaje-cabecera">
                                <i class="fas fa-robot"></i> <strong>Asistente IA</strong>
                            </div>
                            <div class="mensaje-contenido">
                                <p>Hola <?= htmlspecialchars($_SESSION['nombre_usuario'] ?? '') ?>! Soy tu asistente de búsqueda de empleo.</p>
                                <p>Puedes preguntarme cosas como:</p>
                                <ul>
                                    <li><em>"Busco trabajo de programador en Valladolid"</em></li>
                                    <li><em>"Ofertas de empleo en León para alguien sin experiencia"</em></li>
                                    <li><em>"Trabajo de administrativo cerca de Salamanca"</em></li>
                                </ul>
                                <p>También puedes pulsar <strong>Recomendaciones</strong> para que analice tu perfil y te sugiera las mejores ofertas.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Campo de entrada -->
                    <div class="entrada-chat">
                        <div class="input-group">
                            <input type="text" class="form-control" id="consultaIA"
                                   placeholder="Escribe tu búsqueda en lenguaje natural..."
                                   autocomplete="off" aria-label="Consulta al asistente IA">
                            <button class="btn btn-primary" onclick="enviarConsultaIA()" aria-label="Enviar consulta">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var URL_BASE = '<?= Configuracion::obtenerUrlBase() ?>/index.php';
</script>
<script src="js/chat-ia.js?v=3"></script>
