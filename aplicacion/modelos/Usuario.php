<?php
require_once __DIR__ . '/../nucleo/BaseDatos.php';

/**
 * Modelo de Usuario
 * Gestiona operaciones CRUD de usuarios en la base de datos
 */
class Usuario {

    /**
     * Crear un nuevo usuario
     * @param string $email Correo electronico
     * @param string $contrasena Contrasena sin encriptar
     * @param string $nombre Nombre completo
     * @param string $provincia Provincia del usuario
     * @param string $sector Sector profesional
     * @param string $nivelExperiencia Nivel de experiencia
     * @return int ID del usuario creado
     */
    public static function crear($email, $contrasena, $nombre, $provincia = '', $sector = '', $nivelExperiencia = 'sin_experiencia') {
        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
        BaseDatos::ejecutar(
            "INSERT INTO usuarios (email, contrasena, nombre, provincia, sector, nivel_experiencia)
             VALUES (?, ?, ?, ?, ?, ?)",
            [$email, $contrasenaHash, $nombre, $provincia, $sector, $nivelExperiencia]
        );
        return BaseDatos::ultimoId();
    }

    /**
     * Buscar usuario por email
     * @param string $email
     * @return array|false
     */
    public static function buscarPorEmail($email) {
        return BaseDatos::consultarUno(
            "SELECT * FROM usuarios WHERE email = ?",
            [$email]
        );
    }

    /**
     * Buscar usuario por ID
     * @param int $id
     * @return array|false
     */
    public static function buscarPorId($id) {
        return BaseDatos::consultarUno(
            "SELECT id, email, nombre, provincia, sector, nivel_experiencia, fecha_creacion
             FROM usuarios WHERE id = ?",
            [$id]
        );
    }

    /**
     * Verificar credenciales de login
     * @param string $email
     * @param string $contrasena
     * @return array|false Datos del usuario si las credenciales son correctas
     */
    public static function verificarCredenciales($email, $contrasena) {
        $usuario = self::buscarPorEmail($email);
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            unset($usuario['contrasena']);
            return $usuario;
        }
        return false;
    }

    /**
     * Actualizar perfil del usuario
     * @param int $id
     * @param array $datos
     * @return bool
     */
    public static function actualizarPerfil($id, $datos) {
        BaseDatos::ejecutar(
            "UPDATE usuarios SET nombre = ?, provincia = ?, sector = ?, nivel_experiencia = ? WHERE id = ?",
            [$datos['nombre'], $datos['provincia'], $datos['sector'], $datos['nivel_experiencia'], $id]
        );
        return true;
    }

    /**
     * Verificar si un email ya existe
     * @param string $email
     * @return bool
     */
    public static function existeEmail($email) {
        $resultado = BaseDatos::consultarUno(
            "SELECT id FROM usuarios WHERE email = ?",
            [$email]
        );
        return $resultado !== false;
    }
}
