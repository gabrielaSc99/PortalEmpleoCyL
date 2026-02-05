<?php
/**
 * Clase de configuracion global del sistema
 */
class Configuracion {

    /** @var string Ruta base del proyecto */
    const RUTA_BASE = __DIR__ . '/../../';

    /** @var string Nombre del proyecto */
    const NOMBRE_PROYECTO = 'Portal de Empleo CyL';

    /** @var int Ofertas por pagina */
    const OFERTAS_POR_PAGINA = 12;

    /** @var int Tiempo de sesion en segundos (2 horas) */
    const TIEMPO_SESION = 7200;

    /**
     * Obtener configuracion de base de datos
     * @return array
     */
    public static function obtenerConfigBD() {
        return require self::RUTA_BASE . 'configuracion/base_datos.php';
    }

    /**
     * Obtener claves de API
     * @return array
     */
    public static function obtenerClavesAPI() {
        return require self::RUTA_BASE . 'configuracion/claves_api.php';
    }

    /**
     * Obtener URL base del proyecto
     * @return string
     */
    public static function obtenerUrlBase() {
        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocolo . '://' . $host . '/PortalEmpleoCyL/publico';
    }
}
