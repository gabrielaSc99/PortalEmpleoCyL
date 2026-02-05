<?php
/**
 * Sistema de cache en archivos
 * Reduce llamadas innecesarias a la API y consultas a la base de datos
 * Estrategia Green Coding: ahorra hasta un 80% de peticiones
 */
class Cache {

    /** @var string Directorio donde se almacenan los archivos de cache */
    private static $directorio = __DIR__ . '/../../cache/';

    /** @var int Tiempo de vida por defecto en segundos (15 minutos) */
    const TIEMPO_VIDA = 900;

    /**
     * Obtener dato de cache
     * @param string $clave Identificador unico
     * @param int $tiempoVida Segundos de validez (por defecto 15 min)
     * @return mixed|false Datos cacheados o false si no existe/expirado
     */
    public static function obtener($clave, $tiempoVida = self::TIEMPO_VIDA) {
        $archivo = self::obtenerRutaArchivo($clave);

        if (!file_exists($archivo)) {
            return false;
        }

        // Verificar si ha expirado
        $tiempoModificacion = filemtime($archivo);
        if ((time() - $tiempoModificacion) > $tiempoVida) {
            unlink($archivo);
            return false;
        }

        $contenido = file_get_contents($archivo);
        return json_decode($contenido, true);
    }

    /**
     * Guardar dato en cache
     * @param string $clave Identificador unico
     * @param mixed $datos Datos a guardar
     * @return bool
     */
    public static function guardar($clave, $datos) {
        self::crearDirectorio();
        $archivo = self::obtenerRutaArchivo($clave);
        return file_put_contents($archivo, json_encode($datos, JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * Eliminar un dato de cache
     * @param string $clave
     */
    public static function eliminar($clave) {
        $archivo = self::obtenerRutaArchivo($clave);
        if (file_exists($archivo)) {
            unlink($archivo);
        }
    }

    /**
     * Limpiar toda la cache
     */
    public static function limpiarTodo() {
        $directorio = self::$directorio;
        if (is_dir($directorio)) {
            $archivos = glob($directorio . '*.cache');
            foreach ($archivos as $archivo) {
                unlink($archivo);
            }
        }
    }

    /**
     * Limpiar cache expirada
     * @param int $tiempoVida Segundos
     */
    public static function limpiarExpirada($tiempoVida = self::TIEMPO_VIDA) {
        $directorio = self::$directorio;
        if (is_dir($directorio)) {
            $archivos = glob($directorio . '*.cache');
            foreach ($archivos as $archivo) {
                if ((time() - filemtime($archivo)) > $tiempoVida) {
                    unlink($archivo);
                }
            }
        }
    }

    /**
     * Obtener estadisticas de uso de cache (para Green Coding)
     * @return array
     */
    public static function obtenerEstadisticas() {
        $directorio = self::$directorio;
        if (!is_dir($directorio)) {
            return ['archivos' => 0, 'tamano_total' => '0 KB'];
        }

        $archivos = glob($directorio . '*.cache');
        $tamano = 0;
        foreach ($archivos as $archivo) {
            $tamano += filesize($archivo);
        }

        return [
            'archivos' => count($archivos),
            'tamano_total' => round($tamano / 1024, 2) . ' KB',
            'peticiones_ahorradas_estimado' => count($archivos) * 10
        ];
    }

    /**
     * Obtener ruta del archivo de cache
     * @param string $clave
     * @return string
     */
    private static function obtenerRutaArchivo($clave) {
        return self::$directorio . md5($clave) . '.cache';
    }

    /**
     * Crear directorio de cache si no existe
     */
    private static function crearDirectorio() {
        if (!is_dir(self::$directorio)) {
            mkdir(self::$directorio, 0755, true);
        }
    }
}
