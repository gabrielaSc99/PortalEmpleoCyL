<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
require_once __DIR__ . '/../nucleo/Configuracion.php';
require_once __DIR__ . '/../modelos/Oferta.php';
require_once __DIR__ . '/../modelos/Usuario.php';

/**
 * Controlador de Inteligencia Artificial
 * Integra OpenRouter para busqueda por lenguaje natural y recomendaciones
 */
class ControladorIA extends Controlador {

    /** @var string Clave de API */
    private $claveApi;

    /** @var string Modelo */
    private $modelo;

    /** @var string URL de la API */
    private $urlApi;

    /** @var string Ultimo error */
    private $ultimoError;

    public function __construct() {
        $config = Configuracion::obtenerClavesAPI();
        $this->claveApi = $config['ia']['clave'] ?? '';
        $this->modelo = $config['ia']['modelo'];
        $this->urlApi = $config['ia']['url'];
    }

    /**
     * Mostrar la vista del chat IA
     */
    public function mostrarChat() {
        $this->requiereAutenticacion();
        $this->renderizar('ofertas/chat-ia', [
            'titulo' => 'Asistente IA'
        ]);
    }

    /**
     * Buscar ofertas por lenguaje natural (AJAX)
     */
    public function buscarPorLenguajeNatural() {
        $this->requiereAutenticacion();

        $datos = json_decode(file_get_contents('php://input'), true);
        $consulta = $datos['consulta'] ?? '';

        if (empty($consulta)) {
            $this->responderJSON(['exito' => false, 'mensaje' => 'Escribe una consulta'], 400);
        }

        // Obtener provincias y categorias disponibles para contexto
        $provincias = Oferta::obtenerProvincias();
        $listadoProvincias = array_column($provincias, 'provincia');

        $usuario = Usuario::buscarPorId($_SESSION['id_usuario']);

        // Construir prompt
        $provinciaUsuario = $usuario['provincia'] ?? '';
        $prompt = "Eres un asistente de busqueda de empleo en Castilla y Leon (Espana).
Analiza la consulta del usuario y extrae filtros de busqueda.

REGLAS IMPORTANTES:
- Si el usuario menciona una provincia, usa esa provincia.
- Si NO menciona provincia, usa la provincia de su perfil: \"$provinciaUsuario\".
- El campo \"texto\" debe contener palabras clave cortas y genericas para buscar en titulos de ofertas (ej: \"programador\", \"administrativo\", \"soldador\").
- NO uses el campo \"categoria\" porque no hay categorias en la base de datos, dejalo siempre vacio.

Provincias validas: " . implode(', ', $listadoProvincias) . "

Perfil del usuario:
- Provincia: " . ($provinciaUsuario ?: 'No especificada') . "
- Sector: " . ($usuario['sector'] ?? 'No especificado') . "
- Experiencia: " . ($usuario['nivel_experiencia'] ?? 'No especificada') . "

Consulta: \"$consulta\"

Responde SOLO con JSON valido, sin markdown ni explicacion:
{
    \"texto\": \"palabras clave cortas\",
    \"provincia\": \"provincia del filtro\",
    \"categoria\": \"\",
    \"respuesta_usuario\": \"respuesta amigable en espanol\"
}";

        $respuestaIA = $this->llamarIA($prompt);

        if ($respuestaIA === false) {
            $this->responderJSON(['exito' => false, 'mensaje' => 'Error IA: ' . ($this->ultimoError ?? 'desconocido')]);
            return;
        }

        // Parsear respuesta JSON
        $filtrosIA = json_decode($respuestaIA, true);

        if (!$filtrosIA) {
            // Intentar extraer JSON del texto
            preg_match('/\{.*\}/s', $respuestaIA, $coincidencias);
            $filtrosIA = json_decode($coincidencias[0] ?? '{}', true);
        }

        // Buscar ofertas con los filtros
        $textoOriginal = $filtrosIA['texto'] ?? $consulta;
        $provinciaOriginal = $filtrosIA['provincia'] ?? '';
        $filtros = [
            'texto' => $textoOriginal,
            'provincia' => $provinciaOriginal
        ];
        $filtros = array_filter($filtros);

        $resultado = Oferta::buscar($filtros, 1, 10);
        $mensaje = $filtrosIA['respuesta_usuario'] ?? 'Aqui tienes los resultados de tu busqueda.';
        $nota = '';

        // Fallback: si no hay resultados, buscar solo por provincia
        if ($resultado['total'] == 0 && !empty($textoOriginal) && !empty($provinciaOriginal)) {
            $resultado = Oferta::buscar(['provincia' => $provinciaOriginal], 1, 10);
            if ($resultado['total'] > 0) {
                $nota = "No encontre ofertas de \"$textoOriginal\" en $provinciaOriginal, pero aqui tienes otras ofertas disponibles en esa zona.";
            }
        }

        // Fallback: si sigue sin resultados, buscar solo por texto
        if ($resultado['total'] == 0 && !empty($textoOriginal)) {
            $resultado = Oferta::buscar(['texto' => $textoOriginal], 1, 10);
            if ($resultado['total'] > 0) {
                $nota = "No encontre ofertas de \"$textoOriginal\" en $provinciaOriginal, pero hay algunas en otras provincias.";
            }
        }

        // Fallback: mostrar ofertas recientes
        if ($resultado['total'] == 0) {
            $resultado = Oferta::buscar([], 1, 10);
            $nota = "No encontre ofertas con esos criterios. Te muestro las ofertas mas recientes disponibles.";
        }

        $this->responderJSON([
            'exito' => true,
            'mensaje' => !empty($nota) ? $nota : $mensaje,
            'ofertas' => $resultado['ofertas'],
            'total' => $resultado['total'],
            'filtros_aplicados' => $filtros
        ]);
    }

    /**
     * Recomendar ofertas personalizadas (AJAX)
     */
    public function recomendarOfertas() {
        $this->requiereAutenticacion();

        $usuario = Usuario::buscarPorId($_SESSION['id_usuario']);
        $ofertas = Oferta::obtenerPaginadas(1, 20);

        if (empty($ofertas['ofertas'])) {
            $this->responderJSON([
                'exito' => true,
                'mensaje' => 'No hay ofertas disponibles para recomendar.',
                'recomendaciones' => []
            ]);
            return;
        }

        // Preparar resumen de ofertas para el prompt
        $resumenOfertas = array_map(function($o) {
            return [
                'id' => $o['id'],
                'titulo' => $o['titulo'],
                'provincia' => $o['provincia'],
                'empresa' => $o['empresa']
            ];
        }, $ofertas['ofertas']);

        $prompt = "Eres un asistente de empleo. Recomienda las 5 mejores ofertas para este usuario.

Perfil del usuario:
- Nombre: " . ($usuario['nombre'] ?? '') . "
- Provincia: " . ($usuario['provincia'] ?? 'No especificada') . "
- Sector: " . ($usuario['sector'] ?? 'No especificado') . "
- Experiencia: " . ($usuario['nivel_experiencia'] ?? 'No especificada') . "

Ofertas disponibles:
" . json_encode($resumenOfertas, JSON_UNESCAPED_UNICODE) . "

Responde UNICAMENTE con un JSON valido (sin markdown) con esta estructura:
{
    \"recomendaciones\": [
        {\"id\": 1, \"razon\": \"breve justificacion en espanol\"}
    ],
    \"mensaje\": \"mensaje personalizado para el usuario\"
}";

        $respuestaIA = $this->llamarIA($prompt);

        if ($respuestaIA === false) {
            $this->responderJSON(['exito' => false, 'mensaje' => 'Error IA: ' . ($this->ultimoError ?? 'desconocido')]);
            return;
        }

        $resultado = json_decode($respuestaIA, true);
        if (!$resultado) {
            preg_match('/\{.*\}/s', $respuestaIA, $coincidencias);
            $resultado = json_decode($coincidencias[0] ?? '{}', true);
        }

        // Obtener datos completos de las ofertas recomendadas
        $recomendaciones = [];
        foreach ($resultado['recomendaciones'] ?? [] as $rec) {
            $oferta = Oferta::obtenerPorId($rec['id']);
            if ($oferta) {
                $oferta['razon_recomendacion'] = $rec['razon'] ?? '';
                $recomendaciones[] = $oferta;
            }
        }

        $this->responderJSON([
            'exito' => true,
            'mensaje' => $resultado['mensaje'] ?? 'Aqui tienes mis recomendaciones.',
            'recomendaciones' => $recomendaciones
        ]);
    }

    /**
     * Llamar a la API de OpenRouter
     * @param string $prompt Texto del prompt
     * @return string|false Respuesta de texto o false en caso de error
     */
    private function llamarIA($prompt) {
        $datos = [
            'model' => $this->modelo,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.3,
            'max_tokens' => 1024
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->claveApi
        ];

        $ch = curl_init($this->urlApi);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($datos),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($respuesta === false || $httpCode !== 200) {
            if ($httpCode === 429) {
                $this->ultimoError = 'Cuota de la API agotada. Intentalo mas tarde.';
            } elseif ($httpCode === 401) {
                $this->ultimoError = 'Clave de API no valida.';
            } else {
                $this->ultimoError = $curlError ?: "HTTP $httpCode - $respuesta";
            }
            return false;
        }

        $resultado = json_decode($respuesta, true);

        return $resultado['choices'][0]['message']['content'] ?? false;
    }
}
