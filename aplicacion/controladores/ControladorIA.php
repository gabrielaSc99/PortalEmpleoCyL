<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
require_once __DIR__ . '/../nucleo/Configuracion.php';
require_once __DIR__ . '/../modelos/Oferta.php';
require_once __DIR__ . '/../modelos/Usuario.php';

/**
 * Controlador de Inteligencia Artificial
 * Integra Groq (Llama 3.3) para busqueda por lenguaje natural y recomendaciones
 * Incluye modo fallback: si la API falla, busca directamente en BD
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
        $modoFallback = false;

        // Si la API falla y NO es por limite de cuota, usar modo fallback
        if ($respuestaIA === false) {
            if ($this->ultimoError === 'LIMITE_ALCANZADO') {
                // Solo bloquear al usuario si es rate limit real
                $this->responderJSON(['exito' => false, 'mensaje' => 'Error IA: LIMITE_ALCANZADO']);
                return;
            }
            // Modo fallback: buscar directamente en BD sin IA
            $modoFallback = true;
        }

        // Parsear respuesta JSON si tenemos respuesta de IA
        $filtrosIA = [];
        if (!$modoFallback && $respuestaIA) {
            $filtrosIA = json_decode($respuestaIA, true);
            if (!$filtrosIA) {
                // Intentar extraer JSON del texto
                preg_match('/\{.*\}/s', $respuestaIA, $coincidencias);
                $filtrosIA = json_decode($coincidencias[0] ?? '{}', true);
            }
        }

        // En modo fallback, extraer palabras clave basicas de la consulta
        if ($modoFallback) {
            $filtrosIA = $this->extraerFiltrosBasicos($consulta, $provinciaUsuario, $listadoProvincias);
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

        // Aviso informativo en modo fallback
        if ($modoFallback) {
            $nota = 'Busqueda realizada directamente (el asistente IA no esta disponible temporalmente).';
        }

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
        $modoFallback = false;

        // Si la API falla y NO es por limite de cuota, usar modo fallback
        if ($respuestaIA === false) {
            if ($this->ultimoError === 'LIMITE_ALCANZADO') {
                $this->responderJSON(['exito' => false, 'mensaje' => 'Error IA: LIMITE_ALCANZADO']);
                return;
            }
            $modoFallback = true;
        }

        $resultado = [];
        $recomendaciones = [];
        $mensajeFinal = '';

        if (!$modoFallback && $respuestaIA) {
            $resultado = json_decode($respuestaIA, true);
            if (!$resultado) {
                preg_match('/\{.*\}/s', $respuestaIA, $coincidencias);
                $resultado = json_decode($coincidencias[0] ?? '{}', true);
            }

            // Obtener datos completos de las ofertas recomendadas por IA
            foreach ($resultado['recomendaciones'] ?? [] as $rec) {
                $oferta = Oferta::obtenerPorId($rec['id']);
                if ($oferta) {
                    $oferta['razon_recomendacion'] = $rec['razon'] ?? '';
                    $recomendaciones[] = $oferta;
                }
            }
            $mensajeFinal = $resultado['mensaje'] ?? 'Aqui tienes mis recomendaciones.';
        } else {
            // Modo fallback: recomendar por provincia y sector del usuario
            $recomendaciones = $this->recomendarSinIA($usuario, $ofertas['ofertas']);
            $mensajeFinal = 'Recomendaciones basadas en tu perfil (el asistente IA no esta disponible temporalmente).';
        }

        $this->responderJSON([
            'exito' => true,
            'mensaje' => $mensajeFinal,
            'recomendaciones' => $recomendaciones
        ]);
    }

    /**
     * Llamar a la API de Groq (Llama 3.3)
     * @param string $prompt Texto del prompt
     * @return string|false Respuesta de texto o false en caso de error
     */
    private function llamarIA($prompt) {
        $datos = [
            'model' => $this->modelo,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un asistente de busqueda de empleo. Responde SOLO con JSON valido, sin markdown ni explicaciones adicionales.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3,
            'max_tokens' => 1024
        ];

        $ch = curl_init($this->urlApi);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->claveApi
            ],
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
                $this->ultimoError = 'LIMITE_ALCANZADO';
            } elseif ($httpCode === 401) {
                $this->ultimoError = 'Clave de API no valida.';
            } else {
                $this->ultimoError = $curlError ?: "HTTP $httpCode";
            }
            return false;
        }

        $resultado = json_decode($respuesta, true);

        return $resultado['choices'][0]['message']['content'] ?? false;
    }

    /**
     * Recomendar ofertas sin IA (modo fallback)
     * Prioriza ofertas de la misma provincia y sector del usuario
     * @param array $usuario Datos del usuario
     * @param array $ofertas Lista de ofertas disponibles
     * @return array Ofertas recomendadas (max 5)
     */
    private function recomendarSinIA($usuario, $ofertas) {
        $provinciaUsuario = $usuario['provincia'] ?? '';
        $sectorUsuario = mb_strtolower($usuario['sector'] ?? '');

        // Puntuar cada oferta
        $ofertasPuntuadas = [];
        foreach ($ofertas as $oferta) {
            $puntos = 0;
            $razon = [];

            // Coincidencia de provincia
            if (!empty($provinciaUsuario) && $oferta['provincia'] === $provinciaUsuario) {
                $puntos += 10;
                $razon[] = 'En tu provincia';
            }

            // Coincidencia de sector/titulo
            if (!empty($sectorUsuario) && mb_stripos($oferta['titulo'], $sectorUsuario) !== false) {
                $puntos += 5;
                $razon[] = 'Relacionado con tu sector';
            }

            // Oferta reciente (bonus)
            $puntos += 1;

            $oferta['_puntos'] = $puntos;
            $oferta['razon_recomendacion'] = !empty($razon) ? implode('. ', $razon) : 'Oferta destacada';
            $ofertasPuntuadas[] = $oferta;
        }

        // Ordenar por puntuacion descendente
        usort($ofertasPuntuadas, function($a, $b) {
            return $b['_puntos'] - $a['_puntos'];
        });

        // Devolver top 5 sin el campo de puntos
        $resultado = array_slice($ofertasPuntuadas, 0, 5);
        foreach ($resultado as &$oferta) {
            unset($oferta['_puntos']);
        }

        return $resultado;
    }

    /**
     * Extraer filtros basicos de la consulta sin IA (modo fallback)
     * @param string $consulta Consulta del usuario
     * @param string $provinciaUsuario Provincia del perfil del usuario
     * @param array $provinciasValidas Lista de provincias validas
     * @return array Filtros extraidos
     */
    private function extraerFiltrosBasicos($consulta, $provinciaUsuario, $provinciasValidas) {
        $consulta = mb_strtolower($consulta);
        $provinciaDetectada = '';

        // Detectar provincia mencionada en la consulta
        foreach ($provinciasValidas as $prov) {
            if (mb_stripos($consulta, mb_strtolower($prov)) !== false) {
                $provinciaDetectada = $prov;
                // Eliminar la provincia del texto de busqueda
                $consulta = str_ireplace($prov, '', $consulta);
                break;
            }
        }

        // Si no se menciono provincia, usar la del perfil
        if (empty($provinciaDetectada) && !empty($provinciaUsuario)) {
            $provinciaDetectada = $provinciaUsuario;
        }

        // Limpiar palabras comunes que no aportan a la busqueda
        $palabrasIgnorar = ['busco', 'quiero', 'trabajo', 'empleo', 'ofertas', 'de', 'en', 'para', 'como', 'un', 'una', 'el', 'la', 'los', 'las', 'cerca', 'mi', 'zona'];
        $palabras = preg_split('/\s+/', trim($consulta));
        $palabrasClave = array_filter($palabras, function($p) use ($palabrasIgnorar) {
            return strlen($p) > 2 && !in_array(mb_strtolower($p), $palabrasIgnorar);
        });

        $textoBusqueda = implode(' ', $palabrasClave);

        return [
            'texto' => $textoBusqueda,
            'provincia' => $provinciaDetectada,
            'respuesta_usuario' => 'Aqui tienes los resultados de tu busqueda.'
        ];
    }
}
