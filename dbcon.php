<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$bd = "ecommerce";

$con = new mysqli($host, $usuario, $contrasena, $bd);

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

echo "Conexión exitosa";
?>