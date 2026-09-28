<?php
/**
 * legal.php — Datos del responsable y parámetros de los documentos legales.
 *
 * Fuente única: la leen public/avisodeprivacidad.php y
 * public/terminos-y-condiciones.php. Ningún otro archivo debe repetir estos valores.
 *
 * El correo NO vive aquí: se lee del .env con env('LEGAL_CONTACT_EMAIL'). La función
 * env() de app/config/config.php devuelve null tanto si la variable no existe como si
 * existe vacía, por eso normalizamos los tres casos (null, cadena vacía y solo espacios).
 *
 * Requiere bootstrap.php cargado antes (define env()).
 */

$correoConfigured = env('LEGAL_CONTACT_EMAIL');
$correo = ($correoConfigured === null || trim($correoConfigured) === '')
    ? '[correo no configurado]'
    : $correoConfigured;

return [
    'razon_social'          => 'Comercializadora Amigos Digitales, S.A. de C.V.',
    'nombre_comercial'      => 'E-commerce friends',
    'domicilio'             => 'Circuito Villas del Sol 120, Fraccionamiento Villas de la Universidad, C.P. 20000, Aguascalientes, Aguascalientes, México',
    'correo'                => $correo,
    'plazo_devolucion_dias' => 7,
    'fecha_actualizacion'   => '26/09/2026',
    'nota_academica'        => 'Documento elaborado con fines académicos para la materia Optativa III. La razón social, el domicilio y el contacto corresponden a un proyecto escolar sin operación comercial.',
];
