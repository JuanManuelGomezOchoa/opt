<?php
/**
 * Seguridad común (remediación Burp Suite).
 *  - V1: cabeceras HTTP de seguridad (se envían en todas las páginas porque bootstrap.php carga este archivo).
 *  - V3: token Anti-CSRF (generar, imprimir en formularios y validar en el servidor).
 */

// ---------------------------------------------------------------
// V1. Cabeceras HTTP de seguridad
// ---------------------------------------------------------------
if (!headers_sent()) {
    // Evita que el sitio se cargue dentro de un iframe externo (clickjacking)
    header('X-Frame-Options: DENY');

    // Evita que el navegador "adivine" el tipo de archivo (MIME-sniffing)
    header('X-Content-Type-Options: nosniff');

    // Content-Security-Policy: solo se permiten los orígenes que el proyecto realmente usa
    // (Bootstrap, jQuery, SweetAlert2, DataTables, Font Awesome, Google Fonts, AOS y Openpay).
    // 'unsafe-inline' es necesario porque las páginas tienen <script> y style="" dentro del HTML.
    $csp = [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' https://code.jquery.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://ajax.googleapis.com https://cdn.datatables.net https://openpay.s3.amazonaws.com https://use.fontawesome.com https://unpkg.com",
        "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.datatables.net https://unpkg.com https://use.fontawesome.com",
        "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://use.fontawesome.com",
        "img-src 'self' data: https:",
        "connect-src 'self' https://api.openpay.mx https://sandbox-api.openpay.mx https://use.fontawesome.com",
        "frame-src https://*.openpay.mx https://openpay.s3.amazonaws.com",
        "frame-ancestors 'none'",   // igual que X-Frame-Options, versión moderna
        "form-action 'self'",
        "base-uri 'self'",
        "object-src 'none'",
    ];
    header('Content-Security-Policy: ' . implode('; ', $csp));
}

// ---------------------------------------------------------------
// V3. Token Anti-CSRF
// ---------------------------------------------------------------

/** Devuelve el token de la sesión (lo crea la primera vez con 32 bytes aleatorios). */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Imprime el <input hidden> que se pone dentro de cada formulario POST. */
function csrf_campo(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Valida el token recibido por POST contra el de la sesión.
 * Si falta o no coincide responde 403 y detiene TODO (no se ejecuta nada más).
 */
function csrf_validar(): void
{
    $enviado = $_POST['csrf_token'] ?? '';
    $guardado = $_SESSION['csrf_token'] ?? '';

    if (!is_string($enviado) || $guardado === '' || !hash_equals($guardado, $enviado)) {
        http_response_code(403);
        header('Content-Type: text/html; charset=UTF-8');
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>403 Prohibido</title></head><body>'
            . '<h1>403 - Solicitud no válida</h1><p>Token de seguridad (CSRF) inválido o ausente. Recarga la página e inténtalo de nuevo.</p></body></html>';
        exit;
    }
}
