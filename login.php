<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'dbcon.php';

// Si ya tiene sesión activa, mandarlo directo a usuarios.php
if (isset($_SESSION['username'])) {
    header("Location: usuarios.php");
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
                
                header("Location: usuarios.php");
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #0f0f1a;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 380px;
        }
        
        .login-card {
            background: #1a1a2e;
            border-radius: 16px;
            padding: 40px 35px;
            border: 1px solid #2a2a4a;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .login-header i {
            font-size: 48px;
            color: #00d4ff;
            display: block;
            margin-bottom: 12px;
        }
        
        .login-header h4 {
            color: #ffffff;
            font-weight: 300;
            margin: 0;
            letter-spacing: 1px;
        }
        
        .login-header small {
            color: #8888aa;
            font-size: 14px;
        }
        
        .form-control {
            background: #12121f;
            border: 1px solid #2a2a4a;
            color: #ffffff;
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            background: #12121f;
            border-color: #00d4ff;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
            color: #ffffff;
        }
        
        .form-control::placeholder {
            color: #555577;
        }
        
        .form-floating label {
            color: #8888aa;
        }
        
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #00d4ff;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #00d4ff;
            border: none;
            border-radius: 8px;
            color: #0f0f1a;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            background: #00b8e6;
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 212, 255, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .footer-links {
            text-align: center;
            margin-top: 25px;
            color: #555577;
            font-size: 14px;
        }
        
        .footer-links a {
            color: #00d4ff;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-robot"></i>
            <h4>Acceso al Sistema</h4>
            <small>Ingresa tus credenciales</small>
        </div>
        
        <form action="login.php" method="POST">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="correo@ejemplo.com" autocomplete="off" required>
                <label for="email"><i class="bi bi-envelope me-2"></i>Correo</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" autocomplete="off" required>
                <label for="password"><i class="bi bi-key me-2"></i>Contraseña</label>
            </div>

            <button type="submit" name="login_btn" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
            </button>
        </form>
        
        <div class="footer-links">
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