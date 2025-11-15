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
        $query = "SELECT COUNT(*) as total FROM dbo.Usuarios WHERE nombre = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($nombre));
        
        if ($stmt === false) {
            return false;
        }
        
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $row && $row['total'] > 0;
    }

    /**
     * Verifica si un email ya existe en InformacionPersonal
     * @param string $email Email
     * @return bool True si existe, false si no
     */
    public function existeEmail($email) {
        if (empty($email)) {
            return false;
        }
        
        // Buscar en la tabla InformacionPersonal en lugar de Usuarios
        $query = "SELECT COUNT(*) as total FROM dbo.InformacionPersonal WHERE email = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($email));
        
        if ($stmt === false) {
            return false;
        }
        
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $row && $row['total'] > 0;
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
        $query = "SELECT u.*, t.tipo as tipo_usuario, ip.email 
                  FROM dbo.Usuarios u 
                  INNER JOIN dbo.Tiposusuarios t ON u.tiposusuariosid = t.ID_tiposusuarios 
                  LEFT JOIN dbo.InformacionPersonal ip ON u.ID_usuarios = ip.usuariosid
                  WHERE u.ID_usuarios = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($userId));
        
        if ($stmt !== false) {
            return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Obtiene todos los usuarios del sistema
     * @return array Lista de usuarios
     */
    public function obtenerTodos() {
        $query = "SELECT u.*, t.tipo as tipo_usuario, ip.email 
                  FROM dbo.Usuarios u 
                  INNER JOIN dbo.Tiposusuarios t ON u.tiposusuariosid = t.ID_tiposusuarios 
                  LEFT JOIN dbo.InformacionPersonal ip ON u.ID_usuarios = ip.usuariosid
                  ORDER BY u.nombre";
        $stmt = sqlsrv_query($this->conn, $query);
        
        $usuarios = [];
        if ($stmt !== false) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }

    /**
     * Obtiene todos los tipos de usuario
     * @return array Lista de tipos de usuario
     */
    public function obtenerTiposUsuario() {
        $query = "SELECT * FROM dbo.Tiposusuarios ORDER BY tipo";
        $stmt = sqlsrv_query($this->conn, $query);
        
        $tipos = [];
        if ($stmt !== false) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $tipos[] = $row;
            }
        }
        return $tipos;
    }

    /**
     * Actualiza un usuario existente
     * @param int $id ID del usuario
     * @param array $datos Datos a actualizar
     * @return bool True si se actualizó correctamente
     */
    public function actualizar($id, $datos) {
        $email = isset($datos['email']) ? $datos['email'] : null;
        
        // 1. Actualizar datos del usuario (sin email)
        if (!empty($datos['contrasena'])) {
            // Si se proporciona nueva contraseña
            $query = "UPDATE dbo.Usuarios SET nombre = ?, contrasenas = ?, tiposusuariosid = ? WHERE ID_usuarios = ?";
            $params = array(
                $datos['nombre'],
                password_hash($datos['contrasena'], PASSWORD_DEFAULT),
                $datos['tiposusuarioid'],
                $id
            );
        } else {
            // Sin cambiar contraseña
            $query = "UPDATE dbo.Usuarios SET nombre = ?, tiposusuariosid = ? WHERE ID_usuarios = ?";
            $params = array(
                $datos['nombre'],
                $datos['tiposusuarioid'],
                $id
            );
        }
        
        $stmt = sqlsrv_query($this->conn, $query, $params);
        
        // 2. Actualizar o insertar email en InformacionPersonal
        if ($stmt !== false && !empty($email)) {
            // Verificar si ya existe registro en InformacionPersonal
            $queryCheck = "SELECT COUNT(*) as total FROM dbo.InformacionPersonal WHERE usuariosid = ?";
            $stmtCheck = sqlsrv_query($this->conn, $queryCheck, array($id));
            $rowCheck = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);
            
            if ($rowCheck && $rowCheck['total'] > 0) {
                // UPDATE
                $queryEmail = "UPDATE dbo.InformacionPersonal SET email = ? WHERE usuariosid = ?";
                sqlsrv_query($this->conn, $queryEmail, array($email, $id));
            } else {
                // INSERT
                $queryEmail = "INSERT INTO dbo.InformacionPersonal (usuariosid, email) VALUES (?, ?)";
                sqlsrv_query($this->conn, $queryEmail, array($id, $email));
            }
        }
        
        return $stmt !== false;
    }

    /**
     * Elimina un usuario
     * @param int $id ID del usuario
     * @return bool True si se eliminó correctamente
     */
    public function eliminar($id) {
        $query = "DELETE FROM dbo.Usuarios WHERE ID_usuarios = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($id));
        return $stmt !== false;
    }

    /**
     * Crea un nuevo usuario (para uso del administrador)
     * @param array $datos Datos del usuario
     * @return bool True si se creó correctamente
     */
    public function crear($datos) {
        $email = isset($datos['email']) ? $datos['email'] : null;
        
        // 1. Insertar usuario (sin email)
        $query = "INSERT INTO dbo.Usuarios (nombre, contrasenas, tiposusuariosid) VALUES (?, ?, ?)";
        $params = array(
            $datos['nombre'],
            password_hash($datos['contrasena'], PASSWORD_DEFAULT),
            $datos['tiposusuarioid']
        );
        
        $stmt = sqlsrv_query($this->conn, $query, $params);
        
        if ($stmt !== false && !empty($email)) {
            // 2. Obtener el ID del usuario recién creado
            $queryId = "SELECT TOP 1 ID_usuarios FROM dbo.Usuarios WHERE nombre = ? ORDER BY ID_usuarios DESC";
            $stmtId = sqlsrv_query($this->conn, $queryId, array($datos['nombre']));
            
            if ($stmtId && $row = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC)) {
                $usuarioId = $row['ID_usuarios'];
                
                // 3. Insertar email en InformacionPersonal
                $queryInfo = "INSERT INTO dbo.InformacionPersonal (usuariosid, email) VALUES (?, ?)";
                sqlsrv_query($this->conn, $queryInfo, array($usuarioId, $email));
            }
        }
        
        return $stmt !== false;
    }
}
?>
