<?php
require_once __DIR__ . '/../includes/bootstrap.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['finalizar'])) {

    $identificador = mysqli_real_escape_string($con, $_POST['identificador']);
    $guia = mysqli_real_escape_string($con, $_POST['guia']);
    $estatus = 0;

    $query = "UPDATE `pedidos` SET `estatus` = '$estatus', `guia` = '$guia' WHERE `pedidos`.`identificador` = '$identificador'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {


        $queryPedido = "SELECT * FROM pedidos WHERE identificador = '$identificador' LIMIT 1";

        $resultPedido = mysqli_query($con, $queryPedido);

        if (!$resultPedido || mysqli_num_rows($resultPedido) === 0) {
            error_log('orders.php: pedido no encontrado: ' . $identificador);
            $_SESSION['alert'] = [
                'title'   => 'ERROR',
                'message' => 'No se encontró el pedido',
                'icon'    => 'error'
            ];
            header("Location: " . BASE_URL . "/admin/approved-orders.php");
            exit;
        }

        $pedido = mysqli_fetch_assoc($resultPedido);

        $nombre       = $pedido['nombre'];
        $apellidop    = $pedido['apellidop'];
        $apellidom    = $pedido['apellidom'];
        $email        = $pedido['email'];
        $telefono     = $pedido['telefono'];

        $calle = $pedido['calle'] . ' #' . $pedido['exterior'] . ' ' . $pedido['interior'] .
            ', ' . $pedido['colonia'] . ', ' . $pedido['ciudad'] . ', ' .
            $pedido['estado'] . ' CP ' . $pedido['postal'];

        $subtotal     = (float)$pedido['subtotal'];
        $cuponMonto   = (float)$pedido['cuponMonto'];
        $envioMonto   = (float)$pedido['envioMonto'];
        $total        = (float)$pedido['total'];

        // Configuracion SMTP (centralizada en app/includes/mailer.php)
        $mail = nuevoCorreo('admin');
        // $mail->addReplyTo($email, $nombreuser);
        $mail->addAddress($email);
        $mail->Subject = 'PEDIDO' . ' ' . $identificador;
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        

        $queryVentas = "
    SELECT cantidad, titulo, sku, subtitulo, detalles, precio, descuento
    FROM ventas
    WHERE identificador = '$identificador'
";

        $resultVentas = mysqli_query($con, $queryVentas);

        $filasProductos = [];

        if (mysqli_num_rows($resultVentas) > 0) {

            while ($row = mysqli_fetch_assoc($resultVentas)) {

                $cantidad  = (int)$row['cantidad'];
                $precioU   = (float)$row['precio'];
                $descuentoU = (float)$row['descuento'];

                $precioTotal    = $precioU * $cantidad;
                $descuentoTotal = $descuentoU * $cantidad;

                $filasProductos[] = [
                    'cantidad'  => $cantidad,
                    'titulo'    => (string)$row['titulo'],
                    'subtitulo' => (string)$row['subtitulo'],
                    'sku'       => (string)$row['sku'],
                    'detalles'  => (string)$row['detalles'],
                    'importe'   => number_format($precioTotal, 2),
                    'nota'      => $descuentoTotal > 0 ? 'Descuento -$' . number_format($descuentoTotal, 2) : '',
                ];
            }
        }

        $lineasTotales = [];
        $lineasTotales[] = ['Subtotal', '$' . number_format($subtotal, 2)];

        if ($cuponMonto > 0) {
            $lineasTotales[] = ['Cupón', '-$' . number_format($cuponMonto, 2)];
        }

        if ($envioMonto > 0) {
            $lineasTotales[] = ['Envío', '$' . number_format($envioMonto, 2)];
        } else {
            $lineasTotales[] = ['Envío', 'GRATIS'];
        }


        $contenido = renderEmailCaja([
            'Pedido ID'   => $identificador,
            'Nombre'      => trim($nombre . ' ' . $apellidop . ' ' . $apellidom),
            'Teléfono'    => $telefono,
            'Domicilio'   => $calle,
        ], 'Resumen del pedido')
            . '<p style="margin:0 0 12px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#212529;">'
            . 'TUS PRODUCTOS:</p>'
            . renderEmailTablaProductos($filasProductos)
            . renderEmailTotales($lineasTotales, 'Total', '$' . number_format($total, 2))
            . (!empty($guia)
                ? '<p style="margin:0 0 8px 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#212529;">'
                    . '<strong>Guía de rastreo:</strong> <a href="' . e($guia) . '" target="_blank" '
                    . 'style="color:#dc3545;text-decoration:underline;">' . e($guia) . '</a></p>'
                : '')
            . '<p style="margin:16px 0 0 0;font-family:' . EMAIL_FUENTE . ';font-size:15px;line-height:1.6;color:#6c757d;">'
            . 'Atentamente,<br><strong style="color:#212529;">MIEMPRESA</strong></p>';

        adjuntarLogoCorreo($mail);

        $mail->Body = renderEmail('PEDIDO ' . $identificador, $contenido, [
            'preheader'   => 'Tu pedido ' . $identificador . ($guia !== '' ? ' va en camino' : ' fue actualizado'),
            'boton_texto' => 'Ver mi pedido',
            'boton_url'   => STORE_URL . '/order.php?id=' . urlencode($identificador),
        ]);

        $alt = "PEDIDO " . $identificador . "\n\n"
            . "Resumen del pedido\n"
            . "Pedido ID: " . $identificador . "\n"
            . "Nombre: " . trim($nombre . ' ' . $apellidop . ' ' . $apellidom) . "\n"
            . "Telefono: " . $telefono . "\n"
            . "Domicilio: " . $calle . "\n\n"
            . "TUS PRODUCTOS:\n";
        foreach ($filasProductos as $f) {
            $alt .= "- " . $f['cantidad'] . " x " . $f['titulo'] . "  $" . $f['importe'] . "\n";
            if (!empty($f['nota'])) {
                $alt .= "  " . $f['nota'] . "\n";
            }
        }
        $alt .= "\n";
        foreach ($lineasTotales as $linea) {
            $alt .= $linea[0] . ": " . $linea[1] . "\n";
        }
        $alt .= "Total: $" . number_format($total, 2) . "\n\n";
        if (!empty($guia)) {
            $alt .= "Guia de rastreo: " . $guia . "\n\n";
        }
        $alt .= "Ver mi pedido: " . STORE_URL . "/order.php?id=" . urlencode($identificador) . "\n\n"
            . "Atentamente,\nMIEMPRESA\n\n"
            . "Este correo fue generado automáticamente, por favor no respondas a este mensaje.\n"
            . "Aviso de Privacidad: " . BASE_URL . '/avisodeprivacidad.php';

        $mail->AltBody = $alt;

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
                'message' => 'El pedido se actualizó pero el correo no pudo enviarse',
                'icon' => 'warning'
            ];
        }

        header("Location: " . BASE_URL . "/admin/approved-orders.php");
        exit(0);
    } else {
        header("Location: " . BASE_URL . "/admin/approved-orders.php");
        exit(0);
    }
}


if (isset($_POST['save'])) {

    mysqli_begin_transaction($con);

    try {

      
        $resCom = mysqli_query($con, "SELECT valoruno FROM configuraciones WHERE id=4 LIMIT 1");
        $comData = mysqli_fetch_assoc($resCom);
        $comisionValor = str_replace('%', '', $comData['valoruno']);
        $comisionFactor = (float)$comisionValor / 100;

        $productos = json_decode($_POST['cartLS'], true);
        if (!is_array($productos)) {
            throw new Exception("Carrito inválido");
        }

       
        $nombre     = mysqli_real_escape_string($con, $_POST['nombre']);
        $apellidop  = mysqli_real_escape_string($con, $_POST['apellidop']);
        $apellidom  = mysqli_real_escape_string($con, $_POST['apellidom']);
        $email = mysqli_real_escape_string(
            $con,
            strtolower(trim($_POST['email']))
        );
        $telefono   = mysqli_real_escape_string($con, $_POST['telefono']);
        $calle      = mysqli_real_escape_string($con, $_POST['calle']);
        $exterior   = mysqli_real_escape_string($con, $_POST['exterior']);
        $interior   = mysqli_real_escape_string($con, $_POST['interior']);
        $colonia    = mysqli_real_escape_string($con, $_POST['colonia']);
        $ciudad     = mysqli_real_escape_string($con, $_POST['ciudad']);
        $estado     = mysqli_real_escape_string($con, $_POST['estado']);
        $postal     = mysqli_real_escape_string($con, $_POST['postal']);
        $pais       = mysqli_real_escape_string($con, $_POST['pais']);
        $cupon      = mysqli_real_escape_string($con, $_POST['cuponLS']);

        $productos = json_decode($_POST['cartLS'], true);
        if (!is_array($productos)) {
            throw new Exception("Carrito inválido");
        }

        $estatus = 1;
        $subtotal = 0;
        $descuentoTotal = 0;
        $cuponMonto = 0;
        $envioMonto = 0;

        $productosAjustados = [];
        $alertasStock = [];

        
        if (!mysqli_query($con, "
            INSERT INTO pedidos
            (nombre, apellidop, apellidom, email, telefono, calle, exterior, interior, colonia, ciudad, estado, postal, pais, cupon, estatus)
            VALUES
            ('$nombre','$apellidop','$apellidom','$email','$telefono','$calle','$exterior','$interior','$colonia','$ciudad','$estado','$postal','$pais','$cupon','$estatus')
        ")) {
            throw new Exception(mysqli_error($con));
        }

        $pedido_id = mysqli_insert_id($con);

       
        $folio = str_pad($pedido_id, 7, "0", STR_PAD_LEFT);
        $iniciales = strtoupper(
            substr($nombre, 0, 1) .
                substr($apellidop, 0, 1) .
                substr($apellidom, 0, 1)
        );
        $identificador = "MIEMPRESA-$folio-$iniciales";

       
        foreach ($productos as &$item) {

            $id = (int)$item['id'];
            $cantidadSolicitada = (int)$item['cantidad'];

            $res = mysqli_query($con, "
                SELECT * FROM productosventa
                WHERE id = $id
                FOR UPDATE
            ");
            $producto = mysqli_fetch_assoc($res);

            if (!$producto) {
                throw new Exception("Producto no encontrado");
            }

            $stock = (int)$producto['stock'];
            $cantidadFinal = min($cantidadSolicitada, $stock);

            if ($cantidadFinal < $cantidadSolicitada) {
                $alertasStock[] = [
                    'titulo' => $producto['titulo'],
                    'cantidad' => $cantidadFinal
                ];
            }

            if ($cantidadFinal <= 0) continue;


            if ($producto['cantidadmayoreo'] > 0 && $cantidadFinal >= $producto['cantidadmayoreo']) {
                $precioBase = $producto['preciomayoreo'];
                $mayoreo = "Si";
                $descuentoReal = 0; 
            } else {
                $precioBase = $producto['preciounitario'];
                $mayoreo = "No";
                $descuentoReal = $producto['descuento'];
            }

           
            $precioConComision = $precioBase * (1 + $comisionFactor);

            $subtotal += $cantidadFinal * $precioConComision;
            $descuentoTotal += $cantidadFinal * $descuentoReal;
            if (!mysqli_query($con, "
                INSERT INTO ventas
                (identificador, producto_id, titulo, subtitulo, detalles, sku, cantidad, mayoreo, precio, descuento)
                VALUES
                ('$identificador','$id','{$producto['titulo']}','{$producto['subtitulo']}','{$producto['detalles']}','{$producto['sku']}',
                 '$cantidadFinal','$mayoreo','$precioConComision','$descuentoReal')
            ")) {
                throw new Exception(mysqli_error($con));
            }

            if (!mysqli_query($con, "
                UPDATE productosventa
                SET stock = stock - $cantidadFinal
                WHERE id = $id
            ")) {
                throw new Exception(mysqli_error($con));
            }

            $item['cantidad'] = $cantidadFinal;
            $productosAjustados[] = $item;
        }

       
        $montoBase = $subtotal - $descuentoTotal;

        if (!empty($cupon)) {
            $resCupon = mysqli_query($con, "
                SELECT * FROM cupones
                WHERE codigo = '$cupon' AND estatus = 1
                LIMIT 1
            ");
            $cuponData = mysqli_fetch_assoc($resCupon);

            if ($cuponData) {
                $resCanjes = mysqli_query($con, "
                    SELECT COUNT(*) AS usados
                    FROM cuponescanjeados
                    WHERE codigo = '$cupon'
                ");
                $canjes = mysqli_fetch_assoc($resCanjes)['usados'];

                if ($canjes < $cuponData['canjes'] && $montoBase >= $cuponData['minimo']) {
                    $calc = ($cuponData['porcentaje'] / 100) * $montoBase;
                    $cuponMonto = min($calc, $cuponData['maximo']);
                } else {
                    $cupon = NULL;
                }
            } else {
                $cupon = NULL;
            }
        } else {
            $cupon = NULL;
        }

       
        $montoEnvioBase = $subtotal - $descuentoTotal - $cuponMonto;

        $resConfig = mysqli_query($con, "
            SELECT valoruno, valordos
            FROM configuraciones
            WHERE id = 1
            LIMIT 1
        ");
        $config = mysqli_fetch_assoc($resConfig);

        if ($montoEnvioBase < $config['valoruno']) {
            $envioMonto = $config['valordos'];
        }

        
        $total = $montoEnvioBase + $envioMonto;
        if ($total < 0) $total = 0;

      
        if (!mysqli_query($con, "
            UPDATE pedidos SET
                identificador = '$identificador',
                productos = '" . json_encode($productosAjustados) . "',
                cupon = " . ($cupon ? "'$cupon'" : "NULL") . ",
                subtotal = '$subtotal',
                descuentoTotal = '$descuentoTotal',
                cuponMonto = '$cuponMonto',
                envioMonto = '$envioMonto',
                total = '$total'
            WHERE id = '$pedido_id'
        ")) {
            throw new Exception(mysqli_error($con));
        }

      
        if ($cuponMonto > 0 && $cupon) {
            mysqli_query($con, "
                INSERT INTO cuponescanjeados (codigo, identificador, monto)
                VALUES ('$cupon','$identificador','$cuponMonto')
            ");
        }

        mysqli_commit($con);

        header("Location: " . BASE_URL . "/payment.php?id=$identificador");
        exit;
    } catch (Exception $e) {

        mysqli_rollback($con);
        // echo "<pre>ERROR:\n" . $e->getMessage() . "</pre>";
        error_log($e->getMessage());
        header("Location: " . BASE_URL . "/checkout.php");
        exit;
    }
}