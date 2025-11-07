<?php
/**
 * Controlador de Administración
 * Maneja las funcionalidades del panel de administración
 */
class AdminController {
    private $carreraModel;

    public function __construct() {
        // Verificar que el usuario sea administrador
        AuthController::verificarAdmin();
        $this->carreraModel = new Carrera();
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
        $mensaje = "";
        $tipo_mensaje = "";
        $carreras = $this->carreraModel->obtenerTodas();
        require_once VIEWS_PATH . 'admin/carreras.php';
    }

    /**
     * Crea una nueva carrera
     */
    public function crearCarrera() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $perfil_carrera = isset($_POST['perfil_carrera']) ? trim($_POST['perfil_carrera']) : '';
            $duracion = isset($_POST['duracion']) ? trim($_POST['duracion']) : '';
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

            if (empty($nombre) || empty($perfil_carrera) || empty($duracion) || empty($descripcion)) {
                $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
                $_SESSION['tipo_mensaje'] = "error";
            } else {
                $datos = [
                    'nombre' => $nombre,
                    'perfil_carrera' => $perfil_carrera,
                    'duracion' => $duracion,
                    'descripcion' => $descripcion
                ];

                if ($this->carreraModel->crear($datos)) {
                    $_SESSION['mensaje'] = "Carrera creada exitosamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    $_SESSION['mensaje'] = "Error al crear la carrera.";
                    $_SESSION['tipo_mensaje'] = "error";
                }
            }
        }

        header("Location: " . BASE_URL . "?page=carreras");
        exit();
    }

    /**
     * Actualiza una carrera existente
     */
    public function actualizarCarrera() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $perfil_carrera = isset($_POST['perfil_carrera']) ? trim($_POST['perfil_carrera']) : '';
            $duracion = isset($_POST['duracion']) ? trim($_POST['duracion']) : '';
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

            if ($id <= 0 || empty($nombre) || empty($perfil_carrera) || empty($duracion) || empty($descripcion)) {
                $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
                $_SESSION['tipo_mensaje'] = "error";
            } else {
                $datos = [
                    'nombre' => $nombre,
                    'perfil_carrera' => $perfil_carrera,
                    'duracion' => $duracion,
                    'descripcion' => $descripcion
                ];

                if ($this->carreraModel->actualizar($id, $datos)) {
                    $_SESSION['mensaje'] = "Carrera actualizada exitosamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    $_SESSION['mensaje'] = "Error al actualizar la carrera.";
                    $_SESSION['tipo_mensaje'] = "error";
                }
            }
        }

        header("Location: " . BASE_URL . "?page=carreras");
        exit();
    }

    /**
     * Elimina una carrera
     */
    public function eliminarCarrera() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

            if ($id <= 0) {
                $_SESSION['mensaje'] = "ID inválido.";
                $_SESSION['tipo_mensaje'] = "error";
            } else {
                if ($this->carreraModel->eliminar($id)) {
                    $_SESSION['mensaje'] = "Carrera eliminada exitosamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    $_SESSION['mensaje'] = "Error al eliminar la carrera.";
                    $_SESSION['tipo_mensaje'] = "error";
                }
            }
        }

        header("Location: " . BASE_URL . "?page=carreras");
        exit();
    }
}
?>
