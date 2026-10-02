<?php
/**
 * Autenticación y control de acceso (Actividad 3.3).
 * Roles: 'administrador' | 'vendedor'. Se guarda en $_SESSION['rol'] al iniciar sesión.
 */

const ROLES_VALIDOS = ['administrador', 'vendedor'];
const MAX_INTENTOS_FALLIDOS = 3;
const MINUTOS_BLOQUEO = 5;

/**
 * Inicia la sesión con cookie httponly + SameSite=Lax (debe ir antes de session_start).
 */
function iniciar_sesion_segura(): void
{
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/** Página de inicio de la intranet según el rol. */
function pagina_inicio_por_rol(?string $rol): string
{
    return $rol === 'administrador' ? 'admin/users.php' : 'admin/approved-orders.php';
}

/**
 * Exige sesión iniciada y usuario vigente (existe y está activo). Refresca el rol
 * desde la BD para que un cambio de rol / baja surta efecto de inmediato.
 */
function requerir_login(): void
{
    global $con;

    $id = (int)($_SESSION['id'] ?? 0);
    if ($id > 0) {
        $stmt = $con->prepare('SELECT rol, estatus FROM usuarios WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row && (int)$row['estatus'] === 1 && in_array($row['rol'], ROLES_VALIDOS, true)) {
            $_SESSION['rol'] = $row['rol'];
            return;
        }
        $_SESSION = [];
    }

    $_SESSION['alert'] = [
        'message' => 'Para acceder debes iniciar sesión primero',
        'title'   => 'SESIÓN NO INICIADA',
        'icon'    => 'error',
    ];
    header('Location: ' . url('login.php'));
    exit;
}

/**
 * Exige login y que el rol esté en $roles; si no, responde 403 y corta la ejecución.
 */
function requerir_rol(array $roles): void
{
    requerir_login();

    if (!in_array($_SESSION['rol'] ?? '', $roles, true)) {
        http_response_code(403);
        header('Content-Type: text/html; charset=UTF-8');
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>403 Prohibido</title></head><body>'
            . '<h1>403 - Acceso denegado</h1><p>No tienes permiso para acceder a este recurso.</p>'
            . '<p><a href="' . e(url(pagina_inicio_por_rol($_SESSION['rol'] ?? null))) . '">Volver</a></p></body></html>';
        exit;
    }
}

/**
 * Política de contraseñas. Devuelve la lista de errores (vacía = válida).
 * Reglas: mínimo 8 caracteres, una minúscula, una mayúscula y un número.
 */
function validar_password(string $password): array
{
    $errores = [];
    if (mb_strlen($password) < 8) {
        $errores[] = 'Debe tener al menos 8 caracteres.';
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errores[] = 'Debe incluir al menos una letra minúscula.';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errores[] = 'Debe incluir al menos una letra mayúscula.';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errores[] = 'Debe incluir al menos un número.';
    }
    return $errores;
}
