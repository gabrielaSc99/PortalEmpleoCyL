<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
require_once __DIR__ . '/../nucleo/BaseDatos.php';
require_once __DIR__ . '/../modelos/Oferta.php';
require_once __DIR__ . '/../modelos/Favorito.php';

/**
 * Controlador de ofertas de empleo
 * Gestiona listado, busqueda, detalle y favoritos
 */
class ControladorOfertas extends Controlador {

    /**
     * Listar ofertas con paginacion y filtros
     */
    public function listar() {
        $pagina = (int)($this->obtenerGet('pagina', 1));
        $filtros = [
            'texto' => $this->obtenerGet('texto', ''),
            'provincia' => $this->obtenerGet('provincia', ''),
            'categoria' => $this->obtenerGet('categoria', ''),
            'tipo_contrato' => $this->obtenerGet('tipo_contrato', '')
        ];

        // Limpiar filtros vacios
        $filtros = array_filter($filtros);

        if (!empty($filtros)) {
            $resultado = Oferta::buscar($filtros, $pagina);
        } else {
            $resultado = Oferta::obtenerPaginadas($pagina);
        }

        $provincias = Oferta::obtenerProvincias();

        // Meta descripcion dinamica segun filtros
        $metaDesc = 'Encuentra ' . $resultado['total'] . ' ofertas de empleo en Castilla y Leon.';
        if (!empty($filtros['provincia'])) {
            $metaDesc = 'Ofertas de empleo en ' . $filtros['provincia'] . '. ' . $resultado['total'] . ' puestos disponibles.';
        }

        $this->renderizar('ofertas/listado', [
            'titulo' => 'Ofertas de Empleo en Castilla y Leon',
            'metaDescripcion' => $metaDesc . ' Busqueda con filtros y actualizacion diaria desde datos abiertos de la Junta.',
            'metaKeywords' => 'ofertas empleo, trabajo Castilla Leon, ' . (!empty($filtros['provincia']) ? 'empleo ' . $filtros['provincia'] . ', ' : '') . 'buscar trabajo, ofertas CyL',
            'ofertas' => $resultado['ofertas'],
            'total' => $resultado['total'],
            'paginas' => $resultado['paginas'],
            'paginaActual' => $resultado['pagina_actual'],
            'filtros' => $filtros,
            'provincias' => $provincias
        ]);
    }

    /**
     * Ver detalle de una oferta
     * @param int $id ID de la oferta
     */
    public function ver($id) {
        $oferta = Oferta::obtenerPorId($id);

        if (!$oferta) {
            http_response_code(404);
            $this->renderizar('plantillas/404', ['titulo' => 'Oferta no encontrada']);
            return;
        }

        $esFavorito = false;
        if (isset($_SESSION['id_usuario'])) {
            $esFavorito = Favorito::esFavorito($_SESSION['id_usuario'], $id);
        }

        // Meta descripcion con datos de la oferta
        $metaDesc = 'Oferta de empleo: ' . $oferta['titulo'];
        if (!empty($oferta['empresa'])) {
            $metaDesc .= ' en ' . $oferta['empresa'];
        }
        if (!empty($oferta['provincia'])) {
            $metaDesc .= ' (' . $oferta['provincia'] . ')';
        }
        $metaDesc .= '. Consulta requisitos y como aplicar.';

        $this->renderizar('ofertas/detalle', [
            'titulo' => $oferta['titulo'] . ' - Empleo CyL',
            'metaDescripcion' => $metaDesc,
            'metaKeywords' => 'oferta empleo, ' . ($oferta['empresa'] ?? '') . ', trabajo ' . ($oferta['provincia'] ?? 'Castilla Leon') . ', ' . $oferta['titulo'],
            'oferta' => $oferta,
            'esFavorito' => $esFavorito
        ]);
    }

    /**
     * Buscar ofertas via AJAX (respuesta JSON)
     */
    public function buscarAjax() {
        $filtros = [
            'texto' => $this->obtenerGet('texto', ''),
            'provincia' => $this->obtenerGet('provincia', ''),
            'categoria' => $this->obtenerGet('categoria', ''),
            'tipo_contrato' => $this->obtenerGet('tipo_contrato', '')
        ];
        $pagina = (int)($this->obtenerGet('pagina', 1));

        $filtros = array_filter($filtros);
        $resultado = Oferta::buscar($filtros, $pagina);

        $this->responderJSON([
            'exito' => true,
            'ofertas' => $resultado['ofertas'],
            'total' => $resultado['total'],
            'paginas' => $resultado['paginas'],
            'pagina_actual' => $resultado['pagina_actual']
        ]);
    }

    /**
     * Mostrar mapa interactivo de ofertas
     */
    public function mapa() {
        $estadisticas = Oferta::obtenerEstadisticas();
        $this->renderizar('ofertas/mapa', [
            'titulo' => 'Mapa de Ofertas de Empleo en Castilla y Leon',
            'metaDescripcion' => 'Mapa interactivo con ofertas de empleo en las 9 provincias de Castilla y Leon. Visualiza donde hay mas oportunidades laborales.',
            'metaKeywords' => 'mapa empleo Castilla Leon, ofertas por provincia, trabajo Valladolid, empleo Leon, Burgos, Salamanca',
            'estadisticas' => $estadisticas
        ]);
    }

    /**
     * Datos de ofertas con coordenadas para el mapa (JSON)
     */
    public function datosMapa() {
        // Coordenadas de las capitales de provincia de CyL
        // Incluye variantes con y sin tildes para coincidir con la BD
        $coordenadas = [
            'Ávila' => [40.6564, -4.6818],
            'Avila' => [40.6564, -4.6818],
            'Burgos' => [42.3440, -3.6969],
            'León' => [42.5987, -5.5671],
            'Leon' => [42.5987, -5.5671],
            'Palencia' => [42.0096, -4.5288],
            'Salamanca' => [40.9701, -5.6635],
            'Segovia' => [40.9429, -4.1088],
            'Soria' => [41.7636, -2.4649],
            'Valladolid' => [41.6523, -4.7245],
            'Zamora' => [41.5035, -5.7446]
        ];

        $porProvincia = BaseDatos::consultar(
            "SELECT provincia, COUNT(*) as total FROM ofertas WHERE provincia != '' GROUP BY provincia"
        );

        $marcadores = [];
        foreach ($porProvincia as $prov) {
            $nombre = trim($prov['provincia']);
            if (isset($coordenadas[$nombre])) {
                $marcadores[] = [
                    'provincia' => $nombre,
                    'total' => (int)$prov['total'],
                    'lat' => $coordenadas[$nombre][0],
                    'lng' => $coordenadas[$nombre][1]
                ];
            }
        }

        $this->responderJSON(['exito' => true, 'marcadores' => $marcadores]);
    }
}
