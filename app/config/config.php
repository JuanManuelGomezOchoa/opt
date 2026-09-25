<?php
/**
 * Único punto de carga del archivo .env y de las constantes globales.
 * Ningún otro archivo debe instanciar Dotenv ni leer credenciales en duro.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(__DIR__, 2));
}

$dotenv = Dotenv\Dotenv::createImmutable(APP_PATH);
$dotenv->safeLoad();

define('APP_ENV', isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] !== '' ? $_ENV['APP_ENV'] : 'local');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? 'false', FILTER_VALIDATE_BOOLEAN));
define('BASE_URL', rtrim($_ENV['BASE_URL'] ?? '', '/'));
define('STORE_URL', rtrim($_ENV['STORE_URL'] ?? '', '/'));

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? '');
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');

if (APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
}

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        return array_key_exists($key, $_ENV) && $_ENV[$key] !== '' ? $_ENV[$key] : $default;
    }
}