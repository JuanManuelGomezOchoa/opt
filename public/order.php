<?php
require_once __DIR__ . '/../app/includes/bootstrap.php';

header("Content-Type: text/html; charset=UTF-8");

$identificador = isset($_GET['id']) ? trim($_GET['id']) : '';

$pedido = null;
$errorBD = false;
if ($identificador !== '') {
    try {
        $stmt = $con->prepare("SELECT * FROM pedidos WHERE identificador = ? LIMIT 1");
        $stmt->bind_param("s", $identificador);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();
        $stmt->close();
    } catch (Throwable $ex) {
        app_log_error('order.php: fallo al consultar el pedido: ' . $ex->getMessage());
        $errorBD = true;
    }
}

        $titulo = 'Estado de tu compra | ' . e(APP_NAME);
$contenido = '';

if ($errorBD) {
    http_response_code(500);
    $titulo = 'Error | ' . e(APP_NAME);
    $contenido = '
        <div class="text-center py-5">
            <h3>Ocurrió un error, inténtalo de nuevo</h3>
            <a class="btn btn-primary" href="' . url('index.php') . '">Volver a la tienda</a>
        </div>';
} elseif (!$pedido) {
    http_response_code(404);
        $titulo = 'Orden no encontrada | ' . e(APP_NAME);
    $contenido = '
        <div class="text-center py-5">
            <h3>Orden no encontrada</h3>
            <p>No pudimos localizar tu orden. Revisa el enlace o contacta a soporte.</p>
            <a class="btn btn-primary" href="' . url('index.php') . '">Volver a la tienda</a>
        </div>';
} else {
    $statusPago = $pedido['status_pago'] ?? 'Desconocido';
    $html = '
        <div class="card shadow-sm my-4">
            <div class="card-body">
                <h3 class="card-title">Gracias por tu compra</h3>
                <p class="mb-1"><strong>Identificador:</strong> ' . e($pedido['identificador']) . '</p>
                <p class="mb-1"><strong>Estado del pago:</strong> ' . e($statusPago) . '</p>';

    if (!empty($pedido['banco'])) {
        $html .= '
                <hr>
                <h5>Datos para tu pago por SPEI</h5>
                <p class="mb-1"><strong>Banco:</strong> ' . e($pedido['banco']) . '</p>
                <p class="mb-1"><strong>CLABE:</strong> ' . e($pedido['clabe']) . '</p>
                <p class="mb-1"><strong>Referencia:</strong> ' . e($pedido['referencia']) . '</p>
                <p class="mb-0"><strong>Vigencia:</strong> ' . e($pedido['vigencia']) . '</p>';
    }

    $html .= '
                <hr>
                <p class="mb-1"><strong>Total:</strong> $' . number_format((float)$pedido['total'], 2) . '</p>
                <p class="mb-0"><strong>Fecha:</strong> ' . e($pedido['fecha']) . '</p>
                <a class="btn btn-primary mt-3" href="' . url('index.php') . '">Volver a la tienda</a>
            </div>
        </div>';

    $html .= '
        <h5 class="mb-3">Productos</h5>
        <div class="table-responsive">
            <table class="table table-bordered bg-white">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>';

    // Las lineas del pedido viven en la tabla `ventas`, enlazadas por identificador
    $huboLineas = false;
    $errorLineas = false;
    try {
        $stmtLineas = $con->prepare(
            "SELECT cantidad, precio, titulo
             FROM ventas
             WHERE identificador = ?"
        );
        $stmtLineas->bind_param("s", $pedido['identificador']);
        $stmtLineas->execute();
        $resultLineas = $stmtLineas->get_result();

        while ($linea = $resultLineas->fetch_assoc()) {
            $huboLineas = true;
            $cantidadLinea = (int)$linea['cantidad'];
            $precioLinea = (float)$linea['precio'];
            $html .= '
                    <tr>
                        <td>' . e($linea['titulo'] ?? 'Producto') . '</td>
                        <td>' . $cantidadLinea . '</td>
                        <td>$' . number_format($precioLinea, 2) . '</td>
                        <td>$' . number_format($precioLinea * $cantidadLinea, 2) . '</td>
                    </tr>';
        }
        $stmtLineas->close();
    } catch (Throwable $ex) {
        app_log_error('order.php: fallo al consultar las lineas del pedido ' . $pedido['identificador'] . ': ' . $ex->getMessage());
        $errorLineas = true;
    }

    if ($errorLineas) {
        $html .= '
                    <tr><td colspan="4" class="text-center">Ocurrió un error, inténtalo de nuevo</td></tr>';
    } elseif (!$huboLineas) {
        $html .= '
                    <tr><td colspan="4" class="text-center">Sin productos registrados</td></tr>';
    }

    $html .= '
                </tbody>
            </table>
        </div>';

    $contenido = $html;
}
?>
<?php
$pageTitle = e($titulo);
include APP_PATH . '/app/screens/layout/head.php';
?>

    <?php include APP_PATH . '/app/screens/layout/navbar.php'; ?>
    <div class="container-fluid order-container">
        <?= $contenido ?>
    </div>
    <?php include APP_PATH . '/app/screens/layout/footer.php'; ?>
</body>

</html>