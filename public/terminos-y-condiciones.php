<?php
require_once __DIR__ . '/../app/includes/bootstrap.php';

header("Content-Type: text/html; charset=UTF-8");

$legal = require __DIR__ . '/../app/config/legal.php';

$nombreComercial = $legal['nombre_comercial'];
$correo = $legal['correo'];
$plazoDevolucion = (string) $legal['plazo_devolucion_dias'];
$fechaActualizacion = $legal['fecha_actualizacion'];
$notaAcademica = $legal['nota_academica'];
?>
<?php
$pageTitle = e('Términos y Condiciones | ' . $nombreComercial);
include APP_PATH . '/app/screens/layout/head.php';
?>

<?php include APP_PATH . '/app/screens/layout/navbar.php'; ?>

<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <h1 class="mb-4">Términos y Condiciones</h1>

            <section>
                <h2 class="h5">1. Aceptación</h2>
                <p>
                    Al utilizar <?= e($nombreComercial) ?> aceptas estos Términos y Condiciones.
                </p>
            </section>

            <section>
                <h2 class="h5">2. Accesos y contraseñas</h2>
                <p>
                    Las compras en la tienda no requieren cuenta de cliente. Los accesos al
                    panel administrativo son personales e intransferibles: cada usuario es
                    responsable de la confidencialidad de su contraseña. Si la pierdes o
                    sospechas de un uso indebido, avisa de inmediato a <?= e($correo) ?>; un
                    Administrador atenderá tu solicitud y te indicará cómo establecer una
                    nueva contraseña. No nos hacemos responsables por accesos derivados de la
                    negligencia en el manejo de credenciales.
                </p>
            </section>

            <section>
                <h2 class="h5">3. Pagos</h2>
                <p>
                    Los pagos se procesan mediante OpenPay, con tarjeta o transferencia SPEI. No
                    almacenamos los datos completos de tu tarjeta. Una disputa relacionada con
                    el procesamiento del pago deberá dirigirse inicialmente a OpenPay. En pagos
                    por SPEI, el pedido se considera pagado cuando se confirma el acreditamiento
                    del pago dentro de la vigencia indicada; hasta entonces tu pedido aparece con
                    estatus pendiente.
                </p>
            </section>

            <section>
                <h2 class="h5">4. Disponibilidad</h2>
                <p>
                    Procuramos mantener el servicio disponible las 24 horas, pero no
                    garantizamos que sea ininterrumpido. Podemos suspenderlo temporalmente por
                    mantenimiento, fallas técnicas o fuerza mayor, sin responsabilidad por los
                    daños derivados.
                </p>
            </section>

            <section>
                <h2 class="h5">5. Precios y existencias</h2>
                <p>
                    Los precios y la disponibilidad pueden cambiar sin previo aviso. Podemos
                    cancelar pedidos por error en el precio, falta de existencias o sospecha de
                    fraude; en esos casos reembolsaremos el total pagado.
                </p>
            </section>

            <section>
                <h2 class="h5">6. Reembolsos</h2>
                <ul>
                    <li>
                        Pedidos no enviados: reembolso del 100 % al mismo método de pago, en un
                        plazo de 5 a 10 días hábiles.
                    </li>
                    <li>
                        Pedidos enviados: puedes solicitar la devolución dentro de
                        <?= e($plazoDevolucion) ?> días escribiendo a <?= e($correo) ?> con tu
                        identificador de pedido; el reembolso se hará al mismo método de pago
                        una vez recibido y verificado el producto.
                    </li>
                </ul>
            </section>

            <section>
                <h2 class="h5">7. Propiedad intelectual</h2>
                <p>
                    El contenido de <?= e($nombreComercial) ?> es propiedad de la empresa o de
                    sus licenciantes. Se prohíbe su reproducción o uso sin autorización previa.
                </p>
            </section>

            <section>
                <h2 class="h5">8. Limitación de responsabilidad</h2>
                <p>
                    No seremos responsables por daños indirectos, lucro cesante o pérdida de
                    datos. Nuestra responsabilidad máxima se limita al monto de la transacción
                    correspondiente.
                </p>
            </section>

            <section>
                <h2 class="h5">9. Modificaciones</h2>
                <p>
                    Podemos modificar estos términos; la versión vigente es la publicada en esta
                    página.
                </p>
            </section>

            <section>
                <h2 class="h5">10. Ley aplicable</h2>
                <p>
                    Estos términos se rigen por las leyes de México. Para cualquier
                    controversia, las partes se someten a los tribunales competentes de
                    Aguascalientes.
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
