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
            'titulo' => 'Portal de Empleo CyL - Ofertas de trabajo en Castilla y Leon',
            'metaDescripcion' => 'Portal de Empleo Inteligente de Castilla y Leon. Encuentra ofertas de trabajo con busqueda por IA, recomendaciones personalizadas y datos actualizados de la Junta de CyL. ' . ($estadisticas['total'] ?? 0) . ' ofertas disponibles.',
            'metaKeywords' => 'empleo Castilla y Leon, trabajo CyL, ofertas empleo Valladolid, trabajo Leon, empleo Burgos, ofertas Salamanca, buscar trabajo, IA empleo',
            'estadisticas' => $estadisticas,
            'ultimasOfertas' => $ultimasOfertas['ofertas']
        ]);
    }
}
