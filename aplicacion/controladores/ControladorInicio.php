<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
require_once __DIR__ . '/../modelos/Oferta.php';

/**
 * Controlador de la pagina de inicio
 */
class ControladorInicio extends Controlador {

    /**
     * Mostrar pagina de inicio con estadisticas y ofertas recientes
     */
    public function inicio() {
        $estadisticas = Oferta::obtenerEstadisticas();
        $ultimasOfertas = Oferta::obtenerPaginadas(1, 6);

        $this->renderizar('inicio', [
            'titulo' => 'Portal de Empleo CyL',
            'estadisticas' => $estadisticas,
            'ultimasOfertas' => $ultimasOfertas['ofertas']
        ]);
    }
}
