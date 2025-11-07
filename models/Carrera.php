<?php
/**
 * Modelo Carrera
 * Maneja todas las operaciones relacionadas con carreras
 */
class Carrera {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Obtiene todas las carreras
     * @return array Lista de carreras
     */
    public function obtenerTodas() {
        $query = "SELECT * FROM Carrera";
        $stmt = sqlsrv_query($this->conn, $query);
        $carreras = [];
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $carreras[] = $row;
        }
        
        return $carreras;
    }

    /**
     * Obtiene una carrera por su ID
     * @param int $id ID de la carrera
     * @return array|false Datos de la carrera
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM Carrera WHERE ID_carrera = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($id));
        
        if ($stmt !== false) {
            return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Obtiene la carrera de un estudiante
     * @param int $usuarioId ID del usuario
     * @return int|false ID de la carrera
     */
    public function obtenerCarreraEstudiante($usuarioId) {
        $query = "SELECT carreraid FROM InformacionAcademica_estudiante WHERE usuariosid = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($usuarioId));
        
        if ($stmt !== false && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            return $row['carreraid'];
        }
        return false;
    }
}
?>
