<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
require_once __DIR__ . '/../modelos/Usuario.php';
require_once __DIR__ . '/../modelos/Favorito.php';
require_once __DIR__ . '/../modelos/Oferta.php';

/**
 * Controlador de perfil de usuario y dashboard
 */
class ControladorUsuario extends Controlador {

    /**
     * Mostrar dashboard del usuario
     */
    public function dashboard() {
        $this->requiereAutenticacion();

        $usuario = Usuario::buscarPorId($_SESSION['id_usuario']);
        $totalFavoritos = Favorito::contar($_SESSION['id_usuario']);
        $favoritosRecientes = Favorito::obtenerPorUsuario($_SESSION['id_usuario']);
        $estadisticas = Oferta::obtenerEstadisticas();
        $estadisticasFavoritos = Favorito::estadisticasPorEstado($_SESSION['id_usuario']);

        $this->renderizar('usuario/dashboard', [
            'titulo' => 'Mi Panel',
            'usuario' => $usuario,
            'totalFavoritos' => $totalFavoritos,
            'favoritosRecientes' => array_slice($favoritosRecientes, 0, 5),
            'estadisticas' => $estadisticas,
            'estadisticasFavoritos' => $estadisticasFavoritos
        ]);
    }

    /**
     * Mostrar y procesar perfil
     */
    public function perfil() {
        $this->requiereAutenticacion();

        $usuario = Usuario::buscarPorId($_SESSION['id_usuario']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $this->obtenerPost('nombre'),
                'provincia' => $this->obtenerPost('provincia', ''),
                'sector' => $this->obtenerPost('sector', ''),
                'nivel_experiencia' => $this->obtenerPost('nivel_experiencia', 'sin_experiencia')
            ];

            Usuario::actualizarPerfil($_SESSION['id_usuario'], $datos);
            $_SESSION['nombre_usuario'] = $datos['nombre'];

            $usuario = Usuario::buscarPorId($_SESSION['id_usuario']);
            $this->renderizar('usuario/perfil', [
                'titulo' => 'Mi Perfil',
                'usuario' => $usuario,
                'mensaje' => 'Perfil actualizado correctamente'
            ]);
            return;
        }

        $this->renderizar('usuario/perfil', [
            'titulo' => 'Mi Perfil',
            'usuario' => $usuario
        ]);
    }
}
