<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
require_once __DIR__ . '/../modelos/Usuario.php';

/**
 * Controlador de autenticacion
 * Gestiona registro, login y logout de usuarios
 */
class ControladorAutenticacion extends Controlador {

    /**
     * Mostrar formulario de login
     */
    public function mostrarLogin() {
        if (isset($_SESSION['id_usuario'])) {
            $this->redirigir('ofertas');
        }
        $this->renderizar('autenticacion/login', [
            'titulo' => 'Iniciar Sesion'
        ]);
    }

    /**
     * Procesar login
     */
    public function procesarLogin() {
        $email = $this->obtenerPost('email');
        $contrasena = $_POST['contrasena'] ?? '';

        if (empty($email) || empty($contrasena)) {
            $this->renderizar('autenticacion/login', [
                'titulo' => 'Iniciar Sesion',
                'error' => 'Todos los campos son obligatorios'
            ]);
            return;
        }

        $usuario = Usuario::verificarCredenciales($email, $contrasena);

        if ($usuario) {
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nombre_usuario'] = $usuario['nombre'];
            $_SESSION['email_usuario'] = $usuario['email'];
            $_SESSION['rol_usuario'] = $usuario['rol'];
            $this->redirigir('ofertas');
        } else {
            $this->renderizar('autenticacion/login', [
                'titulo' => 'Iniciar Sesion',
                'error' => 'Email o contrasena incorrectos'
            ]);
        }
    }

    /**
     * Mostrar formulario de registro
     */
    public function mostrarRegistro() {
        if (isset($_SESSION['id_usuario'])) {
            $this->redirigir('ofertas');
        }
        $this->renderizar('autenticacion/registro', [
            'titulo' => 'Registrarse'
        ]);
    }

    /**
     * Procesar registro de nuevo usuario
     */
    public function procesarRegistro() {
        $nombre = $this->obtenerPost('nombre');
        $email = $this->obtenerPost('email');
        $contrasena = $_POST['contrasena'] ?? '';
        $confirmarContrasena = $_POST['confirmar_contrasena'] ?? '';
        $provincia = $this->obtenerPost('provincia', '');
        $sector = $this->obtenerPost('sector', '');
        $nivelExperiencia = $this->obtenerPost('nivel_experiencia', 'sin_experiencia');

        // Validaciones
        $errores = [];

        if (empty($nombre)) {
            $errores[] = 'El nombre es obligatorio';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Introduce un email valido';
        }

        if (strlen($contrasena) < 6) {
            $errores[] = 'La contrasena debe tener al menos 6 caracteres';
        }

        if ($contrasena !== $confirmarContrasena) {
            $errores[] = 'Las contrasenas no coinciden';
        }

        if (Usuario::existeEmail($email)) {
            $errores[] = 'Este email ya esta registrado';
        }

        if (!empty($errores)) {
            $this->renderizar('autenticacion/registro', [
                'titulo' => 'Registrarse',
                'errores' => $errores,
                'datos' => $_POST
            ]);
            return;
        }

        // Crear usuario
        $idUsuario = Usuario::crear($email, $contrasena, $nombre, $provincia, $sector, $nivelExperiencia);

        // Iniciar sesion automaticamente
        $_SESSION['id_usuario'] = $idUsuario;
        $_SESSION['nombre_usuario'] = $nombre;
        $_SESSION['email_usuario'] = $email;
        $_SESSION['rol_usuario'] = 'usuario';

        $this->redirigir('ofertas');
    }

    /**
     * Cerrar sesion
     */
    public function cerrarSesion() {
        session_destroy();
        $this->redirigir('login');
    }
}
