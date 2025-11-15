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
     * Muestra la página de registro (redirige al paso 1)
     */
    public function mostrarRegistro() {
        header("Location: " . BASE_URL . "?page=registrarse");
        exit();
    }

    /**
     * Procesa el registro de un nuevo usuario (PASO 1: Datos Personales)
     */
    public function registrarPaso1() {
        $mensajeError = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombres = isset($_POST['nombres']) ? htmlspecialchars($_POST['nombres']) : '';
            $primerapellido = isset($_POST['primerapellido']) ? htmlspecialchars($_POST['primerapellido']) : '';
            $segundoapellido = isset($_POST['segundoapellido']) ? htmlspecialchars($_POST['segundoapellido']) : '';
            $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';

            // Validaciones
            if (empty($nombres) || empty($primerapellido) || empty($fecha_nacimiento)) {
                $mensajeError = "Por favor, complete todos los campos obligatorios.";
            } else {
                // Validar edad (mínimo 18 años)
                $fechaNacimiento = new DateTime($fecha_nacimiento);
                $fechaActual = new DateTime();
                $edad = $fechaActual->diff($fechaNacimiento)->y;
                
                if ($edad < 18) {
                    $mensajeError = "Debes tener al menos 18 años para registrarte.";
                } else {
                    // Guardar en sesión y avanzar al paso 2
                    $_SESSION['registro_paso1'] = [
                        'nombres' => $nombres,
                        'primerapellido' => $primerapellido,
                        'segundoapellido' => $segundoapellido,
                        'fecha_nacimiento' => $fecha_nacimiento
                    ];

                    header("Location: " . BASE_URL . "?page=registro-paso2");
                    exit();
                }
            }
        }

        require_once VIEWS_PATH . 'auth/registro_paso1.php';
    }

    /**
     * PASO 2: Información de Contacto
     */
    public function registrarPaso2() {
        // Verificar que haya completado el paso 1
        if (!isset($_SESSION['registro_paso1'])) {
            header("Location: " . BASE_URL . "?page=registrarse");
            exit();
        }

        $mensajeError = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
            $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
            $RFC = isset($_POST['RFC']) ? htmlspecialchars($_POST['RFC']) : null;

            // Validaciones
            if (empty($telefono) || empty($email)) {
                $mensajeError = "Por favor, complete todos los campos obligatorios.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mensajeError = "Por favor, ingrese un correo electrónico válido.";
            } elseif (!preg_match('/^[0-9]{10}$/', $telefono)) {
                $mensajeError = "El teléfono debe contener exactamente 10 dígitos.";
            }
            // Verificar si el email ya existe
            elseif ($this->usuarioModel->existeEmail($email)) {
                $mensajeError = "El correo electrónico ya está registrado.";
            }
            else {
                // Guardar en sesión y avanzar al paso 3
                $_SESSION['registro_paso2'] = [
                    'telefono' => $telefono,
                    'email' => $email,
                    'RFC' => $RFC
                ];

                header("Location: " . BASE_URL . "?page=registro-paso3");
                exit();
            }
        }

        require_once VIEWS_PATH . 'auth/registro_paso2.php';
    }

    /**
     * PASO 3: Credenciales de Cuenta
     */
    public function registrarPaso3() {
        // Verificar que haya completado los pasos anteriores
        if (!isset($_SESSION['registro_paso1']) || !isset($_SESSION['registro_paso2'])) {
            header("Location: " . BASE_URL . "?page=registrarse");
            exit();
        }

        $mensajeError = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
            $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
            $confirmar_contrasena = isset($_POST['confirmar_contrasena']) ? $_POST['confirmar_contrasena'] : '';

            // Validaciones
            if (empty($nombre) || empty($contrasena) || empty($confirmar_contrasena)) {
                $mensajeError = "Por favor, complete todos los campos.";
            } elseif (strlen($nombre) < 3) {
                $mensajeError = "El nombre de usuario debe tener al menos 3 caracteres.";
            } elseif (strlen($contrasena) < 6) {
                $mensajeError = "La contraseña debe tener al menos 6 caracteres.";
            } elseif ($contrasena !== $confirmar_contrasena) {
                $mensajeError = "Las contraseñas no coinciden.";
            } else {
                // Verificar si el usuario ya existe
                if ($this->usuarioModel->existeUsuario($nombre)) {
                    $mensajeError = "El nombre de usuario ya existe.";
                } else {
                    // Todo OK - Registrar usuario y redirigir a RegistroController
                    $_SESSION['registro_usuario'] = [
                        'nombre' => $nombre,
                        'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT),
                        'tiposusuarioid' => 1 // Por defecto Alumno
                    ];

                    header("Location: " . BASE_URL . "?page=completar-registro");
                    exit();
                }
            }
        }

        require_once VIEWS_PATH . 'auth/registro_paso3.php';
    }

    /**
     * Procesa el registro de un nuevo usuario (MÉTODO ANTIGUO - DEPRECATED)
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
