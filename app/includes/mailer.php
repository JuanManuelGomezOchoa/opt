<?php
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Devuelve un PHPMailer ya configurado con las credenciales SMTP del .env.
 *
 * @param string $area 'admin' (Gmail) o 'noreply' (dominio).
 */
if (!function_exists('nuevoCorreo')) {
    function nuevoCorreo(string $area): PHPMailer
    {
        $prefix = $area === 'noreply' ? 'SMTP_NOREPLY' : 'SMTP_ADMIN';

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = env($prefix . '_HOST');
        $mail->Port = (int) env($prefix . '_PORT');
        $mail->SMTPAuth = true;
        $mail->Username = env($prefix . '_USER');
        $mail->Password = env($prefix . '_PASS');
        $mail->SMTPSecure = env($prefix . '_SECURITY', 'tls');
        $mail->setFrom(env($prefix . '_FROM_EMAIL'), env($prefix . '_FROM_NAME'));

        return $mail;
    }
}