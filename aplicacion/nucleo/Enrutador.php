<?php
/**
 * Enrutador principal del sistema
 * Gestiona las rutas URL y las dirige al controlador correspondiente
 */
class Enrutador {

    /** @var array Rutas registradas */
    private static $rutas = [];

    /**
     * Registrar una ruta GET
     * @param string $ruta URL de la ruta
     * @param string $controlador Nombre del controlador
     * @param string $metodo Nombre del metodo
     */
    public static function get($ruta, $controlador, $metodo) {
        self::$rutas['GET'][$ruta] = ['controlador' => $controlador, 'metodo' => $metodo];
    }

    /**
     * Registrar una ruta POST
     * @param string $ruta URL de la ruta
     * @param string $controlador Nombre del controlador
     * @param string $metodo Nombre del metodo
     */
    public static function post($ruta, $controlador, $metodo) {
        self::$rutas['POST'][$ruta] = ['controlador' => $controlador, 'metodo' => $metodo];
    }

    /**
     * Resolver la ruta actual y ejecutar el controlador correspondiente
     */
    public static function resolver() {
        $metodoHTTP = $_SERVER['REQUEST_METHOD'];
        $uri = $_GET['ruta'] ?? 'inicio';
        $uri = trim($uri, '/');

        // Buscar ruta exacta
        if (isset(self::$rutas[$metodoHTTP][$uri])) {
            $destino = self::$rutas[$metodoHTTP][$uri];
            $nombreControlador = $destino['controlador'];
            $metodo = $destino['metodo'];

            require_once __DIR__ . '/../controladores/' . $nombreControlador . '.php';
            $controlador = new $nombreControlador();
            $controlador->$metodo();
            return;
        }

        // Buscar rutas con parametros (ej: ofertas/ver/123)
        foreach (self::$rutas[$metodoHTTP] ?? [] as $ruta => $destino) {
            $patron = preg_replace('/\{([a-zA-Z]+)\}/', '([a-zA-Z0-9_-]+)', $ruta);
            if (preg_match('#^' . $patron . '$#', $uri, $coincidencias)) {
                $nombreControlador = $destino['controlador'];
                $metodo = $destino['metodo'];

                require_once __DIR__ . '/../controladores/' . $nombreControlador . '.php';
                $controlador = new $nombreControlador();
                array_shift($coincidencias);
                $controlador->$metodo(...$coincidencias);
                return;
            }
        }

        // Ruta no encontrada
        http_response_code(404);
        require_once __DIR__ . '/../vistas/plantillas/404.php';
    }
}
