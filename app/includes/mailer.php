<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * PHPMailer que no rompe el flujo por una dirección inválida:
 * lo registra con error_log() y deja que send() falle (los controllers ya lo capturan).
 */
if (!class_exists('MailerSeguro')) {
    class MailerSeguro extends PHPMailer
    {
        public function addAddress($address, $name = '')
        {
            try {
                return parent::addAddress($address, $name);
            } catch (Exception $e) {
                error_log('Correo: destinatario inválido');
                return false;
            }
        }
    }
}

/**
 * Devuelve un PHPMailer ya configurado con las credenciales SMTP del .env.
 *
 * @param string $area 'admin' (Gmail) o 'noreply' (dominio).
 */
if (!function_exists('nuevoCorreo')) {
    function nuevoCorreo(string $area): PHPMailer
    {
        $prefix = $area === 'noreply' ? 'SMTP_NOREPLY' : 'SMTP_ADMIN';

        $mail = new MailerSeguro(true);
        $mail->isSMTP();
        $mail->Host = env($prefix . '_HOST');
        $mail->Port = (int) env($prefix . '_PORT');
        $mail->SMTPAuth = true;
        $mail->Username = env($prefix . '_USER');
        $mail->Password = env($prefix . '_PASS');
        $mail->SMTPSecure = env($prefix . '_SECURITY', 'tls');

        try {
            $mail->setFrom(env($prefix . '_FROM_EMAIL'), env($prefix . '_FROM_NAME'));
        } catch (Exception $e) {
            error_log('Correo: remitente inválido en la configuración ' . $prefix);
        }

        return $mail;
    }
}
