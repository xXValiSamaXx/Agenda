<?php
/**
 * Archivo de enrutamiento principal - index.php
 * Este es el punto de entrada de la aplicación MVC
 */

// Cargar configuración
require_once __DIR__ . '/config/config.php';

// Obtener la página solicitada
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Enrutamiento
switch ($page) {
    // Rutas públicas
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    // Rutas de autenticación
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'registrarse':
        $controller = new AuthController();
        $controller->registrar();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // Rutas de actividades académicas (requiere autenticación)
    case 'academicas':
        $controller = new ActividadController();
        $controller->index();
        break;

    case 'editar-actividad':
        $controller = new ActividadController();
        $controller->editar();
        break;

    case 'actualizar-actividad':
        $controller = new ActividadController();
        $controller->actualizar();
        break;

    case 'eliminar-actividad':
        $controller = new ActividadController();
        $controller->eliminar();
        break;

    // Rutas de administración (requiere autenticación de admin)
    case 'admin':
        $controller = new AdminController();
        $controller->index();
        break;

    case 'materias':
        $controller = new MateriaController();
        $controller->index();
        break;

    case 'carreras':
        $controller = new AdminController();
        $controller->carreras();
        break;

    case 'crear-carrera':
        $controller = new AdminController();
        $controller->crearCarrera();
        break;

    case 'actualizar-carrera':
        $controller = new AdminController();
        $controller->actualizarCarrera();
        break;

    case 'eliminar-carrera':
        $controller = new AdminController();
        $controller->eliminarCarrera();
        break;

    case 'usuarios':
        $controller = new AdminController();
        $controller->usuarios();
        break;

    case 'crear-usuario':
        $controller = new AdminController();
        $controller->crearUsuario();
        break;

    case 'actualizar-usuario':
        $controller = new AdminController();
        $controller->actualizarUsuario();
        break;

    case 'eliminar-usuario':
        $controller = new AdminController();
        $controller->eliminarUsuario();
        break;

    // Ruta por defecto
    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
?>
