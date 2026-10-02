<?php
// head.php — Cabecera única para todo el sitio.
// Orden de carga garantizado: Bootstrap (o sidenav.css en el panel) →
// variables.css → styles.css (→ menu.css y extras).
// Variables opcionales (definir antes del include):
//   $pageTitle  string  Título de la pestaña.
//   $layout     string  'store' | 'panel' | 'standalone'  (por defecto 'store').
//   $extraCss   string  HTML con links CSS que van DESPUÉS de styles.css.
//   $bodyClass  string  Clases extra para <body>.
$pageTitle  = $pageTitle  ?? '';
$layout     = $layout     ?? 'store';
$extraCss   = $extraCss   ?? '';
// Las páginas de tienda tienen navbar fijo: se compensa con body.has-navbar.
$bodyClass  = $bodyClass  ?? (($layout === 'store') ? 'has-navbar' : '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/images/favicon.svg">
    <?php if ($layout === 'panel'): ?>
        <!-- Panel: Bootstrap v5.1 compilado (SB Admin) en sidenav.css -->
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/sidenav.css">
    <?php else: ?>
        <!-- Tienda / login: Bootstrap v5.2 CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <?php if ($layout === 'store'): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/menu.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <?php endif; ?>
    <?= $extraCss ?>
</head>
<body class="<?= $bodyClass ?>">