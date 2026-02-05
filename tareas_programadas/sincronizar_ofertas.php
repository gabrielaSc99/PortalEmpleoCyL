<?php
/**
 * Script de sincronizacion de ofertas de empleo
 * Descarga datos desde la API de datos abiertos de Castilla y Leon
 *
 * Uso manual: php tareas_programadas/sincronizar_ofertas.php
 * Cron: 0 0 * * * php /ruta/completa/tareas_programadas/sincronizar_ofertas.php
 */

require_once __DIR__ . '/../aplicacion/nucleo/BaseDatos.php';
require_once __DIR__ . '/../aplicacion/nucleo/Cache.php';

class ServicioSincronizacion {

    /** @var string URL base de la API de datos abiertos */
    private $urlApi = 'https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/ofertas-de-empleo/records';

    /** @var int Registros por peticion */
    private $registrosPorPeticion = 100;

    /**
     * Ejecutar sincronizacion completa
     */
    public function sincronizar() {
        echo "[" . date('Y-m-d H:i:s') . "] Iniciando sincronizacion de ofertas...\n";

        $totalAnadidas = 0;
        $totalActualizadas = 0;
        $totalEliminadas = 0;
        $offset = 0;
        $totalRegistros = null;
        $idsFuenteAPI = [];

        try {
            do {
                $datos = $this->obtenerDesdeAPI($offset);

                if ($datos === false) {
                    throw new Exception("Error al obtener datos de la API (offset: $offset)");
                }

                if ($totalRegistros === null) {
                    $totalRegistros = $datos['total_count'] ?? 0;
                    echo "Total de registros en la API: $totalRegistros\n";
                }

                $registros = $datos['results'] ?? [];

                foreach ($registros as $registro) {
                    $oferta = $this->mapearOferta($registro);
                    $idsFuenteAPI[] = $oferta['id_fuente'];
                    $resultado = $this->insertarOferta($oferta);

                    if ($resultado === 'insertada') {
                        $totalAnadidas++;
                    } elseif ($resultado === 'actualizada') {
                        $totalActualizadas++;
                    }
                }

                $offset += $this->registrosPorPeticion;
                echo "Procesados: $offset / $totalRegistros\n";

                // Pausa para no saturar la API
                usleep(500000); // 0.5 segundos

            } while ($offset < $totalRegistros);

            // Purgar ofertas que ya no existen en la API
            $totalEliminadas = $this->purgarOfertasObsoletas($idsFuenteAPI);

            // Registrar sincronizacion exitosa
            $this->registrarSincronizacion($totalAnadidas, $totalActualizadas, $totalEliminadas, 'exitoso');

            // Limpiar cache si hubo cambios
            if ($totalAnadidas > 0 || $totalActualizadas > 0 || $totalEliminadas > 0) {
                Cache::limpiarTodo();
                echo "Cache limpiada (hubo cambios)\n";
            }

            echo "[" . date('Y-m-d H:i:s') . "] Sincronizacion completada.\n";
            echo "Ofertas nuevas: $totalAnadidas\n";
            echo "Ofertas actualizadas: $totalActualizadas\n";
            echo "Ofertas eliminadas (obsoletas): $totalEliminadas\n";

        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
            $this->registrarSincronizacion($totalAnadidas, $totalActualizadas, $totalEliminadas, 'fallido', $e->getMessage());
        }
    }

    /**
     * Obtener registros desde la API
     * @param int $offset
     * @return array|false
     */
    private function obtenerDesdeAPI($offset = 0) {
        $url = $this->urlApi . '?limit=' . $this->registrosPorPeticion . '&offset=' . $offset;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($respuesta === false || $httpCode !== 200) {
            return false;
        }

        return json_decode($respuesta, true);
    }

    /**
     * Mapear registro de la API al formato de la base de datos
     * @param array $registro
     * @return array
     */
    private function mapearOferta($registro) {
        // Limpiar HTML de la descripcion
        $descripcion = strip_tags($registro['descripcion'] ?? '');
        $descripcion = html_entity_decode($descripcion, ENT_QUOTES, 'UTF-8');
        $descripcion = trim(preg_replace('/\s+/', ' ', $descripcion));

        return [
            'id_fuente' => $registro['identificador'] ?? uniqid(),
            'titulo' => trim($registro['titulo'] ?? 'Sin titulo'),
            'descripcion' => $descripcion,
            'empresa' => trim($registro['fuentecontenido'] ?? ''),
            'provincia' => trim($registro['provincia'] ?? '') === 'Otra' ? 'Ambito Nacional' : trim($registro['provincia'] ?? ''),
            'categoria' => '', // La API no tiene campo de categoria
            'salario' => '',
            'tipo_contrato' => '',
            'url' => trim($registro['enlace_al_contenido'] ?? ''),
            'fecha_publicacion' => $registro['fecha_publicacion'] ?? date('Y-m-d')
        ];
    }

    /**
     * Insertar oferta en la base de datos si no existe
     * @param array $oferta
     * @return string 'insertada' o 'existente'
     */
    private function insertarOferta($oferta) {
        $existe = BaseDatos::consultarUno(
            "SELECT id FROM ofertas WHERE id_fuente = ?",
            [$oferta['id_fuente']]
        );

        if (!$existe) {
            BaseDatos::ejecutar(
                "INSERT INTO ofertas (id_fuente, titulo, descripcion, empresa, provincia, categoria, salario, tipo_contrato, url, fecha_publicacion)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $oferta['id_fuente'], $oferta['titulo'], $oferta['descripcion'],
                    $oferta['empresa'], $oferta['provincia'], $oferta['categoria'],
                    $oferta['salario'], $oferta['tipo_contrato'], $oferta['url'],
                    $oferta['fecha_publicacion']
                ]
            );
            return 'insertada';
        }

        // Actualizar datos por si cambiaron
        BaseDatos::ejecutar(
            "UPDATE ofertas SET titulo = ?, descripcion = ?, empresa = ?, provincia = ?, url = ?, fecha_actualizacion = NOW()
             WHERE id_fuente = ?",
            [
                $oferta['titulo'], $oferta['descripcion'], $oferta['empresa'],
                $oferta['provincia'], $oferta['url'], $oferta['id_fuente']
            ]
        );
        return 'actualizada';
    }

    /**
     * Eliminar ofertas que ya no existen en la API
     * @param array $idsFuenteAPI IDs de fuente recibidos de la API
     * @return int Numero de ofertas eliminadas
     */
    private function purgarOfertasObsoletas($idsFuenteAPI) {
        if (empty($idsFuenteAPI)) {
            return 0;
        }

        $placeholders = implode(',', array_fill(0, count($idsFuenteAPI), '?'));
        $resultado = BaseDatos::ejecutar(
            "DELETE FROM ofertas WHERE id_fuente NOT IN ($placeholders)",
            $idsFuenteAPI
        );

        $eliminadas = $resultado->rowCount();

        if ($eliminadas > 0) {
            echo "Purgadas $eliminadas ofertas obsoletas que ya no estan en la API\n";
        }

        return $eliminadas;
    }

    /**
     * Registrar resultado de la sincronizacion en la base de datos
     * @param int $anadidas
     * @param int $actualizadas
     * @param int $eliminadas
     * @param string $estado
     * @param string $mensajeError
     */
    private function registrarSincronizacion($anadidas, $actualizadas, $eliminadas, $estado, $mensajeError = null) {
        try {
            BaseDatos::ejecutar(
                "INSERT INTO registros_sincronizacion (registros_anadidos, registros_actualizados, registros_eliminados, estado, mensaje_error)
                 VALUES (?, ?, ?, ?, ?)",
                [$anadidas, $actualizadas, $eliminadas, $estado, $mensajeError]
            );
        } catch (Exception $e) {
            echo "Error al registrar sincronizacion: " . $e->getMessage() . "\n";
        }
    }
}

// Ejecutar si se llama directamente
$servicio = new ServicioSincronizacion();
$servicio->sincronizar();
