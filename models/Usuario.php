<?php
/**
 * Modelo Usuario
 * Maneja todas las operaciones relacionadas con usuarios
 */
class Usuario {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Valida las credenciales del usuario
     * @param string $nombre Usuario
     * @param string $contrasena Contraseña
     * @return array|false Datos del usuario o false
     */
    public function login($nombre, $contrasena) {
        $query = "SELECT TOP 1 * FROM dbo.Usuarios WHERE nombre = ?";
        $params = array($nombre);
        $stmt = sqlsrv_query($this->conn, $query, $params);

        if ($stmt !== false && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (password_verify($contrasena, $row['contrasenas'])) {
                return $row;
            }
        }
        return false;
    }

    /**
     * Obtiene el tipo de usuario
     * @param int $tiposusuariosid ID del tipo de usuario
     * @return string|false Tipo de usuario
     */
    public function getTipoUsuario($tiposusuariosid) {
        $query = "SELECT tipo FROM dbo.Tiposusuarios WHERE ID_tiposusuarios = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($tiposusuariosid));
        
        if (sqlsrv_fetch($stmt)) {
            return sqlsrv_get_field($stmt, 0);
        }
        return false;
    }

    /**
     * Verifica si un usuario ya existe
     * @param string $nombre Usuario
     * @return bool True si existe, false si no
     */
    public function existeUsuario($nombre) {
        $query = "SELECT * FROM dbo.Usuarios WHERE nombre = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($nombre));
        return sqlsrv_fetch($stmt) !== false;
    }

    /**
     * Registra un nuevo usuario
     * @param array $datosUsuario Datos del usuario a registrar
     * @return bool True si se registró correctamente
     */
    public function registrar($datosUsuario) {
        $query = "INSERT INTO dbo.Usuarios (nombre, contrasenas, tiposusuariosid) VALUES (?, ?, ?)";
        $params = array(
            $datosUsuario['nombre'],
            $datosUsuario['contrasena'],
            $datosUsuario['tiposusuarioid']
        );
        
        $stmt = sqlsrv_query($this->conn, $query, $params);
        return $stmt !== false;
    }

    /**
     * Obtiene un usuario por su ID
     * @param int $userId ID del usuario
     * @return array|false Datos del usuario
     */
    public function obtenerPorId($userId) {
        $query = "SELECT * FROM dbo.Usuarios WHERE ID_usuarios = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($userId));
        
        if ($stmt !== false) {
            return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        }
        return false;
    }
}
?>
