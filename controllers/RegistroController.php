<?php
/**
 * Controlador de Registro Completo
 * Maneja el proceso de registro de nuevos usuarios incluyendo información personal y académica
 */
class RegistroController {
    private $usuarioModel;
    private $db;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->db = new Database();
    }

    /**
     * Completa el registro insertando usuario e información personal
     */
    public function completarRegistro() {
        // Verificar que existan todos los datos de sesión
        if (!isset($_SESSION['registro_usuario']) || !isset($_SESSION['registro_paso1']) || !isset($_SESSION['registro_paso2'])) {
            header("Location: " . BASE_URL . "?page=registrarse");
            exit();
        }

        // 1. Insertar el usuario en la tabla Usuarios
        $datosUsuario = $_SESSION['registro_usuario'];
        
        if ($this->usuarioModel->registrar($datosUsuario)) {
            // 2. Obtener el ID del usuario recién creado
            $conn = $this->db->getConnection();
            $query = "SELECT TOP 1 ID_usuarios FROM dbo.Usuarios WHERE nombre = ? ORDER BY ID_usuarios DESC";
            $stmt = sqlsrv_query($conn, $query, array($datosUsuario['nombre']));
            
            if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $usuarioId = $row['ID_usuarios'];
                
                // 3. Insertar información personal
                $paso1 = $_SESSION['registro_paso1'];
                $paso2 = $_SESSION['registro_paso2'];
                
                $queryInfo = "INSERT INTO dbo.InformacionPersonal 
                              (usuariosid, nombres, primerapellido, segundoapellido, fecha_nacimiento, telefono, email, RFC) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                
                $paramsInfo = array(
                    $usuarioId,
                    $paso1['nombres'],
                    $paso1['primerapellido'],
                    $paso1['segundoapellido'],
                    $paso1['fecha_nacimiento'],
                    $paso2['telefono'],
                    $paso2['email'],
                    $paso2['RFC']
                );

                $stmtInfo = sqlsrv_query($conn, $queryInfo, $paramsInfo);

                if ($stmtInfo === false) {
                    die("Error al guardar información personal: " . print_r(sqlsrv_errors(), true));
                }

                // 4. Limpiar sesiones de registro
                unset($_SESSION['registro_usuario']);
                unset($_SESSION['registro_paso1']);
                unset($_SESSION['registro_paso2']);

                // 5. Si es alumno, redirigir a información académica
                if ($datosUsuario['tiposusuarioid'] == 1) {
                    $_SESSION['informacion_personal'] = ['usuariosid' => $usuarioId];
                    header("Location: " . BASE_URL . "?page=informacion-academica");
                } else {
                    // Login automático para otros tipos
                    $this->loginAutomatico($usuarioId);
                }
                exit();
            } else {
                die("Error al obtener el ID del usuario: " . print_r(sqlsrv_errors(), true));
            }
        } else {
            die("Error al registrar usuario: " . print_r(sqlsrv_errors(), true));
        }
    }

    /**
     * Muestra el formulario de información personal (MÉTODO ANTIGUO - DEPRECATED)
     */
    public function informacionPersonal() {
        // Verificar que exista la sesión de registro
        if (!isset($_SESSION['registro_usuario'])) {
            header("Location: " . BASE_URL . "?page=registrarse");
            exit();
        }

        $mensajeError = "";
        require_once __DIR__ . '/../Informacionpersonal.php';
    }

    /**
     * Procesa el formulario de información personal
     */
    public function guardarInformacionPersonal() {
        // Verificar que exista la sesión de registro
        if (!isset($_SESSION['registro_usuario'])) {
            header("Location: " . BASE_URL . "?page=registrarse");
            exit();
        }

        $mensajeError = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recoger los datos del formulario
            $nombres = isset($_POST['nombres']) ? htmlspecialchars($_POST['nombres']) : '';
            $primerapellido = isset($_POST['primerapellido']) ? htmlspecialchars($_POST['primerapellido']) : '';
            $segundoapellido = isset($_POST['segundoapellido']) ? htmlspecialchars($_POST['segundoapellido']) : '';
            $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
            $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
            $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
            $RFC = isset($_POST['RFC']) ? $_POST['RFC'] : null;

            // Validar la edad
            if (!empty($fecha_nacimiento)) {
                $fechaNacimiento = new DateTime($fecha_nacimiento);
                $fechaActual = new DateTime();
                $edad = $fechaActual->diff($fechaNacimiento)->y;
                
                if ($edad < 18) {
                    $mensajeError = "Debes tener al menos 18 años para registrarte.";
                }
            } else {
                $mensajeError = "Por favor, ingrese su fecha de nacimiento.";
            }

            // Validar otros campos si la edad es adecuada
            if (empty($mensajeError)) {
                if (empty($nombres) || empty($primerapellido) || empty($telefono) || empty($email)) {
                    $mensajeError = "Por favor, complete todos los campos obligatorios.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mensajeError = "Ingrese una dirección de correo electrónico válida.";
                } 
            }

            // Si hay errores, mostrar el formulario de nuevo
            if (!empty($mensajeError)) {
                require_once __DIR__ . '/../Informacionpersonal.php';
                return;
            }

            // 1. Primero insertar el usuario en la tabla Usuarios
            $datosUsuario = $_SESSION['registro_usuario'];
            
            if ($this->usuarioModel->registrar($datosUsuario)) {
                // Obtener el ID del usuario recién creado
                $conn = $this->db->getConnection();
                $query = "SELECT TOP 1 ID_usuarios FROM dbo.Usuarios WHERE nombre = ? ORDER BY ID_usuarios DESC";
                $stmt = sqlsrv_query($conn, $query, array($datosUsuario['nombre']));
                
                if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $usuarioId = $row['ID_usuarios'];
                    
                    // 2. Guardar información personal en sesión temporal con el ID del usuario
                    $_SESSION['informacion_personal'] = [
                        'usuariosid' => $usuarioId,
                        'nombres' => $nombres,
                        'primerapellido' => $primerapellido,
                        'segundoapellido' => $segundoapellido,
                        'fecha_nacimiento' => $fecha_nacimiento,
                        'telefono' => $telefono,
                        'email' => $email,
                        'RFC' => $RFC
                    ];

                    // Limpiar sesión de registro
                    unset($_SESSION['registro_usuario']);

                    // 3. Redirigir según el tipo de usuario
                    if ($datosUsuario['tiposusuarioid'] == 1) { // Alumno
                        header("Location: " . BASE_URL . "?page=confirmar-informacion");
                    } else { // Otros tipos (Maestro, Administrativo, etc.)
                        // Insertar información personal directamente
                        $this->insertarInformacionPersonal();
                        
                        // Login automático
                        $this->loginAutomatico($usuarioId);
                    }
                    exit();
                } else {
                    die("Error al obtener el ID del usuario: " . print_r(sqlsrv_errors(), true));
                }
            } else {
                die("Error al registrar usuario: " . print_r(sqlsrv_errors(), true));
            }
        }
    }

    /**
     * Confirma e inserta la información personal
     */
    public function confirmarInformacion() {
        if (!isset($_SESSION['informacion_personal'])) {
            header("Location: " . BASE_URL . "?page=registrarse");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->insertarInformacionPersonal();
            $usuarioId = $_SESSION['informacion_personal']['usuariosid'];
            
            // Redirigir a información académica
            header("Location: " . BASE_URL . "?page=informacion-academica");
            exit();
        }

        require_once __DIR__ . '/../ConfirmarInformacion.php';
    }

    /**
     * Muestra el formulario de información académica (solo para alumnos)
     */
    public function informacionAcademica() {
        if (!isset($_SESSION['informacion_personal'])) {
            header("Location: " . BASE_URL . "?page=registrarse");
            exit();
        }

        // Obtener las carreras disponibles
        $conn = $this->db->getConnection();
        $query = "SELECT ID_carrera, nombre FROM dbo.Carrera ORDER BY nombre";
        $stmt = sqlsrv_query($conn, $query);
        
        $carreras = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $carreras[] = $row;
            }
        } else {
            // Debug: Si hay error en la consulta
            error_log("Error obteniendo carreras: " . print_r(sqlsrv_errors(), true));
        }

        // Debug: Ver cuántas carreras se obtuvieron
        error_log("Total carreras obtenidas: " . count($carreras));

        $mensajeError = "";
        require_once VIEWS_PATH . 'auth/informacion_academica.php';
    }

    /**
     * Procesa el formulario de información académica
     */
    public function guardarInformacionAcademica() {
        if (!isset($_SESSION['informacion_personal'])) {
            header("Location: " . BASE_URL . "?page=registrarse");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $conn = $this->db->getConnection();
            $usuarioId = $_SESSION['informacion_personal']['usuariosid'];

            // Insertar información académica
            $query = "INSERT INTO dbo.InformacionAcademica_estudiante 
                      (usuariosid, carreraid, semestre, grupo, turno) 
                      VALUES (?, ?, ?, ?, ?)";
            
            $params = array(
                $usuarioId,
                $_POST['carrera'],
                $_POST['semestre'],
                htmlspecialchars($_POST['grupo']),
                $_POST['turno']
            );

            $stmt = sqlsrv_query($conn, $query, $params);

            if ($stmt === false) {
                die("Error al guardar información académica: " . print_r(sqlsrv_errors(), true));
            }

            // Limpiar sesión
            unset($_SESSION['informacion_personal']);

            // Login automático
            $this->loginAutomatico($usuarioId);
        }
    }

    /**
     * Inserta la información personal en la base de datos
     */
    private function insertarInformacionPersonal() {
        $conn = $this->db->getConnection();
        $info = $_SESSION['informacion_personal'];

        $query = "INSERT INTO dbo.InformacionPersonal 
                  (usuariosid, nombres, primerapellido, segundoapellido, fecha_nacimiento, telefono, email, RFC) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = array(
            $info['usuariosid'],
            $info['nombres'],
            $info['primerapellido'],
            $info['segundoapellido'],
            $info['fecha_nacimiento'],
            $info['telefono'],
            $info['email'],
            $info['RFC']
        );

        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            die("Error al guardar información personal: " . print_r(sqlsrv_errors(), true));
        }
    }

    /**
     * Realiza login automático después del registro
     */
    private function loginAutomatico($usuarioId) {
        $usuario = $this->usuarioModel->obtenerPorId($usuarioId);
        
        if ($usuario) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $usuario['ID_usuarios'];
            $_SESSION['user_name'] = $usuario['nombre'];
            $_SESSION['user_type_id'] = $usuario['tiposusuariosid'];
            
            $tipo = $this->usuarioModel->getTipoUsuario($usuario['tiposusuariosid']);
            $_SESSION['user_type'] = $tipo;

            // Limpiar cualquier sesión de registro pendiente
            unset($_SESSION['informacion_personal']);

            // Redirigir según tipo
            if (strcasecmp($tipo, 'Admin') === 0 || strcasecmp($tipo, 'Admi') === 0) {
                header("Location: " . BASE_URL . "?page=admin");
            } else {
                header("Location: " . BASE_URL . "?page=academicas");
            }
            exit();
        }
    }
}
?>
