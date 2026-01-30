<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
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
        $categorias = Oferta::obtenerCategorias();

        $this->renderizar('ofertas/listado', [
            'titulo' => 'Ofertas de Empleo',
            'ofertas' => $resultado['ofertas'],
            'total' => $resultado['total'],
            'paginas' => $resultado['paginas'],
            'paginaActual' => $resultado['pagina_actual'],
            'filtros' => $filtros,
            'provincias' => $provincias,
            'categorias' => $categorias
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

        $this->renderizar('ofertas/detalle', [
            'titulo' => $oferta['titulo'],
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
}
