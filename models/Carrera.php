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

    /**
     * Crea una nueva carrera
     * @param array $datos Datos de la carrera (nombre, perfil_carrera, duracion, descripcion)
     * @return bool True si se creó correctamente
     */
    public function crear($datos) {
        $query = "INSERT INTO Carrera (nombre, perfil_carrera, duracion, descripcion) VALUES (?, ?, ?, ?)";
        $params = array($datos['nombre'], $datos['perfil_carrera'], $datos['duracion'], $datos['descripcion']);
        $stmt = sqlsrv_query($this->conn, $query, $params);
        return $stmt !== false;
    }

    /**
     * Actualiza una carrera existente
     * @param int $id ID de la carrera
     * @param array $datos Datos a actualizar
     * @return bool True si se actualizó correctamente
     */
    public function actualizar($id, $datos) {
        $query = "UPDATE Carrera SET nombre = ?, perfil_carrera = ?, duracion = ?, descripcion = ? WHERE ID_carrera = ?";
        $params = array($datos['nombre'], $datos['perfil_carrera'], $datos['duracion'], $datos['descripcion'], $id);
        $stmt = sqlsrv_query($this->conn, $query, $params);
        return $stmt !== false;
    }

    /**
     * Elimina una carrera
     * @param int $id ID de la carrera
     * @return bool True si se eliminó correctamente
     */
    public function eliminar($id) {
        // Iniciar transacción
        sqlsrv_begin_transaction($this->conn);

        // Actualizar los registros de estudiantes que tienen esta carrera asociada
        $queryUpdateEstudiantes = "UPDATE InformacionAcademica_estudiante SET carreraid = NULL WHERE carreraid = ?";
        $stmtUpdateEstudiantes = sqlsrv_query($this->conn, $queryUpdateEstudiantes, array($id));

        if ($stmtUpdateEstudiantes === false) {
            sqlsrv_rollback($this->conn);
            return false;
        }

        // Eliminar la carrera
        $queryDeleteCarrera = "DELETE FROM Carrera WHERE ID_carrera = ?";
        $stmtDeleteCarrera = sqlsrv_query($this->conn, $queryDeleteCarrera, array($id));

        if ($stmtDeleteCarrera === false) {
            sqlsrv_rollback($this->conn);
            return false;
        }

        sqlsrv_commit($this->conn);
        return true;
    }

    /**
     * Verifica si una carrera está en uso
     * @param int $id ID de la carrera
     * @return bool True si está en uso
     */
    public function estaEnUso($id) {
        $query = "SELECT COUNT(*) as total FROM InformacionAcademica_estudiante WHERE carreraid = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($id));
        
        if ($stmt !== false && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            return $row['total'] > 0;
        }
        return false;
    }
}
?>
