<?php
require_once __DIR__ . '/../includes/bootstrap.php';

header("Content-Type: application/json; charset=UTF-8");

$codigo   = trim($_POST['codigo'] ?? '');
$subtotal = (float) ($_POST['subtotal'] ?? 0);

if ($codigo === '') {
    echo json_encode(['ok' => false, 'msg' => 'Ingresa un código de cupón']);
    exit;
}

$codigo = mysqli_real_escape_string($con, $codigo);

$query = "SELECT * FROM cupones WHERE codigo = '$codigo' AND estatus = 1 LIMIT 1";
$result = mysqli_query($con, $query);

if (!$result) {
    error_log('coupon.php: error al validar cupón: ' . mysqli_error($con));
    echo json_encode(['ok' => false, 'msg' => 'Error al validar el cupón']);
    exit;
}

if (mysqli_num_rows($result) === 0) {
    echo json_encode(['ok' => false, 'msg' => 'El cupón no existe o no está activo']);
    exit;
}

$cupon = mysqli_fetch_assoc($result);

$queryCanjes = "SELECT COUNT(*) AS usados FROM cuponescanjeados WHERE codigo = '$codigo'";
$resultCanjes = mysqli_query($con, $queryCanjes);
$canjes = $resultCanjes ? mysqli_fetch_assoc($resultCanjes)['usados'] : 0;

if ((int)$canjes >= (int)$cupon['canjes']) {
    echo json_encode(['ok' => false, 'msg' => 'El cupón ya alcanzó su límite de usos']);
    exit;
}

if ($subtotal < (float)$cupon['minimo']) {
    echo json_encode(['ok' => false, 'msg' => 'El cupón aplica a partir de $' . number_format((float)$cupon['minimo'], 2)]);
    exit;
}

$descuento = min(((float)$cupon['porcentaje'] / 100) * $subtotal, (float)$cupon['maximo']);

echo json_encode(['ok' => true, 'descuento' => round($descuento, 2)]);