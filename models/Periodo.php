<?php
/**
 * Modelo Periodo
 * Maneja todas las operaciones relacionadas con periodos
 */
class Periodo {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Obtiene todos los periodos
     * @return array Lista de periodos
     */
    public function obtenerTodos() {
        $query = "SELECT * FROM Periodo";
        $stmt = sqlsrv_query($this->conn, $query);
        $periodos = [];
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $periodos[] = $row;
        }
        
        return $periodos;
    }

    /**
     * Obtiene un periodo por su ID
     * @param int $id ID del periodo
     * @return array|false Datos del periodo
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM Periodo WHERE ID_periodo = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($id));
        
        if ($stmt !== false) {
            return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        }
        return false;
    }
}
?>
