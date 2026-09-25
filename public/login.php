<?php
require_once __DIR__ . '/../app/includes/bootstrap.php';

// Si ya tiene sesión activa, mandarlo directo a usuarios.php
if (isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/admin/users.php");
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
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT id, nombre, username, password, rol, estatus FROM usuarios WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $db_id = 0;
    $db_nombre = "";
    $db_username = "";
    $db_password = "";
    $db_rol = "";
    $db_estatus = "";

    $stmt->bind_result($db_id, $db_nombre, $db_username, $db_password, $db_rol, $db_estatus);

    if ($stmt->fetch()) {
        if (password_verify($password, (string) $db_password)) {
            if ((string)$db_estatus === "1") {
                $_SESSION['id'] = $db_id;
                $_SESSION['nombre'] = $db_nombre;
                $_SESSION['username'] = $db_username;
                $_SESSION['rol'] = $db_rol;
                
                header("Location: " . BASE_URL . "/admin/users.php");
                exit();
            } else {
                $_SESSION['alert'] = [
                    'title' => 'ACCESO DENEGADO',
                    'message' => 'Tu usuario se encuentra inactivo.',
                    'icon' => 'warning'
                ];
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = [
                'title' => 'ERROR',
                'message' => 'Contraseña incorrecta.',
                'icon' => 'error'
            ];
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = [
            'title' => 'ERROR',
            'message' => 'El correo electrónico no está registrado.',
            'icon' => 'error'
        ];
        header("Location: login.php");
        exit();
    }
    
    $stmt->close();
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