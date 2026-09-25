<?php
require_once __DIR__ . '/../app/includes/bootstrap.php';

http_response_code(200);
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto | Mi Empresa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/menu.css">
    <link rel="shortcut icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/images/ico.ico" />
</head>

<body style="background-color: #f5f5f5;">
    <?php include APP_PATH . '/app/screens/layout/navbar.php'; ?>
    <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 70vh; margin-top: 100px;">
        <div class="text-center">
            <h3>Oops, no se pudo mostrar este producto</h3>
            <p class="text-muted">Estamos trabajando en esta página.</p>
            <a class="btn btn-primary" href="<?= BASE_URL ?>/index.php">Volver a la tienda</a>
        </div>
    </div>
    <?php include APP_PATH . '/app/screens/layout/footer.php'; ?>
</body>

</html>