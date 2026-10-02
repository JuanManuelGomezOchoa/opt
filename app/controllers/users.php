<?php
require_once __DIR__ . '/../includes/bootstrap.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Gestión de usuarios: SOLO administradores (validado en servidor para delete/update/save)
requerir_rol(['administrador']);

// V3: todo POST a este action debe traer un token CSRF válido (si no, 403 y no se ejecuta nada)
csrf_validar();

$volver = function (string $title, string $message, string $icon) {
    $_SESSION['alert'] = ['title' => $title, 'message' => $message, 'icon' => $icon];
    header("Location: " . url("admin/users.php"));
    exit;
};

if (isset($_POST['delete'])) {
    $registro_id = (int)$_POST['delete'];

    if ($registro_id === (int)$_SESSION['id']) {
        $volver('ERROR AL ELIMINAR', 'No puedes eliminar tu propia cuenta', 'error');
    }

    $stmt = $con->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $registro_id);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        $volver('USUARIO ELIMINADO', 'Usuario eliminado exitosamente', 'success');
    }
    $volver('ERROR AL ELIMINAR', 'Notifica a soporte', 'error');
}

if (isset($_POST['update'])) {
    $id = (int)($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidopaterno = trim($_POST['apellidopaterno'] ?? '');
    $apellidomaterno = trim($_POST['apellidomaterno'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $estatus = (int)($_POST['estatus'] ?? 0) === 1 ? 1 : 0;

    if (!in_array($rol, ROLES_VALIDOS, true)) {
        $volver('ERROR AL EDITAR', 'Rol no válido', 'error');
    }

    // Solo se valida/cambia la contraseña si se capturó una nueva
    if ($password !== '') {
        $errores = validar_password($password);
        if ($errores) {
            $volver('CONTRASEÑA NO VÁLIDA', implode(' ', $errores), 'error');
        }
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, apellidopaterno=?, apellidomaterno=?, username=?, rol=?, estatus=?, password=? WHERE id=?");
        $stmt->bind_param("sssssisi", $nombre, $apellidopaterno, $apellidomaterno, $username, $rol, $estatus, $hashed_password, $id);
    } else {
        $stmt = $con->prepare("UPDATE usuarios SET nombre=?, apellidopaterno=?, apellidomaterno=?, username=?, rol=?, estatus=? WHERE id=?");
        $stmt->bind_param("sssssii", $nombre, $apellidopaterno, $apellidomaterno, $username, $rol, $estatus, $id);
    }
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        $volver('USUARIO EDITADO', 'Usuario editado exitosamente', 'success');
    }
    $volver('ERROR AL EDITAR', 'Notifica a soporte', 'error');
}


if (isset($_POST['save'])) {

    $nombre = trim($_POST['nombre'] ?? '');
    $apellidopaterno = trim($_POST['apellidopaterno'] ?? '');
    $apellidomaterno = trim($_POST['apellidomaterno'] ?? '');
    $email = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $estatus = 1;

    if (!in_array($rol, ROLES_VALIDOS, true)) {
        $volver('ERROR', 'Selecciona un rol válido', 'error');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $volver('ERROR', 'Correo no válido', 'error');
    }
    $errores = validar_password($password);
    if ($errores) {
        $volver('CONTRASEÑA NO VÁLIDA', implode(' ', $errores), 'error');
    }

    $rol_nombre = $rol === 'administrador' ? 'Administrador' : 'Vendedor';

    $stmt = $con->prepare("SELECT id FROM usuarios WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $existe = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    if ($existe) {
        $volver('ERROR', 'Este correo ya está registrado', 'error');
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $con->prepare("INSERT INTO usuarios (nombre, apellidopaterno, apellidomaterno, username, password, rol, estatus) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $nombre, $apellidopaterno, $apellidomaterno, $email, $hashed_password, $rol, $estatus);
        $query_run = $stmt->execute();
        $stmt->close();
        if ($query_run) {

            // Configuracion SMTP (centralizada en app/includes/mailer.php)
            $mail = nuevoCorreo('admin');
            // $mail->addReplyTo($email, $nombreuser);
            $mail->addAddress($email);
            $mail->Subject = 'NUEVO USUARIO';
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);

            // Cuerpo del mensaje (plantilla compartida + version de texto plano)
            $contenido = '<p style="margin:0 0 14px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#212529;">'
                . 'Estimado/a ' . e($nombre) . ',</p>'
                . '<p style="margin:0 0 14px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#212529;">'
                . 'Tu cuenta para gestionar el catálogo de productos y servicios de ' . e(APP_NAME) . ' se creó exitosamente.</p>'
                . '<p style="margin:0 0 20px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#6c757d;">'
                . 'Por seguridad no compartas tus credenciales con nadie.</p>'
                . renderEmailCaja([
                    'Nombre'     => trim($nombre . ' ' . $apellidopaterno . ' ' . $apellidomaterno),
                    'Correo'     => $email,
                    'Contraseña' => $password,
                    'Rol'        => $rol_nombre,
                ], 'Conoce los detalles de tu cuenta')
                . '<p style="margin:0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#6c757d;">'
                . 'Atentamente,<br><strong style="color:#212529;">Equipo administrativo</strong></p>';

            adjuntarLogoCorreo($mail);

            $mail->Body = renderEmail('Solicitud para colaborar', $contenido, [
                'preheader'   => 'Tu cuenta de ' . APP_NAME . ' se creó exitosamente',
                'boton_texto' => 'Iniciar sesión',
                'boton_url'   => url('login.php'),
            ]);

            $mail->AltBody = "Solicitud para colaborar\n\n"
                . "Estimado/a " . $nombre . ",\n\n"
                . "Tu cuenta para gestionar el catálogo de productos y servicios de " . APP_NAME . " se creó exitosamente.\n"
                . "Por seguridad no compartas tus credenciales con nadie.\n\n"
                . "Conoce los detalles de tu cuenta:\n"
                . "Nombre: " . trim($nombre . ' ' . $apellidopaterno . ' ' . $apellidomaterno) . "\n"
                . "Correo: " . $email . "\n"
                . "Contraseña: " . $password . "\n"
                . "Rol: " . $rol_nombre . "\n\n"
                . "Iniciar sesión: " . url("login.php\n\n")
                . "Atentamente,\nEquipo administrativo\n\n"
                . "Este correo fue generado automáticamente, por favor no respondas a este mensaje.\n"
                . "Aviso de Privacidad: " . url('avisodeprivacidad.php');

            $correoEnviado = false;

            try {
                $correoEnviado = $mail->send();
            } catch (Exception $e) {
                error_log('Error correo: ' . $mail->ErrorInfo);
            }

            if ($query_run && $correoEnviado) {
                $_SESSION['alert'] = [
                    'title' => 'SOLICITUD EXITOSA',
                    'message' => 'Revisa tu correo electrónico',
                    'icon' => 'success'
                ];
            } else {
                $_SESSION['alert'] = [
                    'title' => 'ERROR',
                    'message' => 'El usuario se creo pero el correo no pudo enviarse',
                    'icon' => 'warning'
                ];
            }

            header("Location: " . url("admin/users.php"));
            exit(0);
        } else {
            $_SESSION['alert'] = [
                'title' => 'ERROR',
                'message' => 'Notifica a soporte',
                'icon' => 'error'
            ];
            header("Location: " . url("admin/users.php"));
            exit(0);
        }
    }
}