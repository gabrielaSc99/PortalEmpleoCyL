<?php
/**
 * Clase para gestionar la conexion y consultas a la base de datos
 * Implementa patron Singleton con PDO
 */
class BaseDatos {

    /** @var PDO|null Instancia de conexion */
    private static $conexion = null;

    /**
     * Obtener conexion a la base de datos (Singleton)
     * @return PDO
     */
    public static function obtenerConexion() {
        if (self::$conexion === null) {
            $config = require __DIR__ . '/../../configuracion/base_datos.php';
            $dsn = "mysql:host={$config['host']};port={$config['puerto']};dbname={$config['nombre_bd']};charset={$config['charset']}";

            try {
                self::$conexion = new PDO($dsn, $config['usuario'], $config['contrasena'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                die("Error de conexion a la base de datos: " . $e->getMessage());
            }
        }
        return self::$conexion;
    }

    /**
     * Ejecutar consulta preparada (INSERT, UPDATE, DELETE)
     * @param string $sql Consulta SQL
     * @param array $parametros Parametros para la consulta
     * @return PDOStatement
     */
    public static function ejecutar($sql, $parametros = []) {
        $sentencia = self::obtenerConexion()->prepare($sql);
        $sentencia->execute($parametros);
        return $sentencia;
    }

    /**
     * Consultar datos (SELECT)
     * @param string $sql Consulta SQL
     * @param array $parametros Parametros para la consulta
     * @return array
     */
    public static function consultar($sql, $parametros = []) {
        $sentencia = self::ejecutar($sql, $parametros);
        return $sentencia->fetchAll();
    }

    /**
     * Consultar un solo registro
     * @param string $sql Consulta SQL
     * @param array $parametros Parametros
     * @return array|false
     */
    public static function consultarUno($sql, $parametros = []) {
        $sentencia = self::ejecutar($sql, $parametros);
        return $sentencia->fetch();
    }

    /**
     * Obtener el ultimo ID insertado
     * @return string
     */
    public static function ultimoId() {
        return self::obtenerConexion()->lastInsertId();
    }
}
