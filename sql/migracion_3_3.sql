-- MIGRACIÓN ACTIVIDAD 3.3 — RBAC (rol INT -> ENUM texto)
-- Base: ecommerce (MariaDB). HAZ RESPALDO ANTES (sql/respaldo_antes_migracion.sql).
--
-- `rol` era INT (1 = administrador, 2 = colaborador, 3 = cliente). Se convierte a
-- ENUM('administrador','vendedor') en tres pasos; un MODIFY directo de INT a ENUM
-- dejaría los valores en '' o fallaría.
--
-- Re-ejecutable: el CASE conserva 'administrador' si ya es texto (sin eso, volver a
-- correr el script degradaría a todos a 'vendedor').
-- Nota: el DDL de MySQL/MariaDB hace commit implícito, así que no hay transacción
-- real. Si algo falla a la mitad, `rol` queda como VARCHAR con valores válidos o
-- '1','2','3' y basta con volver a ejecutar el script completo.
--
-- Las columnas intentos_fallidos y bloqueado_hasta ya existen en esta BD. En otra
-- instalación agrégalas antes con:
--   ALTER TABLE usuarios
--     ADD COLUMN IF NOT EXISTS intentos_fallidos TINYINT UNSIGNED NOT NULL DEFAULT 0,
--     ADD COLUMN IF NOT EXISTS bloqueado_hasta DATETIME NULL DEFAULT NULL;

-- 1) INT -> VARCHAR (los números pasan a texto: 1 -> '1')
ALTER TABLE usuarios MODIFY rol VARCHAR(20) NOT NULL DEFAULT 'vendedor';

-- 2) Mapear valores. Todo lo que no sea administrador queda en 'vendedor' (mínimo privilegio),
--    así ningún usuario puede quedar con rol vacío o inválido.
UPDATE usuarios
SET rol = CASE rol
    WHEN '1'             THEN 'administrador'
    WHEN 'administrador' THEN 'administrador'
    WHEN '2'             THEN 'vendedor'
    ELSE 'vendedor'
END;

-- 3) VARCHAR -> ENUM (debe coincidir con ROLES_VALIDOS en app/includes/auth.php)
ALTER TABLE usuarios
    MODIFY rol ENUM('administrador','vendedor') NOT NULL DEFAULT 'vendedor';
