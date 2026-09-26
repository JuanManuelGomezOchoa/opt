<?php
/**
 * Plantilla compartida de correo HTML.
 *
 * renderEmail() arma el documento completo (encabezado + logo, título, cuerpo,
 * botón opcional y pie) con estilos 100% en línea para que se vea igual en
 * Gmail y Outlook. No usa flexbox, grid, SVG, JS ni CSS externo.
 *
 * Todo valor dinámico que se imprime pasa por e(); $contenidoHtml llega ya
 * construido y escapado por el controller que lo arma.
 */

use PHPMailer\PHPMailer\PHPMailer;

if (!defined('EMAIL_LOGO_CID')) {
    define('EMAIL_LOGO_CID', 'logo-ecommerce');
}

if (!defined('EMAIL_FUENTE')) {
    define('EMAIL_FUENTE', "Montserrat, 'Segoe UI', Arial, sans-serif");
}

if (!function_exists('emailLogoRuta')) {
    function emailLogoRuta(): string
    {
        return APP_PATH . '/public/assets/images/email-logo.png';
    }
}

if (!function_exists('emailLogoDisponible')) {
    function emailLogoDisponible(): bool
    {
        return defined('APP_PATH') && is_file(emailLogoRuta());
    }
}

if (!function_exists('adjuntarLogoCorreo')) {
    /**
     * Incrusta el logo como CID. Si el PNG no existe o falla, el encabezado
     * cae al texto "e-commerce" y el correo igual sale bien.
     */
    function adjuntarLogoCorreo(PHPMailer $mail): void
    {
        if (!emailLogoDisponible()) {
            return;
        }
        try {
            $mail->addEmbeddedImage(emailLogoRuta(), EMAIL_LOGO_CID);
        } catch (Throwable $e) {
            error_log('Correo: no se pudo incrustar el logo: ' . $e->getMessage());
        }
    }
}

if (!function_exists('renderEmailCaja')) {
    /**
     * Caja de datos (resumen, credenciales, datos SPEI) con fondo #f8f9fa.
     * $filas = [['Etiqueta' => 'Valor'], ...] -> se escapan con e().
     */
    function renderEmailCaja(array $filas, string $titulo = ''): string
    {
        if ($filas === []) {
            return '';
        }

        $html = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" '
            . 'style="width:100%;border-collapse:collapse;margin:0 0 20px 0;">'
            . '<tr><td style="background-color:#f8f9fa;border:1px solid #e0e0e0;border-radius:6px;padding:18px 20px;">';

        if ($titulo !== '') {
            $html .= '<p style="margin:0 0 12px 0;font-family:' . EMAIL_FUENTE . ';font-size:13px;'
                . 'font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:#6c757d;">'
                . e($titulo) . '</p>';
        }

        foreach ($filas as $etiqueta => $valor) {
            $html .= '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" '
                . 'style="width:100%;border-collapse:collapse;">'
                . '<tr>'
                . '<td style="padding:6px 12px 6px 0;font-family:' . EMAIL_FUENTE . ';font-size:14px;'
                . 'color:#6c757d;vertical-align:top;white-space:nowrap;">' . e((string) $etiqueta) . '</td>'
                . '<td style="padding:6px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;font-weight:600;'
                . 'color:#212529;text-align:right;word-break:break-word;">' . e((string) $valor) . '</td>'
                . '</tr>'
                . '<tr><td colspan="2" style="height:1px;line-height:1px;font-size:0;background-color:#e0e0e0;">&nbsp;</td></tr>'
                . '</table>';
        }

        $html .= '</td></tr></table>';

        return $html;
    }
}

if (!function_exists('renderEmailTablaProductos')) {
    /**
     * Tabla de productos con separadores finos y total destacado.
     * $filas = [['cantidad'=>..,'titulo'=>..,'subtitulo'=>..,'sku'=>..,'importe'=>..], ...]
     */
    function renderEmailTablaProductos(array $filas): string
    {
        $html = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" '
            . 'style="width:100%;border-collapse:collapse;margin:0 0 20px 0;">'
            . '<tr>'
            . '<th align="left" style="padding:0 0 8px 0;border-bottom:2px solid #212529;font-family:' . EMAIL_FUENTE . ';'
            . 'font-size:12px;letter-spacing:.6px;text-transform:uppercase;color:#6c757d;">Producto</th>'
            . '<th align="right" width="90" style="padding:0 0 8px 0;border-bottom:2px solid #212529;font-family:' . EMAIL_FUENTE . ';'
            . 'font-size:12px;letter-spacing:.6px;text-transform:uppercase;color:#6c757d;">Importe</th>'
            . '</tr>';

        foreach ($filas as $f) {
            $detalle = '';
            if (!empty($f['subtitulo'])) {
                $detalle .= '<br><span style="font-size:13px;color:#6c757d;">' . e((string) $f['subtitulo']) . '</span>';
            }
            if (!empty($f['sku'])) {
                $detalle .= '<br><span style="font-size:12px;color:#6c757d;">SKU: ' . e((string) $f['sku']) . '</span>';
            }
            if (!empty($f['detalles'])) {
                $detalle .= '<br><span style="font-size:13px;color:#6c757d;">' . e((string) $f['detalles']) . '</span>';
            }

            $nota = '';
            if (!empty($f['nota'])) {
                $nota = '<br><span style="font-size:13px;color:#dc3545;">' . e((string) $f['nota']) . '</span>';
            }

            $html .= '<tr>'
                . '<td style="padding:12px 12px 12px 0;border-bottom:1px solid #e0e0e0;font-family:' . EMAIL_FUENTE . ';'
                . 'font-size:15px;color:#212529;vertical-align:top;">'
                . '<span style="font-weight:600;">' . e((string) $f['titulo']) . '</span>'
                . $detalle
                . '</td>'
                . '<td align="right" style="padding:12px 0;border-bottom:1px solid #e0e0e0;font-family:' . EMAIL_FUENTE . ';'
                . 'font-size:15px;font-weight:600;color:#212529;white-space:nowrap;">' . e((string) $f['importe']) . $nota . '</td>'
                . '</tr>';
        }

        $html .= '</table>';

        return $html;
    }
}

if (!function_exists('renderEmailTotales')) {
    /**
     * Bloque de totales alineado a la derecha con el total destacado.
     * $lineas = [['Subtotal','$1,000.00'], ['Envío','GRATIS'], ...]
     */
    function renderEmailTotales(array $lineas, string $totalEtiqueta = 'Total', string $totalValor = ''): string
    {
        $html = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" '
            . 'style="width:100%;border-collapse:collapse;margin:0 0 20px 0;">';

        foreach ($lineas as $linea) {
            $html .= '<tr>'
                . '<td align="right" style="padding:6px 0;font-family:' . EMAIL_FUENTE . ';font-size:14px;color:#6c757d;">'
                . e((string) $linea[0]) . '</td>'
                . '<td align="right" width="120" style="padding:6px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;'
                . 'color:#212529;white-space:nowrap;">' . e((string) $linea[1]) . '</td>'
                . '</tr>';
        }

        $html .= '<tr><td colspan="2" style="height:1px;line-height:1px;font-size:0;background-color:#e0e0e0;">&nbsp;</td></tr>'
            . '<tr>'
            . '<td align="right" style="padding:12px 0 0 0;font-family:' . EMAIL_FUENTE . ';font-size:16px;'
            . 'font-weight:700;color:#212529;">' . e($totalEtiqueta) . '</td>'
            . '<td align="right" style="padding:12px 0 0 0;font-family:' . EMAIL_FUENTE . ';font-size:18px;'
            . 'font-weight:700;color:#dc3545;white-space:nowrap;">' . e($totalValor) . '</td>'
            . '</tr>';

        $html .= '</table>';

        return $html;
    }
}

if (!function_exists('renderEmailBoton')) {
    /**
     * Botón principal rojo. Bulletproof: <a> real dentro de un <td> con bgcolor,
     * para que Outlook lo pinte y lo haga clicable.
     */
    function renderEmailBoton(string $texto, string $url): string
    {
        $texto = trim($texto);
        $url = trim($url);
        if ($texto === '' || $url === '') {
            return '';
        }

        return '<table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" '
            . 'style="margin:8px auto 24px auto;">'
            . '<tr><td align="center" bgcolor="#dc3545" style="background-color:#dc3545;border-radius:6px;">'
            . '<a class="btn" href="' . e($url) . '" target="_blank" '
            . 'style="display:inline-block;padding:12px 28px;font-family:' . EMAIL_FUENTE . ';font-size:15px;'
            . 'font-weight:600;line-height:20px;color:#ffffff;text-decoration:none;border-radius:6px;">'
            . e($texto)
            . '</a>'
            . '</td></tr></table>';
    }
}

if (!function_exists('renderEmail')) {
    /**
     * Devuelve el HTML completo del correo.
     *
     * @param string $titulo        Título 22px sobre la tarjeta.
     * @param string $contenidoHtml HTML ya construido y escapado por el caller.
     * @param array  $opciones      preheader, boton_texto, boton_url, marca,
     *                              aviso, privacidad_url, mostrar_pie
     */
    function renderEmail(string $titulo, string $contenidoHtml, array $opciones = []): string
    {
        $marca = (string) ($opciones['marca'] ?? 'e-commerce');
        $preheader = trim((string) ($opciones['preheader'] ?? ''));
        $aviso = (string) ($opciones['aviso'] ?? 'Este correo fue generado automáticamente, por favor no respondas a este mensaje');
        $privacidadUrl = (string) ($opciones['privacidad_url'] ?? (BASE_URL . '/avisodeprivacidad.php'));
        $boton = '';
        if (!empty($opciones['boton_texto']) && !empty($opciones['boton_url'])) {
            $boton = renderEmailBoton((string) $opciones['boton_texto'], (string) $opciones['boton_url']);
        }

        // Preheader: oculto en la vista del correo, visible como vista previa.
        $preheaderOculto = $preheader === ''
            ? ''
            : '<div style="display:none;font-size:1px;line-height:1px;max-height:0;max-width:0;opacity:0;'
                . 'overflow:hidden;mso-hide:all;">' . e($preheader)
                . str_repeat('&#847;&zwnj;&nbsp;', 30) . '</div>';

        if (emailLogoDisponible()) {
            $encabezado = '<img src="cid:' . EMAIL_LOGO_CID . '" alt="' . e($marca) . '" width="160" '
                . 'style="display:block;margin:0 auto;border:0;width:160px;max-width:160px;height:auto;">';
        } else {
            $encabezado = '<span style="font-family:' . EMAIL_FUENTE . ';font-size:24px;font-weight:700;'
                . 'color:#dc3545;letter-spacing:-.5px;">' . e($marca) . '</span>';
        }

        $html = '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>' . e($titulo) . '</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f5f5;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">
' . $preheaderOculto . '
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f5f5f5" style="width:100%;background-color:#f5f5f5;">
<tr>
<td align="center" class="email-pad" style="padding:24px 12px;">

<table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" class="email-wrap" style="width:600px;max-width:600px;background-color:#ffffff;border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.06);">

<tr><td height="4" style="height:4px;line-height:4px;font-size:0;background-color:#dc3545;">&nbsp;</td></tr>

<tr>
<td align="center" bgcolor="#ffffff" style="padding:26px 24px 22px 24px;background-color:#ffffff;">
' . $encabezado . '
</td>
</tr>

<tr><td style="height:1px;line-height:1px;font-size:0;background-color:#e0e0e0;">&nbsp;</td></tr>

<tr>
<td class="email-pad" style="padding:28px 24px 8px 24px;">

<h1 class="h1" style="margin:0 0 16px 0;font-family:' . EMAIL_FUENTE . ';font-size:22px;line-height:1.3;font-weight:700;color:#212529;">'
            . e($titulo) . '</h1>

' . $contenidoHtml . '
' . $boton . '
</td>
</tr>

<tr><td style="height:1px;line-height:1px;font-size:0;background-color:#e0e0e0;">&nbsp;</td></tr>

<tr>
<td class="email-pad" align="center" bgcolor="#f8f9fa" style="padding:22px 24px 24px 24px;background-color:#f8f9fa;border-radius:0 0 9px 9px;">

<p style="margin:0 0 6px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;font-weight:700;color:#212529;">'
            . e($marca) . '</p>

<p style="margin:0 0 10px 0;font-family:' . EMAIL_FUENTE . ';font-size:13px;line-height:1.6;color:#6c757d;">'
            . e($aviso) . '</p>

<p style="margin:0;font-family:' . EMAIL_FUENTE . ';font-size:13px;line-height:1.6;color:#6c757d;">
<a href="' . e($privacidadUrl) . '" target="_blank" style="color:#dc3545;text-decoration:underline;">Aviso de Privacidad</a>
</p>

</td>
</tr>

</table>

</td>
</tr>
</table>
</body>
</html>';

        return $html;
    }
}
