<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../includes/helpers.php';

/**
 * Registra un error en logs/app-error.log (si no se puede escribir, usa el log de PHP).
 */
function app_log_error(string $mensaje): void
{
    $dir = APP_PATH . '/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $mensaje . PHP_EOL;
    if (!@error_log($line, 3, $dir . '/app-error.log')) {
        error_log(trim($line));
    }
}

/**
 * Unico lugar donde se maneja una BD caida: registra el error real en
 * logs/app-error.log y responde 503 con un mensaje generico (sin credenciales).
 */
function db_unavailable(string $detail): void
{
    app_log_error('Error de conexion a BD (' . DB_USER . '@' . DB_HOST . '/' . DB_NAME . '): ' . $detail);

    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    if (!headers_sent()) {
        http_response_code(503);
        header('Retry-After: 120');
        header('Cache-Control: no-store');
    }

    $mensaje = 'Servicio no disponible, intenta más tarde.';
    $esAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
        || stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

    if ($esAjax) {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo json_encode(['error' => $mensaje]);
        exit;
    }

    if (!headers_sent()) {
        header('Content-Type: text/html; charset=UTF-8');
    }
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">'
        . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
        . '<title>Servicio no disponible</title>'
        . '<style>body{font-family:Arial,sans-serif;background:#f5f5f5;color:#333;display:flex;'
        . 'align-items:center;justify-content:center;min-height:100vh;margin:0;text-align:center}'
        . '.box{background:#fff;padding:2rem 2.5rem;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.1);max-width:420px}'
        . 'h1{font-size:1.4rem;margin:0 0 .75rem}p{margin:0 0 1rem}a{color:#0d6efd}</style></head><body>'
        . '<div class="box"><h1>Servicio no disponible</h1>'
        . '<p>' . e($mensaje) . '</p>'
        . '<a href="' . e(url('index.php')) . '">Reintentar</a></div></body></html>';
    exit;
}

try {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($con->connect_error) {
        db_unavailable($con->connect_error);
    }
} catch (Throwable $ex) {
    db_unavailable($ex->getMessage());
}
