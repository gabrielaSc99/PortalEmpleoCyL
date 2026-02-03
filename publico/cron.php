<?php
/**
 * Endpoint para sincronizacion automatica via cron externo
 * URL: http://tu-dominio/PortalEmpleoCyL/publico/cron.php?token=TU_TOKEN_SEGURO
 * Configurar token via variable de entorno CRON_SYNC_TOKEN o editar el valor por defecto
 *
 * Green Coding: solo se ejecuta si han pasado 24h desde la ultima sincronizacion
 */

// Token de seguridad para evitar que cualquiera lance la sincronizacion
// Configurable via variable de entorno o valor por defecto
$tokenValido = getenv('CRON_SYNC_TOKEN') ?: 'aX9kL2mQ7vZ4wP8nR1jT6yB3cF5hD0sE';

// Verificar token
$token = $_GET['token'] ?? '';
if ($token !== $tokenValido) {
    http_response_code(403);
    echo json_encode(['error' => 'Token no valido']);
    exit;
}

require_once __DIR__ . '/../aplicacion/nucleo/Configuracion.php';
require_once __DIR__ . '/../aplicacion/nucleo/Cache.php';

// Green Coding: comprobar si ya se sincronizo en las ultimas 24h
$ultimaSync = Cache::obtener('ultima_sincronizacion', 86400);
if ($ultimaSync !== false) {
    echo json_encode([
        'estado' => 'omitido',
        'mensaje' => 'Ya se sincronizo en las ultimas 24h',
        'ultima_sincronizacion' => $ultimaSync
    ]);
    exit;
}

// Ejecutar sincronizacion
require_once __DIR__ . '/../tareas_programadas/sincronizar_ofertas.php';

// Guardar marca de tiempo
Cache::guardar('ultima_sincronizacion', date('Y-m-d H:i:s'));

echo json_encode([
    'estado' => 'completado',
    'mensaje' => 'Sincronizacion ejecutada correctamente',
    'fecha' => date('Y-m-d H:i:s')
]);
