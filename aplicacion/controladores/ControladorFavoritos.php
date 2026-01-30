<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
require_once __DIR__ . '/../modelos/Favorito.php';

/**
 * Controlador de favoritos
 * Gestiona las ofertas guardadas por el usuario
 */
class ControladorFavoritos extends Controlador {

    /**
     * Agregar oferta a favoritos (AJAX)
     */
    public function agregar() {
        $this->requiereAutenticacion();

        $datos = json_decode(file_get_contents('php://input'), true);
        $idOferta = $datos['id_oferta'] ?? null;

        if (!$idOferta) {
            $this->responderJSON(['exito' => false, 'mensaje' => 'ID de oferta requerido'], 400);
        }

        $resultado = Favorito::agregar($_SESSION['id_usuario'], $idOferta);

        if ($resultado) {
            $this->responderJSON(['exito' => true, 'mensaje' => 'Oferta anadida a favoritos']);
        } else {
            $this->responderJSON(['exito' => false, 'mensaje' => 'La oferta ya esta en favoritos']);
        }
    }

    /**
     * Eliminar oferta de favoritos (AJAX)
     */
    public function eliminar() {
        $this->requiereAutenticacion();

        $datos = json_decode(file_get_contents('php://input'), true);
        $idOferta = $datos['id_oferta'] ?? null;

        if (!$idOferta) {
            $this->responderJSON(['exito' => false, 'mensaje' => 'ID de oferta requerido'], 400);
        }

        Favorito::eliminar($_SESSION['id_usuario'], $idOferta);
        $this->responderJSON(['exito' => true, 'mensaje' => 'Oferta eliminada de favoritos']);
    }

    /**
     * Cambiar estado de favorito (AJAX)
     */
    public function cambiarEstado() {
        $this->requiereAutenticacion();

        $datos = json_decode(file_get_contents('php://input'), true);
        $idOferta = $datos['id_oferta'] ?? null;
        $estado = $datos['estado'] ?? null;

        if (!$idOferta || !$estado) {
            $this->responderJSON(['exito' => false, 'mensaje' => 'Datos incompletos'], 400);
        }

        Favorito::cambiarEstado($_SESSION['id_usuario'], $idOferta, $estado);
        $this->responderJSON(['exito' => true, 'mensaje' => 'Estado actualizado']);
    }

    /**
     * Listar favoritos del usuario
     */
    public function listar() {
        $this->requiereAutenticacion();

        $estado = $this->obtenerGet('estado');
        $favoritos = Favorito::obtenerPorUsuario($_SESSION['id_usuario'], $estado);

        $this->renderizar('usuario/favoritos', [
            'titulo' => 'Mis Favoritos',
            'favoritos' => $favoritos,
            'estadoFiltro' => $estado
        ]);
    }
}
