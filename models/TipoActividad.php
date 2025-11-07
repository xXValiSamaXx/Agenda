<?php
/**
 * Modelo TipoActividad
 * Maneja todas las operaciones relacionadas con tipos de actividades
 */
class TipoActividad {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Obtiene todos los tipos de actividades
     * @return array Lista de tipos de actividades
     */
    public function obtenerTodos() {
        $query = "SELECT * FROM Tipos_Actividades";
        $stmt = sqlsrv_query($this->conn, $query);
        $tipos = [];
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $tipos[] = $row;
        }
        
        return $tipos;
    }

    /**
     * Obtiene tipos indexados por ID
     * @return array Tipos indexados por ID
     */
    public function obtenerIndexadosPorId() {
        $tipos = $this->obtenerTodos();
        $tiposIndexados = [];
        
        foreach ($tipos as $tipo) {
            $tiposIndexados[$tipo['ID_tiposactividades']] = $tipo['nombre'];
        }
        
        return $tiposIndexados;
    }

    /**
     * Obtiene un tipo de actividad por su ID
     * @param int $id ID del tipo de actividad
     * @return array|false Datos del tipo de actividad
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM Tipos_Actividades WHERE ID_tiposactividades = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($id));
        
        if ($stmt !== false) {
            return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        }
        return false;
    }
}
?>
