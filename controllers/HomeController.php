<?php
/**
 * Controlador de Página de Inicio
 * Maneja la página pública de inicio
 */
class HomeController {

    /**
     * Muestra la página de inicio
     */
    public function index() {
        require_once VIEWS_PATH . 'home/index.php';
    }
}
?>
