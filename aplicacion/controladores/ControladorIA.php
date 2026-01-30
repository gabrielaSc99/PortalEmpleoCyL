<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
require_once __DIR__ . '/../nucleo/Configuracion.php';
require_once __DIR__ . '/../modelos/Oferta.php';
require_once __DIR__ . '/../modelos/Usuario.php';

/**
 * Controlador de Inteligencia Artificial
 * Integra Google Gemini para busqueda por lenguaje natural y recomendaciones
 */
class ControladorIA extends Controlador {

    /** @var string Clave de API */
    private $claveApi;

    /** @var string Modelo de Gemini */
    private $modelo;

    /** @var string URL base de la API */
    private $urlBase;

    public function __construct() {
        $config = Configuracion::obtenerClavesAPI();
        $this->claveApi = $config['gemini']['clave'];
        $this->modelo = $config['gemini']['modelo'];
        $this->urlBase = $config['gemini']['url'];
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

        // Construir prompt para Gemini
        $prompt = "Eres un asistente de busqueda de empleo en Castilla y Leon (Espana).
El usuario busca ofertas de empleo. Analiza su consulta y extrae los filtros de busqueda.

Provincias disponibles: " . implode(', ', $listadoProvincias) . "

Perfil del usuario:
- Provincia: " . ($usuario['provincia'] ?? 'No especificada') . "
- Sector: " . ($usuario['sector'] ?? 'No especificado') . "
- Experiencia: " . ($usuario['nivel_experiencia'] ?? 'No especificada') . "

Consulta del usuario: \"$consulta\"

Responde UNICAMENTE con un JSON valido (sin markdown, sin explicacion) con esta estructura:
{
    \"texto\": \"palabras clave para buscar en titulo y descripcion\",
    \"provincia\": \"nombre de provincia si se menciona, o vacio\",
    \"categoria\": \"categoria si se detecta, o vacio\",
    \"respuesta_usuario\": \"respuesta amigable en espanol explicando que vas a buscar\"
}";

        $respuestaIA = $this->llamarGemini($prompt);

        if ($respuestaIA === false) {
            $this->responderJSON(['exito' => false, 'mensaje' => 'Error al conectar con la IA']);
            return;
        }

        // Parsear respuesta JSON de Gemini
        $filtrosIA = json_decode($respuestaIA, true);

        if (!$filtrosIA) {
            // Intentar extraer JSON del texto
            preg_match('/\{.*\}/s', $respuestaIA, $coincidencias);
            $filtrosIA = json_decode($coincidencias[0] ?? '{}', true);
        }

        // Buscar ofertas con los filtros
        $filtros = [
            'texto' => $filtrosIA['texto'] ?? $consulta,
            'provincia' => $filtrosIA['provincia'] ?? '',
            'categoria' => $filtrosIA['categoria'] ?? ''
        ];
        $filtros = array_filter($filtros);

        $resultado = Oferta::buscar($filtros, 1, 10);

        $this->responderJSON([
            'exito' => true,
            'mensaje' => $filtrosIA['respuesta_usuario'] ?? 'Aqui tienes los resultados de tu busqueda.',
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

        $respuestaIA = $this->llamarGemini($prompt);

        if ($respuestaIA === false) {
            $this->responderJSON(['exito' => false, 'mensaje' => 'Error al conectar con la IA']);
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
     * Llamar a la API de Google Gemini
     * @param string $prompt Texto del prompt
     * @return string|false Respuesta de texto o false en caso de error
     */
    private function llamarGemini($prompt) {
        $url = $this->urlBase . $this->modelo . ':generateContent?key=' . $this->claveApi;

        $datos = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.3,
                'maxOutputTokens' => 1024
            ]
        ];

        $opciones = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => json_encode($datos),
                'timeout' => 30
            ]
        ];

        $contexto = stream_context_create($opciones);
        $respuesta = @file_get_contents($url, false, $contexto);

        if ($respuesta === false) {
            return false;
        }

        $resultado = json_decode($respuesta, true);

        // Extraer texto de la respuesta de Gemini
        return $resultado['candidates'][0]['content']['parts'][0]['text'] ?? false;
    }
}
