<?php
/**
 * Clase base para todos los controladores
 * Proporciona metodos comunes como renderizar vistas y redirigir
 */
class Controlador {

    /**
     * Renderizar una vista con datos
     * @param string $vista Ruta de la vista (ej: 'ofertas/listado')
     * @param array $datos Variables para la vista
     */
    protected function renderizar($vista, $datos = []) {
        // Extraer datos como variables accesibles en la vista
        extract($datos);

        // Incluir la plantilla principal con la vista dentro
        $contenidoVista = __DIR__ . '/../vistas/' . $vista . '.php';
        require_once __DIR__ . '/../vistas/plantillas/principal.php';
    }

    /**
     * Redirigir a otra URL
     * @param string $ruta Ruta destino
     */
    protected function redirigir($ruta) {
        header('Location: index.php?ruta=' . $ruta);
        exit;
    }

    /**
     * Devolver respuesta JSON (para peticiones AJAX)
     * @param mixed $datos Datos a devolver
     * @param int $codigo Codigo HTTP
     */
    protected function responderJSON($datos, $codigo = 200) {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Verificar que el usuario esta autenticado
     * Redirige al login si no lo esta
     */
    protected function requiereAutenticacion() {
        if (!isset($_SESSION['id_usuario'])) {
            $this->redirigir('login');
        }
    }

    /**
     * Obtener dato del formulario POST de forma segura
     * @param string $campo Nombre del campo
     * @param mixed $defecto Valor por defecto
     * @return mixed
     */
    protected function obtenerPost($campo, $defecto = null) {
        return isset($_POST[$campo]) ? trim(htmlspecialchars($_POST[$campo])) : $defecto;
    }

    /**
     * Obtener parametro GET de forma segura
     * @param string $campo Nombre del campo
     * @param mixed $defecto Valor por defecto
     * @return mixed
     */
    protected function obtenerGet($campo, $defecto = null) {
        return isset($_GET[$campo]) ? trim(htmlspecialchars($_GET[$campo])) : $defecto;
    }
}
