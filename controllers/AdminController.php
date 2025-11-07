<?php
/**
 * Controlador de Administración
 * Maneja las funcionalidades del panel de administración
 */
class AdminController {

    public function __construct() {
        // Verificar que el usuario sea administrador
        AuthController::verificarAdmin();
    }

    /**
     * Muestra el panel de administración
     */
    public function index() {
        require_once VIEWS_PATH . 'admin/index.php';
    }

    /**
     * Muestra la gestión de carreras
     */
    public function carreras() {
        require_once VIEWS_PATH . 'admin/carreras.php';
    }
}
?>
