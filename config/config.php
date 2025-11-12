<?php
/**
 * Archivo de configuración general de la aplicación
 */

// Zona horaria
date_default_timezone_set('America/Cancun');

// Configuración de errores (desactivar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 si se usa HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

// Configuración adicional de sesión para prevenir ataques
ini_set('session.gc_maxlifetime', 1800); // 30 minutos
ini_set('session.cookie_lifetime', 0); // Hasta que se cierre el navegador

// Rutas de la aplicación
define('BASE_PATH', dirname(__DIR__));
define('MODELS_PATH', BASE_PATH . '/models/');
define('VIEWS_PATH', BASE_PATH . '/views/');
define('CONTROLLERS_PATH', BASE_PATH . '/controllers/');
define('PUBLIC_PATH', BASE_PATH . '/public/');

// URL base de la aplicación - Detección automática
// Funciona tanto en localhost/Agenda como en servidor/raíz
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseDir = ($scriptName === '/' || $scriptName === '\\') ? '/' : rtrim($scriptName, '/') . '/';

// Definir BASE_URL completa y relativa
define('BASE_URL', $baseDir);
define('FULL_URL', $protocol . $host . $baseDir);

/*
 * Configuración de correo (PHPMailer)
 * Rellena estos valores con tus credenciales o utiliza variables de entorno
 * Si usas Gmail: crea una contraseña de aplicación y pega aquí la cadena SIN espacios.
 */
define('MAIL_HOST', getenv('MAIL_HOST') ?: 'smtp.gmail.com');
define('MAIL_USERNAME', getenv('MAIL_USERNAME') ?: 'preticor.help@gmail.com');
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD') ?: 'v7c1Mj*X6'); // PONER LA CONTRASEÑA DE APLICACIÓN AQUI (sin espacios)
define('MAIL_SMTP_SECURE', getenv('MAIL_SMTP_SECURE') ?: 'tls');
define('MAIL_PORT', getenv('MAIL_PORT') ?: 587);
define('MAIL_FROM', getenv('MAIL_FROM') ?: MAIL_USERNAME);
define('MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?: 'Mi Agenda');
define('MAIL_TO', getenv('MAIL_TO') ?: MAIL_USERNAME);

// Autoloader simple para clases
spl_autoload_register(function ($class_name) {
    $paths = [
        MODELS_PATH . $class_name . '.php',
        CONTROLLERS_PATH . $class_name . '.php',
        BASE_PATH . '/config/' . $class_name . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
