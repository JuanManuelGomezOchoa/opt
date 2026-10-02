<?php
require_once __DIR__ . '/../app/includes/bootstrap.php';

// Si ya tiene sesión activa, mandarlo directo a usuarios.php
if (isset($_SESSION['id'], $_SESSION['rol'])) {
    header("Location: " . url(pagina_inicio_por_rol($_SESSION['rol'])));
    exit();
}

// Lógica de Alertas SweetAlert2
$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;
if (!empty($alert)) {
    $title = isset($alert['title']) ? json_encode($alert['title']) : '"Notificación"';
    $message = isset($alert['message']) ? json_encode($alert['message']) : '""';
    $icon = isset($alert['icon']) ? json_encode($alert['icon']) : '"info"';

    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: $title,
                    " . (!empty($alert['message']) ? "text: $message," : "") . "
                    icon: $icon,
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    unset($_SESSION['alert']);
}

// Procesar el formulario cuando se envía
if (isset($_POST['login_btn'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $falla = function (string $mensaje) {
        $_SESSION['alert'] = ['title' => 'ERROR', 'message' => $mensaje, 'icon' => 'error'];
        header("Location: " . url("login.php"));
        exit();
    };

    $stmt = $con->prepare("SELECT id, nombre, username, password, rol, estatus, intentos_fallidos, bloqueado_hasta FROM usuarios WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$u) {
        // Mismo costo y mismo mensaje que una contraseña incorrecta (no revela si el correo existe)
        password_verify($password, '$2y$10$HSe8nYP36tP.g/ygV1D9e.etA3duiyjwmG9XSv1GUhfAeTGVP.tA2');
        $falla('Credenciales incorrectas');
    }

    // Reloj de PHP para guardar y comparar (nunca NOW() de MySQL)
    $ahora = time();
    $bloqueadoHasta = $u['bloqueado_hasta'] ? strtotime($u['bloqueado_hasta']) : 0;

    if ($bloqueadoHasta > $ahora) {
        // Rechazar aunque la contraseña sea correcta
        $minutos = (int)ceil(($bloqueadoHasta - $ahora) / 60);
        $falla('Cuenta bloqueada por intentos fallidos. Intenta de nuevo en ' . $minutos . ' minuto(s).');
    }

    if ($bloqueadoHasta > 0) {
        // El bloqueo ya venció: empezar el conteo desde cero
        $stmt = $con->prepare("UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id = ?");
        $stmt->bind_param("i", $u['id']);
        $stmt->execute();
        $stmt->close();
    }

    if (!password_verify($password, (string)$u['password'])) {
        // Incremento atómico por cuenta en BD; al llegar al máximo se fija el bloqueo
        $hasta = date('Y-m-d H:i:s', $ahora + MINUTOS_BLOQUEO * 60);
        $max = MAX_INTENTOS_FALLIDOS;
        $stmt = $con->prepare("UPDATE usuarios SET bloqueado_hasta = IF(intentos_fallidos + 1 >= ?, ?, bloqueado_hasta), intentos_fallidos = intentos_fallidos + 1 WHERE id = ?");
        $stmt->bind_param("isi", $max, $hasta, $u['id']);
        $stmt->execute();
        $stmt->close();

        $previos = $bloqueadoHasta > 0 ? 0 : (int)$u['intentos_fallidos'];
        if ($previos + 1 >= MAX_INTENTOS_FALLIDOS) {
            $falla('Cuenta bloqueada por intentos fallidos. Intenta de nuevo en ' . MINUTOS_BLOQUEO . ' minuto(s).');
        }
        $falla('Credenciales incorrectas');
    }

    if ((int)$u['estatus'] !== 1) {
        $_SESSION['alert'] = [
            'title' => 'ACCESO DENEGADO',
            'message' => 'Tu usuario se encuentra inactivo.',
            'icon' => 'warning'
        ];
        header("Location: " . url("login.php"));
        exit();
    }

    // Credenciales correctas: limpiar contador/bloqueo
    $stmt = $con->prepare("UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id = ?");
    $stmt->bind_param("i", $u['id']);
    $stmt->execute();
    $stmt->close();

    // Nuevo ID de sesión ANTES de guardar los datos del usuario (anti session fixation)
    session_regenerate_id(true);
    $_SESSION['id'] = (int)$u['id'];
    $_SESSION['nombre'] = $u['nombre'];
    $_SESSION['username'] = $u['username'];
    $_SESSION['rol'] = $u['rol'];

    header("Location: " . url(pagina_inicio_por_rol($u['rol'])));
    exit();
}
?>
<?php
$pageTitle = 'Iniciar Sesión';
$layout = 'standalone';
$bodyClass = 'login-body';
include APP_PATH . '/app/screens/layout/head.php';
?>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <?php include APP_PATH . '/app/screens/layout/logo.php'; ?>
            <h4 class="mt-3">Acceso al Sistema</h4>
            <small class="text-muted">Ingresa tus credenciales</small>
        </div>
        
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo</label>
                <input type="email" class="form-control" name="email" id="email" autocomplete="username" required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" autocomplete="current-password" required>
            </div>

            <button type="submit" name="login_btn" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
            </button>
        </form>
        
        <div class="footer-links text-muted">
            <a href="#">¿Olvidaste tu contraseña?</a>
            <span>|</span>
            <a href="#">Registrarse</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>

</body>
</html>