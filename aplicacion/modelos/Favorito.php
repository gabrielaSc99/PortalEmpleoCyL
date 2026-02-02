<?php
require_once __DIR__ . '/../nucleo/BaseDatos.php';

/**
 * Modelo de Favoritos
 * Gestiona las ofertas guardadas por cada usuario
 */
class Favorito {

    /**
     * Agregar oferta a favoritos
     * @param int $idUsuario
     * @param int $idOferta
     * @param string $estado 'interesado', 'aplicado', 'descartado'
     * @return bool
     */
    public static function agregar($idUsuario, $idOferta, $estado = 'interesado') {
        $existe = BaseDatos::consultarUno(
            "SELECT id FROM favoritos WHERE id_usuario = ? AND id_oferta = ?",
            [$idUsuario, $idOferta]
        );

        if ($existe) {
            return false;
        }

        BaseDatos::ejecutar(
            "INSERT INTO favoritos (id_usuario, id_oferta, estado) VALUES (?, ?, ?)",
            [$idUsuario, $idOferta, $estado]
        );
        return true;
    }

    /**
     * Eliminar oferta de favoritos
     * @param int $idUsuario
     * @param int $idOferta
     * @return bool
     */
    public static function eliminar($idUsuario, $idOferta) {
        BaseDatos::ejecutar(
            "DELETE FROM favoritos WHERE id_usuario = ? AND id_oferta = ?",
            [$idUsuario, $idOferta]
        );
        return true;
    }

    /**
     * Cambiar estado de un favorito
     * @param int $idUsuario
     * @param int $idOferta
     * @param string $estado
     * @return bool
     */
    public static function cambiarEstado($idUsuario, $idOferta, $estado) {
        BaseDatos::ejecutar(
            "UPDATE favoritos SET estado = ? WHERE id_usuario = ? AND id_oferta = ?",
            [$estado, $idUsuario, $idOferta]
        );
        return true;
    }

    /**
     * Obtener favoritos de un usuario con datos de la oferta
     * @param int $idUsuario
     * @param string|null $estado Filtrar por estado
     * @return array
     */
    public static function obtenerPorUsuario($idUsuario, $estado = null) {
        $sql = "SELECT f.*, o.titulo, o.empresa, o.provincia, o.categoria, o.salario, o.tipo_contrato, o.url, o.fecha_publicacion
                FROM favoritos f
                INNER JOIN ofertas o ON f.id_oferta = o.id
                WHERE f.id_usuario = ?";
        $parametros = [$idUsuario];

        if ($estado) {
            $sql .= " AND f.estado = ?";
            $parametros[] = $estado;
        }

        $sql .= " ORDER BY f.fecha_creacion DESC";
        return BaseDatos::consultar($sql, $parametros);
    }

    /**
     * Verificar si una oferta es favorita del usuario
     * @param int $idUsuario
     * @param int $idOferta
     * @return array|false
     */
    public static function esFavorito($idUsuario, $idOferta) {
        return BaseDatos::consultarUno(
            "SELECT * FROM favoritos WHERE id_usuario = ? AND id_oferta = ?",
            [$idUsuario, $idOferta]
        );
    }

    /**
     * Contar favoritos de un usuario
     * @param int $idUsuario
     * @return int
     */
    public static function contar($idUsuario) {
        $resultado = BaseDatos::consultarUno(
            "SELECT COUNT(*) as total FROM favoritos WHERE id_usuario = ?",
            [$idUsuario]
        );
        return $resultado['total'];
    }

    /**
     * Obtener estadisticas de favoritos por estado
     * @param int $idUsuario
     * @return array
     */
    public static function estadisticasPorEstado($idUsuario) {
        return BaseDatos::consultar(
            "SELECT estado, COUNT(*) as total FROM favoritos WHERE id_usuario = ? GROUP BY estado",
            [$idUsuario]
        );
    }
}
