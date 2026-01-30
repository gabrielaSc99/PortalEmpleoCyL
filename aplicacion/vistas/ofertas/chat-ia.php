<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-robot"></i> Asistente de Empleo IA</h5>
                    <button class="btn btn-outline-light btn-sm" onclick="obtenerRecomendaciones()">
                        <i class="fas fa-magic"></i> Recomendaciones
                    </button>
                </div>

                <!-- Area de mensajes -->
                <div class="card-body p-0">
                    <div id="mensajesChat" class="area-chat">
                        <!-- Mensaje de bienvenida -->
                        <div class="mensaje-chat mensaje-ia">
                            <div class="mensaje-cabecera">
                                <i class="fas fa-robot"></i> <strong>Asistente IA</strong>
                            </div>
                            <div class="mensaje-contenido">
                                <p>Hola <?= htmlspecialchars($_SESSION['nombre_usuario'] ?? '') ?>! Soy tu asistente de busqueda de empleo.</p>
                                <p>Puedes preguntarme cosas como:</p>
                                <ul>
                                    <li><em>"Busco trabajo de programador en Valladolid"</em></li>
                                    <li><em>"Ofertas de empleo en Leon para alguien sin experiencia"</em></li>
                                    <li><em>"Trabajo de administrativo cerca de Salamanca"</em></li>
                                </ul>
                                <p>Tambien puedes pulsar <strong>Recomendaciones</strong> para que analice tu perfil y te sugiera las mejores ofertas.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campo de entrada -->
                <div class="card-footer">
                    <div class="input-group">
                        <input type="text" class="form-control" id="consultaIA"
                               placeholder="Escribe tu busqueda en lenguaje natural..."
                               autocomplete="off">
                        <button class="btn btn-primary" onclick="enviarConsultaIA()">
                            <i class="fas fa-paper-plane"></i> Enviar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/chat-ia.js"></script>
