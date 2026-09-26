<?php
require_once __DIR__ . '/../includes/bootstrap.php';

use Openpay\Data\Openpay;
use Openpay\Data\OpenpayApiTransactionError;
use Openpay\Data\OpenpayApiRequestError;
use Openpay\Data\OpenpayApiConnectionError;
use Openpay\Data\OpenpayApiAuthError;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['delete'])) {
    $registro_id = mysqli_real_escape_string($con, $_POST['delete']);

    $query = "DELETE FROM pedidos WHERE id='$registro_id' ";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        header("Location: industrias.php");
        exit(0);
    } else {
        header("Location: industrias.php");
        exit(0);
    }
}



if (isset($_POST['update'])) {

    if (!isset($_POST['identificador']) || empty($_POST['identificador'])) {
        error_log('payments.php: identificador no recibido');
        $_SESSION['alert'] = [
            'title'   => 'ERROR',
            'message' => 'No se recibió el identificador del pedido',
            'icon'    => 'error'
        ];
        header('Location: ' . BASE_URL . '/checkout.php');
        exit;
    }

    $identificador = $_POST['identificador'];


    $stmt = $con->prepare("
    SELECT nombre, apellidop, apellidom, email, telefono, total
    FROM pedidos
    WHERE identificador = ?
    LIMIT 1
");

    if (!$stmt) {
        error_log('payments.php: fallo al preparar el pedido: ' . $con->error);
        $_SESSION['alert'] = [
            'title'   => 'ERROR',
            'message' => 'Ocurrió un error, inténtalo de nuevo',
            'icon'    => 'error'
        ];
        header("Location: " . BASE_URL . "/payment.php?id=$identificador");
        exit;
    }

    $stmt->bind_param("s", $identificador);
    $stmt->execute();


    $stmt->bind_result(
        $nombre,
        $apellidop,
        $apellidom,
        $email,
        $telefono,
        $total
    );

    if (!$stmt->fetch()) {
        error_log('payments.php: pedido no encontrado: ' . $identificador);
        $_SESSION['alert'] = [
            'title'   => 'ERROR',
            'message' => 'No se encontró el pedido',
            'icon'    => 'error'
        ];
        header("Location: " . BASE_URL . "/payment.php?id=$identificador");
        exit;
    }


    $pedido = [
        'nombre'     => $nombre,
        'apellidop'  => $apellidop,
        'apellidom'  => $apellidom,
        'email'      => $email,
        'telefono'   => $telefono,
        'total'      => $total
    ];
    $stmt->close();


    $openpay = Openpay::getInstance(
        env('OPENPAY_MERCHANT_ID'),
        env('OPENPAY_PRIVATE_KEY'),
        env('OPENPAY_COUNTRY'),
        $_SERVER['REMOTE_ADDR']
    );

    Openpay::setProductionMode(filter_var(env('OPENPAY_PRODUCTION_MODE', 'false'), FILTER_VALIDATE_BOOLEAN));

    $customer = [
        'name'         => $pedido['nombre'],
        'last_name'    => trim($pedido['apellidop'] . ' ' . $pedido['apellidom']),
        'phone_number' => $pedido['telefono'],
        'email'        => $pedido['email'],
    ];

    $method = $_POST['payment_method'];

   $montoFinal = number_format((float)$pedido['total'], 2, '.', '');

    try {
        if ($method === 'card') {

            $chargeData = array(
                'method'            => 'card',
                'source_id'         => $_POST["token_id"],
                'amount'            => $montoFinal,
                'description'       => 'Pedido productos #' . '' . $identificador,
                'order_id'          => $identificador . '_' . time(),
                'device_session_id' => $_POST["deviceIdHiddenFieldName"],
                'customer'          => $customer,
                'redirect_url'      => env('OPENPAY_REDIRECT_URL')
            );
        } else {

            $chargeData = array(
                'method'      => 'bank_account',
                'amount'      => $montoFinal,
                'description' => 'Pedido #' . $identificador,
                'order_id'          => $identificador . '_' . time(),
                'customer'    => $customer
            );
        }
        $charge = $openpay->charges->create($chargeData);
        if ($method === 'bank_account') {

            $vigencia = $charge->due_date;
            $bank = $charge->payment_method->bank;
            $clabe = $charge->payment_method->clabe;
            $convenio = $charge->payment_method->agreement;
            $referencia = $charge->payment_method->name;
            $url_pdf = $charge->payment_method->url_spei;

            $fechaObj = new DateTime($vigencia);

            $formateador = new IntlDateFormatter(
                'es_ES',
                IntlDateFormatter::LONG,
                IntlDateFormatter::SHORT,
                'America/Mexico_City',
                IntlDateFormatter::GREGORIAN
            );


            $vigenciaAmigable = $formateador->format($fechaObj);

            $update_stmt = $con->prepare("UPDATE pedidos SET 
        status_pago = 'Pendiente SPEI', 
        openpay_id = ?, 
        pdf_url = ?, 
        clabe = ?,
        vigencia = ?,
        banco = ?,
        convenio = ?,
        referencia = ? 
        WHERE identificador = ?");

            $update_stmt->bind_param("ssssssss", $charge->id, $url_pdf, $clabe, $vigenciaAmigable, $bank, $convenio, $referencia, $identificador);
            $update_stmt->execute();

            notifyCustomer($identificador, $email, $bank, $clabe, $convenio, $referencia, $url_pdf, $montoFinal, $vigenciaAmigable);
            header("Location: " . BASE_URL . "/order.php?id=" . $identificador);
            exit();
        } else {
            if ($charge->status == 'completed') {

                // Caso A: Pago inmediato y exitoso
                $update_stmt = $con->prepare("UPDATE pedidos SET status_pago = 'Pagado', openpay_id = ? WHERE identificador = ?");
                $update_stmt->bind_param("ss", $charge->id, $identificador);
                $update_stmt->execute();

                header("Location: " . BASE_URL . "/order.php?id=" . $identificador);
                exit();
            } else if ($charge->status == 'charge_pending') {
                // Caso B: Requiere validación 3D Secure
                // Página del banco
                if ($method === 'bank_account') {
                    header("Location: " . $charge->payment_method->url_spei);
                } else {
                    header("Location: " . $charge->payment_method->url);
                }
                exit();
            }
        }
    } catch (OpenpayApiTransactionError $e) {
        handleOpenpayError($e, $identificador);
    } catch (OpenpayApiRequestError $e) {
        handleOpenpayError($e, $identificador);
    } catch (Exception $e) {
        $_SESSION['alert'] = [
            'title'   => 'ERROR DEL SISTEMA',
            'message' => 'Contacta a soporte: ' . $e->getMessage(),
            'icon'    => 'error'
        ];
        header("Location: " . BASE_URL . "/payment.php?id=$identificador");
        exit(0);
    }

    exit(0);
}

function handleOpenpayError($e, $identificador)
{
    $errorCode = $e->getErrorCode();

    switch ($errorCode) {
        case 3001:
            $message = 'La tarjeta fue rechazada';
            break;
        case 3002:
            $message = 'La tarjeta ha expirado';
            break;
        case 3003:
            $message = 'Fondos insuficientes';
            break;
        case 3004:
            $message = 'La tarjeta fue rechazada';
            break;
        case 3005:
            $message = 'La tarjeta fue rechazada';
            break;
        case 2005:
            $message = 'La fecha de expiración es incorrecta';
            break;
        case 15001:
            $message = 'La autenticación de la tarjeta falló. Por favor, intenta con otro método de pago o contacta a tu banco.';
            break;
        default:
            $message = 'Error (' . $errorCode . '): ' . $e->getMessage();
            break;
    }

    $_SESSION['alert'] = [
        'title'   => 'PAGO NO APROBADO',
        'message' => $message,
        'icon'    => 'error'
    ];

    header("Location: " . BASE_URL . "/payment.php?id=$identificador");
    exit(0);
}

if (isset($_POST['save'])) {
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellidop = trim($_POST['apellidop'] ?? '');
    $apellidom = trim($_POST['apellidom'] ?? '');
    $email     = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $telefono  = trim($_POST['telefono'] ?? '');
    $calle     = trim($_POST['calle'] ?? '');
    $exterior  = trim($_POST['exterior'] ?? '');
    $interior  = trim($_POST['interior'] ?? '');
    $colonia   = trim($_POST['colonia'] ?? '');
    $ciudad    = trim($_POST['ciudad'] ?? '');
    $estado    = trim($_POST['estado'] ?? '');
    $postal    = trim($_POST['postal'] ?? '');
    $pais      = trim($_POST['pais'] ?? '');
    $cupon     = trim($_POST['cuponLS'] ?? '');
    $productos = $_POST['cartLS'] ?? '';
    $estatus   = 1;

    $sql = "INSERT INTO pedidos 
            (nombre, apellidop, apellidom, email, telefono, calle, exterior, interior, colonia, ciudad, estado, postal, pais, cupon, productos, estatus)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);

    $stmt->bind_param(
        "sssssssssssssssi",
        $nombre,     // 1
        $apellidop,  // 2
        $apellidom,  // 3
        $email,      // 4
        $telefono,   // 5
        $calle,      // 6
        $exterior,   // 7
        $interior,   // 8
        $colonia,    // 9
        $ciudad,     // 10
        $estado,     // 11
        $postal,     // 12
        $pais,       // 13
        $cupon,      // 14
        $productos,  // 15
        $estatus     // 16
    );

    if ($stmt->execute()) {
        $last_id = $con->insert_id;
        $stmt->close();

        // Generar Identificador
        $folio_num = str_pad($last_id, 7, "0", STR_PAD_LEFT);
        $iniciales = strtoupper(substr($nombre, 0, 1) . substr($apellidop, 0, 1) . substr($apellidom, 0, 1));
        $identificador = "MIEMPRESA-$folio_num-$iniciales";

        $up_stmt = $con->prepare("UPDATE pedidos SET identificador=? WHERE id=?");
        $up_stmt->bind_param("si", $identificador, $last_id);
        $up_stmt->execute();
        $up_stmt->close();

        header("Location: " . BASE_URL . "/payment.php?id=$identificador");
        exit(0);
    } else {
        // error_log($stmt->error); 
        header("Location: " . BASE_URL . "/checkout.php");
        exit(0);
    }
}


function notifyCustomer($identificador, $email, $bank, $clabe, $convenio, $referencia, $url_pdf, $total, $vigenciaAmigable)
{
    $mail = nuevoCorreo('noreply');
    $mail->addAddress($email);
    $mail->Subject = 'Realiza tu pago por SPEI';
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);

    $referenciaLegible = htmlspecialchars(implode(' ', str_split($referencia, 4)), ENT_QUOTES, 'UTF-8');
    $clabeLegible      = htmlspecialchars(implode(' ', str_split($clabe, 4)), ENT_QUOTES, 'UTF-8');
    $convenioLegible   = htmlspecialchars(implode(' ', str_split($convenio, 3)), ENT_QUOTES, 'UTF-8');

    $contenido = '<p style="margin:0 0 14px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#212529;">'
        . 'Estás a un paso de finalizar tu pedido, realiza tu pago por SPEI antes del '
        . '<strong>' . e($vigenciaAmigable) . '</strong> con los siguientes datos:</p>'
        . renderEmailCaja([
            'Beneficiario'          => 'DOMINIO',
            'Concepto'              => 'Pedido #' . $identificador,
            'Total a pagar'         => '$' . number_format($total, 2),
            'Banco'                 => $bank,
            'Referencia'            => $referenciaLegible,
            'CLABE (otros bancos)'  => $clabeLegible,
            'Convenio CIE (BBVA)'   => $convenioLegible,
        ], 'Datos para tu pago')
        . '<p style="margin:0 0 8px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#212529;">'
        . 'También puedes consultar la referencia de pago en tu pedido.</p>'
        . '<p style="margin:0 0 18px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#6c757d;">'
        . '¿Necesitas cambiar tu método de pago o generar una nueva referencia SPEI? Usa el botón para volver al pago.</p>'
        . '<p style="margin:0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#6c757d;">'
        . '<strong style="color:#212529;">EQUIPO DE VENTAS</strong><br>MI EMPRESA</p>';

    adjuntarLogoCorreo($mail);

    $mail->Body = renderEmail('Realiza tu pago por SPEI', $contenido, [
        'preheader'   => 'Paga tu pedido ' . $identificador . ' por SPEI antes del ' . $vigenciaAmigable,
        'boton_texto' => 'Ver referencia de pago',
        'boton_url'   => STORE_URL . '/order.php?id=' . urlencode($identificador),
    ]);

    $mail->AltBody = "Realiza tu pago por SPEI\n\n"
        . "Estas a un paso de finalizar tu pedido, realiza tu pago por SPEI antes del " . $vigenciaAmigable . " con los siguientes datos:\n\n"
        . "Datos para tu pago\n"
        . "Beneficiario: DOMINIO\n"
        . "Concepto: Pedido #" . $identificador . "\n"
        . "Total a pagar: $" . number_format($total, 2) . "\n"
        . "Banco: " . $bank . "\n"
        . "Referencia: " . $referenciaLegible . "\n"
        . "CLABE (otros bancos): " . $clabeLegible . "\n"
        . "Convenio CIE (BBVA): " . $convenioLegible . "\n\n"
        . "Consulta la referencia de pago: " . STORE_URL . "/order.php?id=" . urlencode($identificador) . "\n"
        . "Cambiar metodo de pago: " . STORE_URL . "/payment.php?id=" . urlencode($identificador) . "\n\n"
        . "EQUIPO DE VENTAS\nMI EMPRESA\n\n"
        . "Este correo fue generado automáticamente, por favor no respondas a este mensaje.\n"
        . "Aviso de Privacidad: " . BASE_URL . '/avisodeprivacidad.php';
    try {
        $mail->send();
    } catch (Exception $e) {
        error_log('Error correo cliente: ' . $e->getMessage());
    }
}