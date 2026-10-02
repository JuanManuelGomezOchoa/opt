<?php
require_once __DIR__ . '/../app/includes/bootstrap.php';

header("Content-Type: text/html; charset=UTF-8");

$legal = require __DIR__ . '/../app/config/legal.php';

$razonSocial = $legal['razon_social'];
$nombreComercial = $legal['nombre_comercial'];
$domicilio = $legal['domicilio'];
$correo = $legal['correo'];
$fechaActualizacion = $legal['fecha_actualizacion'];
$notaAcademica = $legal['nota_academica'];
?>
<?php
$pageTitle = e('Aviso de Privacidad | ' . $nombreComercial);
include APP_PATH . '/app/screens/layout/head.php';
?>

<?php include APP_PATH . '/app/screens/layout/navbar.php'; ?>

<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <h1 class="mb-4">Aviso de Privacidad</h1>

            <section>
                <h2 class="h5">1. Responsable</h2>
                <p>
                    <?= e($razonSocial) ?> (<?= e($nombreComercial) ?>), con domicilio en
                    <?= e($domicilio) ?>, es responsable del tratamiento de tus datos personales.
                </p>
            </section>

            <section>
                <h2 class="h5">2. Datos que recabamos</h2>
                <p>
                    Nombre y apellidos, correo electrónico, teléfono, dirección de envío (calle,
                    número exterior, número interior, colonia, ciudad, estado, código postal y
                    país), el contenido y monto de tu pedido, el código de cupón que llegues a
                    aplicar y los datos de la operación de pago que nos devuelve OpenPay
                    (identificador de la operación y, si eliges pago por SPEI, el banco, la
                    CLABE, el número de referencia, el convenio, la vigencia de la transferencia
                    y el enlace al comprobante de pago). No recabamos ni almacenamos número de
                    tarjeta, CVV ni fecha de expiración: estos datos los capturas en esta página
                    y se envían directamente a OpenPay para generar un token de pago; nuestro
                    servidor no los recibe. Para el personal con acceso al panel administrativo
                    guardamos nombre, usuario y una contraseña protegida mediante hash.
                </p>
            </section>

            <section>
                <h2 class="h5">3. Finalidades</h2>
                <p>
                    Procesar y entregar tus pedidos; gestionar cobros y, cuando corresponda,
                    reembolsos; enviarte correos sobre tu pedido (datos para el pago por
                    transferencia y guía de rastreo cuando el pedido es enviado); atender tus
                    solicitudes de información y soporte; y cumplir obligaciones legales y
                    fiscales. No usamos tus datos para promoción ni mercadotecnia.
                </p>
            </section>

            <section>
                <h2 class="h5">4. Terceros que reciben datos</h2>
                <p>
                    OpenPay, que procesa el pago: recibe tu nombre, apellidos, teléfono, correo
                    electrónico, el identificador de tu pedido, el monto a pagar, tu dirección IP
                    y el identificador de sesión del dispositivo. Nuestro proveedor de correo
                    electrónico, que transmite los mensajes de tu pedido. No entregamos tus
                    datos a empresas de paquetería ni de transporte: el seguimiento de tus
                    envíos se realiza mediante un enlace de rastreo que te enviamos por correo.
                    No vendemos ni rentamos tus datos personales.
                </p>
            </section>

            <section>
                <h2 class="h5">5. Cookies y almacenamiento local</h2>
                <p>
                    Usamos una cookie de sesión técnica, indispensable para el funcionamiento
                    del sitio, que se establece en tu navegador aun si no has iniciado sesión y
                    es temporal. Además guardamos en el almacenamiento local de tu navegador los
                    productos de tu carrito y el código de cupón que llegues a aplicar, sin
                    datos que te identifiquen. No usamos cookies publicitarias ni de seguimiento.
                </p>
            </section>

            <section>
                <h2 class="h5">6. Derechos ARCO y revocación</h2>
                <p>
                    Puedes solicitar Acceso, Rectificación, Cancelación u Oposición, así como
                    revocar tu consentimiento o limitar el uso de tus datos, escribiendo a
                    <?= e($correo) ?> con tu nombre, el correo y el identificador de tu pedido,
                    y el derecho que deseas ejercer. Responderemos en un plazo máximo de
                    20 días hábiles.
                </p>
            </section>

            <section>
                <h2 class="h5">7. Conservación</h2>
                <p>
                    Conservamos tus datos por el tiempo necesario para las finalidades
                    descritas y hasta 5 años después por obligaciones fiscales y legales.
                </p>
            </section>

            <section>
                <h2 class="h5">8. Seguridad</h2>
                <p>
                    Aplicamos medidas de seguridad administrativas, técnicas y físicas para
                    proteger tus datos. En particular, los datos de tu tarjeta se convierten en
                    un token de pago en tu navegador y no se guardan en nuestros servidores.
                </p>
            </section>

            <section>
                <h2 class="h5">9. Cambios</h2>
                <p>
                    Cualquier modificación a este aviso se publicará en esta misma página.
                </p>
            </section>

            <hr>

            <p class="mb-1">Última actualización: <?= e($fechaActualizacion) ?>.</p>

            <p class="small text-muted"><?= e($notaAcademica) ?></p>

        </div>
    </div>
</main>

<?php include APP_PATH . '/app/screens/layout/footer.php'; ?>
</body>

</html>
