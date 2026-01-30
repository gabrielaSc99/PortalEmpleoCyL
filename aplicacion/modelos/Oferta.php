<?php
require_once __DIR__ . '/../nucleo/BaseDatos.php';

/**
 * Modelo de Oferta de empleo
 * Gestiona operaciones CRUD de ofertas
 */
class Oferta {

    /**
     * Obtener ofertas con paginacion
     * @param int $pagina Numero de pagina
     * @param int $porPagina Resultados por pagina
     * @return array ['ofertas' => [], 'total' => int, 'paginas' => int]
     */
    public static function obtenerPaginadas($pagina = 1, $porPagina = 12) {
        $offset = ($pagina - 1) * $porPagina;

        $total = BaseDatos::consultarUno("SELECT COUNT(*) as total FROM ofertas")['total'];
        $ofertas = BaseDatos::consultar(
            "SELECT * FROM ofertas ORDER BY fecha_publicacion DESC LIMIT ? OFFSET ?",
            [$porPagina, $offset]
        );

        return [
            'ofertas' => $ofertas,
            'total' => $total,
            'paginas' => ceil($total / $porPagina),
            'pagina_actual' => $pagina
        ];
    }

    /**
     * Buscar ofertas con filtros
     * @param array $filtros Filtros de busqueda
     * @param int $pagina
     * @param int $porPagina
     * @return array
     */
    public static function buscar($filtros = [], $pagina = 1, $porPagina = 12) {
        $condiciones = [];
        $parametros = [];

        if (!empty($filtros['texto'])) {
            $condiciones[] = "(titulo LIKE ? OR descripcion LIKE ? OR empresa LIKE ?)";
            $texto = '%' . $filtros['texto'] . '%';
            $parametros[] = $texto;
            $parametros[] = $texto;
            $parametros[] = $texto;
        }

        if (!empty($filtros['provincia'])) {
            $condiciones[] = "provincia = ?";
            $parametros[] = $filtros['provincia'];
        }

        if (!empty($filtros['categoria'])) {
            $condiciones[] = "categoria = ?";
            $parametros[] = $filtros['categoria'];
        }

        if (!empty($filtros['tipo_contrato'])) {
            $condiciones[] = "tipo_contrato = ?";
            $parametros[] = $filtros['tipo_contrato'];
        }

        $where = '';
        if (!empty($condiciones)) {
            $where = 'WHERE ' . implode(' AND ', $condiciones);
        }

        // Contar total
        $totalResultado = BaseDatos::consultarUno(
            "SELECT COUNT(*) as total FROM ofertas $where",
            $parametros
        );
        $total = $totalResultado['total'];

        // Obtener resultados paginados
        $offset = ($pagina - 1) * $porPagina;
        $parametros[] = $porPagina;
        $parametros[] = $offset;

        $ofertas = BaseDatos::consultar(
            "SELECT * FROM ofertas $where ORDER BY fecha_publicacion DESC LIMIT ? OFFSET ?",
            $parametros
        );

        return [
            'ofertas' => $ofertas,
            'total' => $total,
            'paginas' => ceil($total / $porPagina),
            'pagina_actual' => $pagina
        ];
    }

    /**
     * Obtener una oferta por su ID
     * @param int $id
     * @return array|false
     */
    public static function obtenerPorId($id) {
        return BaseDatos::consultarUno("SELECT * FROM ofertas WHERE id = ?", [$id]);
    }

    /**
     * Obtener lista de provincias disponibles
     * @return array
     */
    public static function obtenerProvincias() {
        return BaseDatos::consultar(
            "SELECT DISTINCT provincia FROM ofertas WHERE provincia IS NOT NULL AND provincia != '' ORDER BY provincia"
        );
    }

    /**
     * Obtener lista de categorias disponibles
     * @return array
     */
    public static function obtenerCategorias() {
        return BaseDatos::consultar(
            "SELECT DISTINCT categoria FROM ofertas WHERE categoria IS NOT NULL AND categoria != '' ORDER BY categoria"
        );
    }

    /**
     * Obtener estadisticas de ofertas
     * @return array
     */
    public static function obtenerEstadisticas() {
        $porProvincia = BaseDatos::consultar(
            "SELECT provincia, COUNT(*) as total FROM ofertas WHERE provincia != '' GROUP BY provincia ORDER BY total DESC"
        );
        $porCategoria = BaseDatos::consultar(
            "SELECT categoria, COUNT(*) as total FROM ofertas WHERE categoria != '' GROUP BY categoria ORDER BY total DESC LIMIT 10"
        );
        $total = BaseDatos::consultarUno("SELECT COUNT(*) as total FROM ofertas")['total'];

        return [
            'total' => $total,
            'por_provincia' => $porProvincia,
            'por_categoria' => $porCategoria
        ];
    }

    /**
     * Insertar o actualizar oferta desde la API externa
     * @param array $datos
     * @return string 'insertada', 'actualizada' o 'existente'
     */
    public static function insertarOActualizar($datos) {
        $existe = BaseDatos::consultarUno(
            "SELECT id FROM ofertas WHERE id_fuente = ?",
            [$datos['id_fuente']]
        );

        if (!$existe) {
            BaseDatos::ejecutar(
                "INSERT INTO ofertas (id_fuente, titulo, descripcion, empresa, provincia, categoria, salario, tipo_contrato, url, fecha_publicacion)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $datos['id_fuente'], $datos['titulo'], $datos['descripcion'],
                    $datos['empresa'], $datos['provincia'], $datos['categoria'],
                    $datos['salario'] ?? '', $datos['tipo_contrato'] ?? '',
                    $datos['url'] ?? '', $datos['fecha_publicacion'] ?? date('Y-m-d')
                ]
            );
            return 'insertada';
        }
        return 'existente';
    }
}
