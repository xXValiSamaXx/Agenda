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
                    $_SESSION['user_type_id'] = $usuario['tiposusuariosid'];
                    $_SESSION['user_name'] = $usuario['nombre'];

                    // Aceptar tanto 'Admin' como 'Admi' (por compatibilidad)
                    if (strcasecmp($tipo, 'Admin') === 0 || strcasecmp($tipo, 'Admi') === 0) {
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
            $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
            $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
            $tiposusuarioid = (strpos($nombre, "Admin") === 0) ? '2' : '1';

            // Validaciones
            if (empty($nombre) || empty($email) || empty($contrasena)) {
                $mensajeError = "Por favor, complete todos los campos.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mensajeError = "Por favor, ingrese un correo electrónico válido.";
            } elseif (strlen($nombre) < 3) {
                $mensajeError = "El nombre de usuario debe tener al menos 3 caracteres.";
            } elseif (strlen($contrasena) < 6) {
                $mensajeError = "La contraseña debe tener al menos 6 caracteres.";
            } else {
                // Verificar si el usuario ya existe
                if ($this->usuarioModel->existeUsuario($nombre)) {
                    $mensajeError = "El nombre de usuario ya existe.";
                } 
                // Verificar si el email ya existe
                elseif ($this->usuarioModel->existeEmail($email)) {
                    $mensajeError = "El correo electrónico ya está registrado.";
                } 
                else {
                    $_SESSION['registro_usuario'] = [
                        'nombre' => $nombre,
                        'email' => $email,
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
        // Limpiar todas las variables de sesión
        $_SESSION = array();
        
        // Destruir la cookie de sesión si existe
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destruir la sesión
        session_destroy();
        
        // Prevenir caché del navegador
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        
        // Redirigir a la página de login (no a index.php directamente)
        header("Location: " . BASE_URL . "?page=login");
        exit();
    }

    /**
     * Verifica si el usuario está autenticado
     * @return bool
     */
    public static function verificarAutenticacion() {
        // Prevenir caché del navegador en páginas protegidas
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        
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
        
        // Verificar si el usuario es Admin (case-insensitive por si hay variaciones)
        if (!isset($_SESSION['user_type']) || 
            (strcasecmp($_SESSION['user_type'], 'Admin') !== 0 && 
             strcasecmp($_SESSION['user_type'], 'Admi') !== 0)) {
            header("Location: " . BASE_URL . "?page=academicas");
            exit();
        }
        return true;
    }
}
?>
