<?php
require_once __DIR__ . '/../includes/bootstrap.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['delete'])) {
    $registro_id = mysqli_real_escape_string($con, $_POST['delete']);

    $query = "DELETE FROM usuarios WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'message' => 'Usuario eliminado exitosamente',
            'title' => 'USUARIO ELIMINADO',
            'icon' => 'success'
        ];
        header("Location: " . BASE_URL . "/admin/users.php");
        exit(0);
    } else {
        $_SESSION['alert'] = [
            'message' => 'Notifica a soporte',
            'title' => 'ERROR AL ELIMINAR',
            'icon' => 'error'
        ];
        header("Location: " . BASE_URL . "/admin/users.php");
        exit(0);
    }
}

if (isset($_POST['update'])) {

    $id = mysqli_real_escape_string($con, $_POST['id']);
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $apellidopaterno = mysqli_real_escape_string($con, $_POST['apellidopaterno']);
    $apellidomaterno = mysqli_real_escape_string($con, $_POST['apellidomaterno']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password']; // NO escapar todavía
    $rol = mysqli_real_escape_string($con, $_POST['rol']);
    $estatus = mysqli_real_escape_string($con, $_POST['estatus']);

    // Base del update
    $query = "
        UPDATE usuarios SET
            nombre = '$nombre',
            apellidopaterno = '$apellidopaterno',
            apellidomaterno = '$apellidomaterno',
            username = '$username',
            rol = '$rol',
            estatus = '$estatus'
    ";

    // 👉 Solo si el password NO está vacío
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", password = '$hashed_password'";
    }

    $query .= " WHERE id = '$id'";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['alert'] = [
            'message' => 'Usuario editado exitosamente',
            'title' => 'USUARIO EDITADO',
            'icon' => 'success'
        ];
        header("Location: " . BASE_URL . "/admin/users.php");
        exit;
    } else {
        $_SESSION['alert'] = [
            'message' => 'Notifica a soporte',
            'title' => 'ERROR AL EDITAR',
            'icon' => 'error'
        ];
        header("Location: " . BASE_URL . "/admin/users.php");
        exit;
    }
}


if (isset($_POST['save'])) {

    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $apellidopaterno = mysqli_real_escape_string($con, $_POST['apellidopaterno']);
    $apellidomaterno = mysqli_real_escape_string($con, $_POST['apellidomaterno']);
    $email = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $rol = mysqli_real_escape_string($con, $_POST['rol']);
    $estatus = "1";

    // Verificar el rol y asignar el nombre correspondiente
    if ($rol == 1) {
        $rol_nombre = "Administrador";
    } elseif ($rol == 2) {
        $rol_nombre = "Colaborador";
    } else {
        $rol_nombre = "Otro"; // Por si acaso el rol no es 1 ni 2
    }

    $check_email_query = "SELECT * FROM usuarios WHERE username='$email' LIMIT 1";
    $result = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['alert'] = [
            'title' => 'ERROR',
            'message' => 'Este correo ya está registrado',
            'icon' => 'error'
        ];
        header("Location: " . BASE_URL . "/admin/users.php");
        exit(0);
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios SET nombre='$nombre', apellidopaterno='$apellidopaterno', apellidomaterno='$apellidomaterno', username='$email', password='$hashed_password', rol='$rol', estatus='$estatus'";

        $query_run = mysqli_query($con, $query);
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
                . 'Tu cuenta para gestionar el catálogo de productos y servicios de Mi Empresa se creó exitosamente.</p>'
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
                'preheader'   => 'Tu cuenta de Mi Empresa se creó exitosamente',
                'boton_texto' => 'Iniciar sesión',
                'boton_url'   => BASE_URL . '/login.php',
            ]);

            $mail->AltBody = "Solicitud para colaborar\n\n"
                . "Estimado/a " . $nombre . ",\n\n"
                . "Tu cuenta para gestionar el catálogo de productos y servicios de Mi Empresa se creó exitosamente.\n"
                . "Por seguridad no compartas tus credenciales con nadie.\n\n"
                . "Conoce los detalles de tu cuenta:\n"
                . "Nombre: " . trim($nombre . ' ' . $apellidopaterno . ' ' . $apellidomaterno) . "\n"
                . "Correo: " . $email . "\n"
                . "Contraseña: " . $password . "\n"
                . "Rol: " . $rol_nombre . "\n\n"
                . "Iniciar sesión: " . BASE_URL . "/login.php\n\n"
                . "Atentamente,\nEquipo administrativo\n\n"
                . "Este correo fue generado automáticamente, por favor no respondas a este mensaje.\n"
                . "Aviso de Privacidad: " . BASE_URL . '/avisodeprivacidad.php';

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

            header("Location: " . BASE_URL . "/admin/users.php");
            exit(0);
        } else {
            $_SESSION['alert'] = [
                'title' => 'ERROR',
                'message' => 'Notifica a soporte',
                'icon' => 'error'
            ];
            header("Location: " . BASE_URL . "/admin/users.php");
            exit(0);
        }
    }
}