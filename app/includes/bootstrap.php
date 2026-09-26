<?php
/**
 * Arranque común de la aplicación.
 * Carga: config (.env + constantes) -> base de datos -> sesión -> helpers -> mailer.
 * Es el ÚNICO lugar por el que se carga vendor/autoload.php (vía config.php).
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/helpers.php';
require_once APP_PATH . '/app/includes/mailer.php';
require_once __DIR__ . '/email-template.php';