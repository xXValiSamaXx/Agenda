<?php
/**
 * Controlador de Perfil
 * Maneja la completación de información personal y de contacto
 */
class PerfilController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Verifica si el usuario tiene su perfil completo
     * @param int $usuarioId
     * @return array ['personal' => bool, 'contacto' => bool]
     */
    public function verificarPerfilCompleto($usuarioId) {
        $conn = $this->db->getConnection();
        
        // Verificar InformacionPersonal
        $queryPersonal = "SELECT COUNT(*) as total FROM dbo.InformacionPersonal 
                          WHERE usuariosid = ? AND nombres IS NOT NULL AND telefono IS NOT NULL";
        $stmtPersonal = sqlsrv_query($conn, $queryPersonal, array($usuarioId));
        $rowPersonal = sqlsrv_fetch_array($stmtPersonal, SQLSRV_FETCH_ASSOC);
        
        // Verificar InformacionContacto
        $queryContacto = "SELECT COUNT(*) as total FROM dbo.InformacionContacto 
                          WHERE usuariosid = ? AND calle_principal IS NOT NULL";
        $stmtContacto = sqlsrv_query($conn, $queryContacto, array($usuarioId));
        $rowContacto = sqlsrv_fetch_array($stmtContacto, SQLSRV_FETCH_ASSOC);
        
        return [
            'personal' => ($rowPersonal && $rowPersonal['total'] > 0),
            'contacto' => ($rowContacto && $rowContacto['total'] > 0)
        ];
    }

    /**
     * Muestra el formulario de información personal
     */
    public function completarPersonal() {
        AuthController::verificarAutenticacion();
        
        $mensajeError = "";
        require_once VIEWS_PATH . 'perfil/completar_personal.php';
    }

    /**
     * Guarda la información personal
     */
    public function guardarPersonal() {
        AuthController::verificarAutenticacion();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuarioId = $_SESSION['user_id'];
            $conn = $this->db->getConnection();
            
            // Validaciones
            $nombres = trim($_POST['nombres']);
            $primerapellido = trim($_POST['primerapellido']);
            $segundoapellido = trim($_POST['segundoapellido'] ?? '');
            $fecha_nacimiento = $_POST['fecha_nacimiento'];
            $telefono = trim($_POST['telefono']);
            $email = trim($_POST['email']);
            $RFC = trim($_POST['RFC'] ?? '');
            
            if (empty($nombres) || empty($primerapellido) || empty($fecha_nacimiento) || empty($telefono) || empty($email)) {
                $_SESSION['mensaje_error'] = "Por favor, complete todos los campos obligatorios.";
                header("Location: " . BASE_URL . "?page=completar-perfil-personal");
                exit();
            }
            
            // Verificar si ya existe registro
            $queryCheck = "SELECT COUNT(*) as total FROM dbo.InformacionPersonal WHERE usuariosid = ?";
            $stmtCheck = sqlsrv_query($conn, $queryCheck, array($usuarioId));
            $rowCheck = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);
            
            if ($rowCheck && $rowCheck['total'] > 0) {
                // UPDATE
                $query = "UPDATE dbo.InformacionPersonal 
                          SET nombres = ?, primerapellido = ?, segundoapellido = ?, 
                              fecha_nacimiento = ?, telefono = ?, email = ?, RFC = ?
                          WHERE usuariosid = ?";
                $params = array($nombres, $primerapellido, $segundoapellido, $fecha_nacimiento, 
                                $telefono, $email, $RFC, $usuarioId);
            } else {
                // INSERT
                $query = "INSERT INTO dbo.InformacionPersonal 
                          (usuariosid, nombres, primerapellido, segundoapellido, fecha_nacimiento, telefono, email, RFC) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $params = array($usuarioId, $nombres, $primerapellido, $segundoapellido, 
                                $fecha_nacimiento, $telefono, $email, $RFC);
            }
            
            $stmt = sqlsrv_query($conn, $query, $params);
            
            if ($stmt === false) {
                $_SESSION['mensaje_error'] = "Error al guardar la información.";
                header("Location: " . BASE_URL . "?page=completar-perfil-personal");
            } else {
                // Redirigir a completar contacto
                header("Location: " . BASE_URL . "?page=completar-perfil-contacto");
            }
            exit();
        }
    }

    /**
     * Muestra el formulario de información de contacto
     */
    public function completarContacto() {
        AuthController::verificarAutenticacion();
        
        $mensajeError = "";
        require_once VIEWS_PATH . 'perfil/completar_contacto.php';
    }

    /**
     * Guarda la información de contacto
     */
    public function guardarContacto() {
        AuthController::verificarAutenticacion();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuarioId = $_SESSION['user_id'];
            $conn = $this->db->getConnection();
            
            $codigo_postal = trim($_POST['codigo_postal']);
            $municipio = trim($_POST['municipio']);
            $estado = trim($_POST['estado']);
            $ciudad = trim($_POST['ciudad']);
            $colonia = trim($_POST['colonia']);
            $calle_principal = trim($_POST['calle_principal']);
            $numero_exterior = trim($_POST['numero_exterior']);
            $numero_interior = trim($_POST['numero_interior'] ?? '');
            $primer_cruzamiento = trim($_POST['primer_cruzamiento'] ?? '');
            $segundo_cruzamiento = trim($_POST['segundo_cruzamiento'] ?? '');
            $referencias = trim($_POST['referencias'] ?? '');
            
            // Verificar si ya existe registro
            $queryCheck = "SELECT COUNT(*) as total FROM dbo.InformacionContacto WHERE usuariosid = ?";
            $stmtCheck = sqlsrv_query($conn, $queryCheck, array($usuarioId));
            $rowCheck = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);
            
            if ($rowCheck && $rowCheck['total'] > 0) {
                // UPDATE
                $query = "UPDATE dbo.InformacionContacto 
                          SET codigo_postal = ?, municipio = ?, estado = ?, ciudad = ?, colonia = ?,
                              calle_principal = ?, numero_exterior = ?, numero_interior = ?,
                              primer_cruzamiento = ?, segundo_cruzamiento = ?, referencias = ?
                          WHERE usuariosid = ?";
                $params = array($codigo_postal, $municipio, $estado, $ciudad, $colonia,
                                $calle_principal, $numero_exterior, $numero_interior,
                                $primer_cruzamiento, $segundo_cruzamiento, $referencias, $usuarioId);
            } else {
                // INSERT
                $query = "INSERT INTO dbo.InformacionContacto 
                          (usuariosid, codigo_postal, municipio, estado, ciudad, colonia,
                           calle_principal, numero_exterior, numero_interior,
                           primer_cruzamiento, segundo_cruzamiento, referencias) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $params = array($usuarioId, $codigo_postal, $municipio, $estado, $ciudad, $colonia,
                                $calle_principal, $numero_exterior, $numero_interior,
                                $primer_cruzamiento, $segundo_cruzamiento, $referencias);
            }
            
            $stmt = sqlsrv_query($conn, $query, $params);
            
            if ($stmt === false) {
                $_SESSION['mensaje_error'] = "Error al guardar la información.";
                header("Location: " . BASE_URL . "?page=completar-perfil-contacto");
            } else {
                // Perfil completado, redirigir al home
                $_SESSION['mensaje_exito'] = "¡Perfil completado exitosamente!";
                
                // Redirigir según tipo de usuario
                if (isset($_SESSION['user_type']) && 
                    (strcasecmp($_SESSION['user_type'], 'Admin') === 0 || strcasecmp($_SESSION['user_type'], 'Admi') === 0)) {
                    header("Location: " . BASE_URL . "?page=admin");
                } else {
                    header("Location: " . BASE_URL . "?page=academicas");
                }
            }
            exit();
        }
    }
}
?>
