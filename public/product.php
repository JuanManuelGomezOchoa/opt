<?php
require_once __DIR__ . '/../app/includes/bootstrap.php';

http_response_code(200);
header("Content-Type: text/html; charset=UTF-8");
?>
<?php
$pageTitle = 'Producto | Mi Empresa';
include APP_PATH . '/app/screens/layout/head.php';
?>

    <?php include APP_PATH . '/app/screens/layout/navbar.php'; ?>
    <div class="container-fluid d-flex align-items-center justify-content-center hero-empty content-top">
        <div class="text-center">
            <h3>Oops, no se pudo mostrar este producto</h3>
            <p class="text-muted">Estamos trabajando en esta página.</p>
            <a class="btn btn-primary" href="<?= BASE_URL ?>/index.php">Volver a la tienda</a>
        </div>
    </div>
    <?php include APP_PATH . '/app/screens/layout/footer.php'; ?>
</body>

</html>