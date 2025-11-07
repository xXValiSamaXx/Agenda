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

// Rutas de la aplicación
define('BASE_PATH', dirname(__DIR__));
define('MODELS_PATH', BASE_PATH . '/models/');
define('VIEWS_PATH', BASE_PATH . '/views/');
define('CONTROLLERS_PATH', BASE_PATH . '/controllers/');
define('PUBLIC_PATH', BASE_PATH . '/public/');

// URL base de la aplicación
define('BASE_URL', '/Agenda/');

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
