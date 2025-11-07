<?php
/**
 * Controlador de Autenticación
 * Maneja el registro, login y logout de usuarios
 */
class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Muestra la página de login
     */
    public function mostrarLogin() {
        $mensajeError = "";
        require_once VIEWS_PATH . 'auth/login.php';
    }

    /**
     * Procesa el login de usuario
     */
    public function login() {
        $mensajeError = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
            $contrasenas = isset($_POST['contrasenas']) ? $_POST['contrasenas'] : '';

            if (empty($nombre) || empty($contrasenas)) {
                $mensajeError = "Por favor, ingrese nombre de usuario y contraseña.";
            } else {
                $usuario = $this->usuarioModel->login($nombre, $contrasenas);

                if ($usuario) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_id'] = $usuario['ID_usuarios'];

                    $tipo = $this->usuarioModel->getTipoUsuario($usuario['tiposusuariosid']);
                    $_SESSION['user_type'] = $tipo;

                    if ($tipo == 'Admi') {
                        header("Location: " . BASE_URL . "?page=admin");
                        exit();
                    } else {
                        header("Location: " . BASE_URL . "?page=academicas");
                        exit();
                    }
                } else {
                    $mensajeError = "Nombre de usuario o contraseña incorrectos.";
                }
            }
        }

        require_once VIEWS_PATH . 'auth/login.php';
    }

    /**
     * Muestra la página de registro
     */
    public function mostrarRegistro() {
        $mensajeError = "";
        require_once VIEWS_PATH . 'auth/registrarse.php';
    }

    /**
     * Procesa el registro de un nuevo usuario
     */
    public function registrar() {
        $mensajeError = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
            $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
            $tiposusuarioid = (strpos($nombre, "Admin") === 0) ? '2' : '1';

            if (empty($nombre) || empty($contrasena)) {
                $mensajeError = "Por favor, complete todos los campos.";
            } else {
                if ($this->usuarioModel->existeUsuario($nombre)) {
                    $mensajeError = "El nombre de usuario ya existe.";
                } else {
                    $_SESSION['registro_usuario'] = [
                        'nombre' => $nombre,
                        'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT),
                        'tiposusuarioid' => $tiposusuarioid
                    ];

                    header("Location: " . BASE_URL . "?page=informacion-personal");
                    exit();
                }
            }
        }

        require_once VIEWS_PATH . 'auth/registrarse.php';
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL . "?page=login");
        exit();
    }

    /**
     * Verifica si el usuario está autenticado
     * @return bool
     */
    public static function verificarAutenticacion() {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header("Location: " . BASE_URL . "?page=login");
            exit();
        }
        return true;
    }

    /**
     * Verifica si el usuario es administrador
     * @return bool
     */
    public static function verificarAdmin() {
        self::verificarAutenticacion();
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Admi') {
            header("Location: " . BASE_URL . "?page=academicas");
            exit();
        }
        return true;
    }
}
?>
