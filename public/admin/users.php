<?php
require_once __DIR__ . '/../../app/includes/bootstrap.php';

// Solo administradores (403 para cualquier otro rol)
requerir_rol(['administrador']);

$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;

if (!empty($alert)) {
    $title = isset($alert['title']) ? json_encode($alert['title']) : '"Notificación"';
    $message = isset($alert['message']) ? json_encode($alert['message']) : '""';
    $icon = isset($alert['icon']) ? json_encode($alert['icon']) : '"info"';

    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: $title,
                    " . (!empty($alert['message']) ? "text: $message," : "") . "
                    icon: $icon,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hacer algo si se confirma la alerta
                    }
                });
            });
        </script>";
    unset($_SESSION['alert']);
}

$username = $_SESSION['username'];
?>
<?php
$pageTitle = 'Usuarios | ' . e(APP_NAME);
$layout = 'panel';
$bodyClass = 'sb-nav-fixed';
$extraCss = '
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">';
include APP_PATH . '/app/screens/layout/head.php';
?>

    <?php include APP_PATH . '/app/screens/panel/sidenav.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <div class="container-fluid">
                <div class="row mb-5 mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="m-1">USUARIOS
                                    <button type="button" class="btn btn-primary btn-sm float-end btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Nuevo usuario
                                    </button>
                                </h4>
                            </div>
                            <div class="card-body" style="overflow-y:scroll;">
                                <div class="table-responsive">
                                <table id="miTabla" class="table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Correo</th>
                                            <th>Rol</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM usuarios ORDER BY id DESC";
                                        $query_run = mysqli_query($con, $query);
                                        if (mysqli_num_rows($query_run) > 0) {
                                            foreach ($query_run as $registro) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <p><?= $registro['id']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['nombre']; ?> <?= $registro['apellidopaterno']; ?> <?= $registro['apellidomaterno']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['username']; ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?= $registro['rol'] === 'administrador' ? 'Administrador/a' : 'Vendedor/a'; ?></p>
                                                    </td>
                                                    <td>
                                                        <?php


                                                        // ============================
                                                        // BOTÓN EDITAR
                                                        // ============================

                                                        // Esta pantalla es solo para administradores: pueden editar todos
                                                        ?>
                                                            <a href="editarusuario.php?id=<?= $registro['id']; ?>" class="btn btn-outline-secondary btn-sm m-1">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                        <?php

                                                        // ============================
                                                        // BOTÓN ELIMINAR
                                                        // ============================

                                                        if ($registro['id'] != 1) {
                                                        ?>
                                                            <form action="<?= url('actions/users.php') ?>" method="POST" class="d-inline">
                                                                <?= csrf_campo() ?> <!-- V3: token anti-CSRF -->
                                                                <button type="submit" name="delete" value="<?= $registro['id']; ?>" class="btn btn-outline-danger btn-sm m-1">
                                                                    <i class="bi bi-trash-fill"></i>
                                                                </button>
                                                            </form>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>

                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<td colspan='5'><p> No se encontro ningun usuario </p></td>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">NUEVO USUARIO</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= url('actions/users.php') ?>" method="POST" class="row">
                        <?= csrf_campo() ?> <!-- V3: token anti-CSRF -->

                        <div class="col-12 col-md-12 form-floating mb-3">
                            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" autocomplete="off" required>
                            <label for="nombre">Nombre</label>
                        </div>

                        <div class="col-12 col-md-6 form-floating mb-3">
                            <input type="text" class="form-control" name="apellidopaterno" id="apellidopaterno" placeholder="Apellido paterno" autocomplete="off" required>
                            <label for="apellidopaterno">Apellido paterno</label>
                        </div>

                        <div class="col-12 col-md-6 form-floating mb-3">
                            <input type="text" class="form-control" name="apellidomaterno" id="apellidomaterno" placeholder="Apellido materno" autocomplete="off" required>
                            <label for="apellidomaterno">Apellido materno</label>
                        </div>

                        <div class="col-12 form-floating mb-3">
                            <input type="email" class="form-control" name="username" id="username" placeholder="Correo" autocomplete="off" required>
                            <label for="username">Correo</label>
                        </div>

                        <div class="col-12 col-md-7 form-floating mb-3">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" autocomplete="new-password" data-password-policy data-feedback="password-feedback" required>
                            <label for="password">Contraseña</label>
                        </div>

                        <div class="col-12 col-md-5 form-floating mb-3 rolcol">
                            <select class="form-select" name="rol" id="rol" autocomplete="off" required>
                                <option selected disabled>Seleccione el rol</option>
                                <option value="administrador">Administrador</option>
                                <option value="vendedor">Vendedor</option>
                            </select>
                            <label for="rol">Rol</label>
                        </div>

                        <div class="col-12 mb-3" id="password-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="save">Guardar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
    <script src="<?= asset('js/password-policy.js') ?>"></script>
    <script>
        $(document).ready(function() {
            $('#miTabla').DataTable({
                "order": [
                    [0, "desc"]
                ]
            });
        });
    </script>

</body>

</html>