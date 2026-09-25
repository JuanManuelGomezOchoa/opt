<?php
require_once __DIR__ . '/app/config/config.php';

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($con->connect_error) {
    error_log('Error de conexión a BD: ' . $con->connect_error);
    die('Error al conectar con la base de datos');
}

echo "Conexión exitosa";