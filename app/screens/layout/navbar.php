<nav class="navbar navbar-expand-md navbar-light fixed-top">
  <div class="container-fluid containernav">
    <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">
      <?php include APP_PATH . '/app/screens/layout/logo.php'; ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <?php include APP_PATH . '/app/screens/layout/logo.php'; ?>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <!-- Navbar items aligned to the right -->
        <div class="navbar-nav ms-auto menucanvass">
          <p>Aqui va una descripcion para la versión movíl</p>
          <hr>
          <a class="nav-item nav-link" href="<?= BASE_URL ?>/login.php">Intranet</a>
          <a class="nav-item nav-link" href="<?= BASE_URL ?>/index.php">Tienda en línea</a>
          <a class="nav-item nav-link" href="<?= BASE_URL ?>/cart.php"><i class="fas fa-shopping-cart"></i></a>
        </div>
      </div>
    </div>
  </div>
</nav>

<script src="<?= BASE_URL ?>/assets/js/menu.js"></script>