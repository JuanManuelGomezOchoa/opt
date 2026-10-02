<?php
if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('url')) {
    /** URL absoluta a una ruta del sitio: url('login.php'), url('order.php?id=1'). */
    function url(string $path = ''): string
    {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}

if (!function_exists('store_url')) {
    /** Igual que url() pero con STORE_URL (enlaces publicos que van en correos). */
    function store_url(string $path = ''): string
    {
        return STORE_URL . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /** URL a un archivo de public/assets: asset('css/styles.css'). */
    function asset(string $path): string
    {
        return url('assets/' . ltrim($path, '/'));
    }
}
