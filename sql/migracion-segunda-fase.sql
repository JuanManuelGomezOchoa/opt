-- MIGRACIÓN SEGUNDA FASE (NO AUTOMÁTICA — EJECUTAR SOLO CUANDO SE CONFIRME)
-- Objetivo: apuntar los medios de producto almacenados en la BD a la nueva ruta de uploads.
-- Antes de la FASE 2 los archivos se guardaban en 'productosventa/' y la BD guardaba 'productosventa/X'.
-- Ahora el controlador (app/controllers/products.php) guarda en public/assets/uploads/products/
-- y registra en BD 'assets/uploads/products/X'.
--
-- ADVERTENCIA: ejecutar únicamente si existen filas con el prefijo viejo y se han movido/copiado
-- los archivos reales a public/assets/uploads/products/. Hacer BACKUP previo.
--
-- Puede mostrar los registros afectados primero:
--   SELECT id, medio FROM mediosventa WHERE medio LIKE 'productosventa/%';
--
-- UPDATE:

UPDATE mediosventa
SET medio = CONCAT('assets/uploads/products/', SUBSTRING_INDEX(medio, '/', -1))
WHERE medio LIKE 'productosventa/%';