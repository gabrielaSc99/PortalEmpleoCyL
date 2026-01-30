<?php
require_once __DIR__ . '/../nucleo/Controlador.php';
require_once __DIR__ . '/../nucleo/BaseDatos.php';
require_once __DIR__ . '/../nucleo/Cache.php';
require_once __DIR__ . '/../modelos/Oferta.php';

/**
 * Controlador del panel de administracion
 * Solo accesible para usuarios con rol 'administrador'
 */
class ControladorAdmin extends Controlador {

    /**
     * Verificar que el usuario es administrador
     */
    private function requiereAdmin() {
        $this->requiereAutenticacion();
        if (($_SESSION['rol_usuario'] ?? '') !== 'administrador') {
            $this->redirigir('inicio');
        }
    }

    /**
     * Mostrar panel de administracion
     */
    public function panel() {
        $this->requiereAdmin();

        // Obtener registros de sincronizacion
        $sincronizaciones = BaseDatos::consultar(
            "SELECT * FROM registros_sincronizacion ORDER BY fecha_sincronizacion DESC LIMIT 20"
        );

        // Obtener estadisticas generales
        $totalOfertas = BaseDatos::consultarUno("SELECT COUNT(*) as total FROM ofertas")['total'];
        $totalUsuarios = BaseDatos::consultarUno("SELECT COUNT(*) as total FROM usuarios")['total'];
        $totalFavoritos = BaseDatos::consultarUno("SELECT COUNT(*) as total FROM favoritos")['total'];

        // Estadisticas de cache
        $estadisticasCache = Cache::obtenerEstadisticas();

        // Ultimos usuarios registrados
        $ultimosUsuarios = BaseDatos::consultar(
            "SELECT id, nombre, email, provincia, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC LIMIT 10"
        );

        $this->renderizar('panel/admin', [
            'titulo' => 'Panel de Administracion',
            'sincronizaciones' => $sincronizaciones,
            'totalOfertas' => $totalOfertas,
            'totalUsuarios' => $totalUsuarios,
            'totalFavoritos' => $totalFavoritos,
            'estadisticasCache' => $estadisticasCache,
            'ultimosUsuarios' => $ultimosUsuarios
        ]);
    }

    /**
     * Lanzar sincronizacion manual desde el panel
     */
    public function sincronizar() {
        $this->requiereAdmin();

        // Ejecutar sincronizacion
        require_once __DIR__ . '/../../tareas_programadas/sincronizar_ofertas.php';

        $this->redirigir('admin');
    }
}
