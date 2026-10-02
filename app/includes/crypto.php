<?php
/**
 * Cifrado simétrico de datos sensibles con AES-256-GCM.
 *
 * Formato almacenado (todo en un solo campo de texto):
 *     base64( IV[12 bytes] || TAG[16 bytes] || TEXTO_CIFRADO )
 *
 * - El IV es aleatorio por cada cifrado, así el mismo texto nunca produce
 *   el mismo resultado.
 * - GCM además autentica: si el dato se altera, el tag no coincide y el
 *   descifrado falla (en lugar de devolver basura).
 * - La clave vive en .env (ENCRYPTION_KEY, 32 bytes en base64), nunca aquí.
 */

const CRYPTO_CIPHER  = 'aes-256-gcm';
const CRYPTO_IV_LEN  = 12;  // longitud recomendada del IV para GCM
const CRYPTO_TAG_LEN = 16;  // tag de autenticación de 128 bits

/** Lee y valida la clave desde .env. Lanza excepción si falta o es inválida. */
function crypto_key(): string
{
    static $key = null;
    if ($key === null) {
        $b64 = env('ENCRYPTION_KEY');
        $raw = $b64 ? base64_decode($b64, true) : false;
        if ($raw === false || strlen($raw) !== 32) {
            throw new RuntimeException('ENCRYPTION_KEY ausente o inválida (se esperan 32 bytes en base64).');
        }
        $key = $raw;
    }
    return $key;
}

/**
 * Cifra un texto. Vacío/null se devuelve como '' (campos opcionales).
 */
function encrypt_data($texto): string
{
    if ($texto === null || $texto === '') {
        return '';
    }

    $iv  = random_bytes(CRYPTO_IV_LEN);
    $tag = '';
    $ct  = openssl_encrypt((string) $texto, CRYPTO_CIPHER, crypto_key(), OPENSSL_RAW_DATA, $iv, $tag, '', CRYPTO_TAG_LEN);

    if ($ct === false) {
        throw new RuntimeException('No se pudo cifrar el dato.');
    }

    return base64_encode($iv . $tag . $ct);
}

/**
 * Descifra un valor producido por encrypt_data().
 * Devuelve '' para vacío/null y null (sin lanzar excepción) si el dato no
 * está cifrado, está incompleto o fue alterado.
 */
function decrypt_data($cifrado): ?string
{
    if ($cifrado === null || $cifrado === '') {
        return '';
    }

    $bin = base64_decode((string) $cifrado, true);
    if ($bin === false || strlen($bin) < CRYPTO_IV_LEN + CRYPTO_TAG_LEN) {
        return null; // no es base64 válido o es demasiado corto: no está cifrado
    }

    $iv  = substr($bin, 0, CRYPTO_IV_LEN);
    $tag = substr($bin, CRYPTO_IV_LEN, CRYPTO_TAG_LEN);
    $ct  = substr($bin, CRYPTO_IV_LEN + CRYPTO_TAG_LEN);

    $plano = openssl_decrypt($ct, CRYPTO_CIPHER, crypto_key(), OPENSSL_RAW_DATA, $iv, $tag);

    if ($plano === false) {
        error_log('decrypt_data: dato no cifrado, alterado o clave incorrecta');
        return null;
    }

    return $plano;
}

/** Columnas de `pedidos` que se guardan cifradas. */
const PEDIDOS_COLUMNAS_CIFRADAS = ['telefono', 'calle', 'exterior', 'interior', 'colonia', 'postal'];

/**
 * Descifra en sitio las columnas sensibles de una fila de `pedidos`.
 * Si alguna no se puede descifrar queda como '' (falla controlada).
 */
function descifrar_pedido(array $fila): array
{
    foreach (PEDIDOS_COLUMNAS_CIFRADAS as $col) {
        if (array_key_exists($col, $fila)) {
            $fila[$col] = decrypt_data($fila[$col]) ?? '';
        }
    }
    return $fila;
}
