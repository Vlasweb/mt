<?php

require_once __DIR__ . '/envLoader.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/jwt.php';
require_once __DIR__ . '/activityLog.php';

// Cargar variables de entorno
loadEnv(__DIR__ . '/../../.env');

// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight CORS (sin cuerpo, sin Content-Type)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Headers de respuesta
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

// Captura global de excepciones no controladas
set_exception_handler(function (Throwable $e) {
    error_log('Unhandled exception: ' . $e->getMessage());
    jsonError('server_error', 'Error interno del servidor', 500);
});
