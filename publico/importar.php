<?php
/**
 * Script web para importar ofertas desde la API de datos abiertos
 * Ejecutar desde el navegador: http://localhost/PortalEmpleoCyL/publico/importar.php
 */

set_time_limit(300); // 5 minutos maximo

echo "<h1>Importacion de Ofertas de Empleo - CyL</h1>";
echo "<pre>";

require_once __DIR__ . '/../aplicacion/nucleo/BaseDatos.php';

$urlApi = 'https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/ofertas-de-empleo/records';
$registrosPorPeticion = 100;
$totalAnadidas = 0;
$totalExistentes = 0;
$offset = 0;

try {
    // Primera peticion para saber el total
    $urlPrimera = $urlApi . '?limit=' . $registrosPorPeticion . '&offset=0';
    $respuesta = file_get_contents($urlPrimera);

    if ($respuesta === false) {
        throw new Exception("No se pudo conectar con la API de datos abiertos");
    }

    $datos = json_decode($respuesta, true);
    $totalRegistros = $datos['total_count'] ?? 0;

    echo "Total de ofertas en la API: $totalRegistros\n\n";

    do {
        $url = $urlApi . '?limit=' . $registrosPorPeticion . '&offset=' . $offset;
        $respuesta = file_get_contents($url);

        if ($respuesta === false) {
            echo "Error al obtener datos en offset $offset, reintentando...\n";
            sleep(2);
            continue;
        }

        $datos = json_decode($respuesta, true);
        $registros = $datos['results'] ?? [];

        foreach ($registros as $registro) {
            $idFuente = $registro['identificador'] ?? uniqid();
            $titulo = trim($registro['titulo'] ?? 'Sin titulo');
            $descripcion = strip_tags($registro['descripcion'] ?? '');
            $descripcion = html_entity_decode($descripcion, ENT_QUOTES, 'UTF-8');
            $descripcion = trim(preg_replace('/\s+/', ' ', $descripcion));
            $empresa = trim($registro['fuentecontenido'] ?? '');
            $provincia = trim($registro['provincia'] ?? '');
            $url_oferta = trim($registro['enlace_al_contenido'] ?? '');
            $fecha = $registro['fecha_publicacion'] ?? date('Y-m-d');

            // Verificar si ya existe
            $existe = BaseDatos::consultarUno(
                "SELECT id FROM ofertas WHERE id_fuente = ?",
                [$idFuente]
            );

            if (!$existe) {
                BaseDatos::ejecutar(
                    "INSERT INTO ofertas (id_fuente, titulo, descripcion, empresa, provincia, categoria, salario, tipo_contrato, url, fecha_publicacion)
                     VALUES (?, ?, ?, ?, ?, '', '', '', ?, ?)",
                    [$idFuente, $titulo, $descripcion, $empresa, $provincia, $url_oferta, $fecha]
                );
                $totalAnadidas++;
            } else {
                $totalExistentes++;
            }
        }

        $offset += $registrosPorPeticion;
        echo "Procesadas: $offset / $totalRegistros (nuevas: $totalAnadidas)\n";
        flush();

        usleep(300000); // 0.3 segundos de pausa

    } while ($offset < $totalRegistros);

    // Registrar sincronizacion
    BaseDatos::ejecutar(
        "INSERT INTO registros_sincronizacion (registros_anadidos, registros_actualizados, estado)
         VALUES (?, 0, 'exitoso')",
        [$totalAnadidas]
    );

    echo "\n============================================\n";
    echo "IMPORTACION COMPLETADA\n";
    echo "Ofertas nuevas importadas: $totalAnadidas\n";
    echo "Ofertas ya existentes: $totalExistentes\n";
    echo "============================================\n\n";
    echo "Vuelve a la aplicacion: <a href='index.php'>Portal de Empleo CyL</a>\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";
