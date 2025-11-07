<?php
/**
 * Controlador de Administración
 * Maneja las funcionalidades del panel de administración
 */
class AdminController {
    private $carreraModel;
    private $usuarioModel;

    public function __construct() {
        // Verificar que el usuario sea administrador
        AuthController::verificarAdmin();
        $this->carreraModel = new Carrera();
        $this->usuarioModel = new Usuario();
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
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $perfil_carrera = isset($_POST['perfil_carrera']) ? trim($_POST['perfil_carrera']) : '';
            $duracion = isset($_POST['duracion']) ? trim($_POST['duracion']) : '';
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

            if (empty($nombre) || empty($perfil_carrera) || empty($duracion) || empty($descripcion)) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                    exit();
                }
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
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Carrera creada exitosamente.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Carrera creada exitosamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Error al crear la carrera.']);
                        exit();
                    }
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
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $perfil_carrera = isset($_POST['perfil_carrera']) ? trim($_POST['perfil_carrera']) : '';
            $duracion = isset($_POST['duracion']) ? trim($_POST['duracion']) : '';
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

            if ($id <= 0 || empty($nombre) || empty($perfil_carrera) || empty($duracion) || empty($descripcion)) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                    exit();
                }
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
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Carrera actualizada exitosamente.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Carrera actualizada exitosamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Error al actualizar la carrera.']);
                        exit();
                    }
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
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

            if ($id <= 0) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'ID inválido.']);
                    exit();
                }
                $_SESSION['mensaje'] = "ID inválido.";
                $_SESSION['tipo_mensaje'] = "error";
            } else {
                if ($this->carreraModel->eliminar($id)) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Carrera eliminada exitosamente.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Carrera eliminada exitosamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Error al eliminar la carrera.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Error al eliminar la carrera.";
                    $_SESSION['tipo_mensaje'] = "error";
                }
            }
        }

        header("Location: " . BASE_URL . "?page=carreras");
        exit();
    }

    /**
     * Gestión de usuarios
     */
    public function usuarios() {
        $usuarios = $this->usuarioModel->obtenerTodos();
        $tiposUsuario = $this->usuarioModel->obtenerTiposUsuario();
        require_once VIEWS_PATH . 'admin/usuarios.php';
    }

    /**
     * Crear un nuevo usuario
     */
    public function crearUsuario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Verificar si es una petición AJAX
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';
            $tiposusuarioid = isset($_POST['tiposusuarioid']) ? intval($_POST['tiposusuarioid']) : 0;

            if (empty($nombre) || empty($contrasena) || $tiposusuarioid == 0) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                    exit();
                }
                $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
                $_SESSION['tipo_mensaje'] = "error";
            } elseif ($this->usuarioModel->existeUsuario($nombre)) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya existe.']);
                    exit();
                }
                $_SESSION['mensaje'] = "El nombre de usuario ya existe.";
                $_SESSION['tipo_mensaje'] = "error";
            } else {
                $datos = [
                    'nombre' => $nombre,
                    'contrasena' => $contrasena,
                    'tiposusuarioid' => $tiposusuarioid
                ];

                if ($this->usuarioModel->crear($datos)) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Usuario creado exitosamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Error al crear el usuario.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Error al crear el usuario.";
                    $_SESSION['tipo_mensaje'] = "error";
                }
            }

            header("Location: " . BASE_URL . "?page=usuarios");
            exit();
        }
    }

    /**
     * Actualizar usuario
     */
    public function actualizarUsuario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            $id = intval($_POST['id']);
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';
            $tiposusuarioid = isset($_POST['tiposusuarioid']) ? intval($_POST['tiposusuarioid']) : 0;

            if (empty($nombre) || $tiposusuarioid == 0) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'El nombre y tipo de usuario son obligatorios.']);
                    exit();
                }
                $_SESSION['mensaje'] = "El nombre y tipo de usuario son obligatorios.";
                $_SESSION['tipo_mensaje'] = "error";
            } else {
                $datos = [
                    'nombre' => $nombre,
                    'contrasena' => $contrasena,
                    'tiposusuarioid' => $tiposusuarioid
                ];

                if ($this->usuarioModel->actualizar($id, $datos)) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Usuario actualizado exitosamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Error al actualizar el usuario.";
                    $_SESSION['tipo_mensaje'] = "error";
                }
            }

            header("Location: " . BASE_URL . "?page=usuarios");
            exit();
        }
    }

    /**
     * Eliminar usuario
     */
    public function eliminarUsuario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            $id = intval($_POST['id']);
            
            if ($id == $_SESSION['user_id']) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'No puedes eliminar tu propio usuario.']);
                    exit();
                }
                $_SESSION['mensaje'] = "No puedes eliminar tu propio usuario.";
                $_SESSION['tipo_mensaje'] = "error";
            } else {
                if ($this->usuarioModel->eliminar($id)) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Usuario eliminado exitosamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario.']);
                        exit();
                    }
                    $_SESSION['mensaje'] = "Error al eliminar el usuario.";
                    $_SESSION['tipo_mensaje'] = "error";
                }
            }

            header("Location: " . BASE_URL . "?page=usuarios");
            exit();
        }
    }
}
?>
