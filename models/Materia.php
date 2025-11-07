<?php
/**
 * Modelo Materia
 * Maneja todas las operaciones relacionadas con materias
 */
class Materia {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Obtiene todas las materias
     * @return array Lista de materias
     */
    public function obtenerTodas() {
        $query = "SELECT * FROM Materia";
        $stmt = sqlsrv_query($this->conn, $query);
        $materias = [];
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $materias[] = $row;
        }
        
        return $materias;
    }

    /**
     * Obtiene materias por carrera
     * @param int $carreraId ID de la carrera
     * @return array Lista de materias
     */
    public function obtenerPorCarrera($carreraId) {
        $query = "SELECT m.ID_materia, m.nombre 
                  FROM Materia m 
                  INNER JOIN carrera_materia cm ON m.ID_materia = cm.materiaid 
                  WHERE cm.carreraid = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($carreraId));
        $materias = [];
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $materias[$row['ID_materia']] = $row['nombre'];
        }
        
        return $materias;
    }

    /**
     * Obtiene una materia por su ID
     * @param int $id ID de la materia
     * @return array|false Datos de la materia
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM Materia WHERE ID_materia = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($id));
        
        if ($stmt !== false) {
            return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Crea una nueva materia
     * @param array $datos Datos de la materia
     * @return int|false ID de la materia creada
     */
    public function crear($datos) {
        sqlsrv_begin_transaction($this->conn);
        
        try {
            // Insertar materia
            $query = "INSERT INTO Materia (nombre, periodoId, carreraId) VALUES (?, ?, ?); SELECT SCOPE_IDENTITY() as ID_materia;";
            $params = array($datos['nombre'], $datos['periodoId'], $datos['carreraId']);
            $stmt = sqlsrv_query($this->conn, $query, $params);
            
            if ($stmt === false) {
                throw new Exception("Error al crear materia");
            }
            
            sqlsrv_next_result($stmt);
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            $materiaId = $row['ID_materia'];
            
            // Insertar relación carrera-materia
            $queryRelacion = "INSERT INTO carrera_materia (carreraid, materiaid, es_reticula) VALUES (?, ?, ?)";
            $paramsRelacion = array($datos['carreraId'], $materiaId, $datos['es_reticula']);
            $stmtRelacion = sqlsrv_query($this->conn, $queryRelacion, $paramsRelacion);
            
            if ($stmtRelacion === false) {
                throw new Exception("Error al crear relación carrera-materia");
            }
            
            sqlsrv_commit($this->conn);
            return $materiaId;
        } catch (Exception $e) {
            sqlsrv_rollback($this->conn);
            return false;
        }
    }

    /**
     * Actualiza una materia
     * @param int $id ID de la materia
     * @param array $datos Nuevos datos
     * @return bool True si se actualizó correctamente
     */
    public function actualizar($id, $datos) {
        sqlsrv_begin_transaction($this->conn);
        
        try {
            $query = "UPDATE Materia SET periodoId = ?, carreraId = ?, nombre = ? WHERE ID_materia = ?";
            $params = array($datos['periodoId'], $datos['carreraId'], $datos['nombre'], $id);
            $stmt = sqlsrv_query($this->conn, $query, $params);
            
            if ($stmt === false) {
                throw new Exception("Error al actualizar materia");
            }
            
            $queryRelacion = "UPDATE carrera_materia SET carreraid = ? WHERE materiaid = ?";
            $paramsRelacion = array($datos['carreraId'], $id);
            $stmtRelacion = sqlsrv_query($this->conn, $queryRelacion, $paramsRelacion);
            
            if ($stmtRelacion === false) {
                throw new Exception("Error al actualizar relación");
            }
            
            sqlsrv_commit($this->conn);
            return true;
        } catch (Exception $e) {
            sqlsrv_rollback($this->conn);
            return false;
        }
    }

    /**
     * Elimina una materia
     * @param int $id ID de la materia
     * @return bool True si se eliminó correctamente
     */
    public function eliminar($id) {
        sqlsrv_begin_transaction($this->conn);
        
        try {
            // Eliminar actividades relacionadas
            $queryActividades = "DELETE FROM ActividadesAcademicas WHERE materiaID = ?";
            sqlsrv_query($this->conn, $queryActividades, array($id));
            
            // Eliminar relación carrera-materia
            $queryRelacion = "DELETE FROM carrera_materia WHERE materiaid = ?";
            sqlsrv_query($this->conn, $queryRelacion, array($id));
            
            // Eliminar materia
            $query = "DELETE FROM Materia WHERE ID_materia = ?";
            $stmt = sqlsrv_query($this->conn, $query, array($id));
            
            if ($stmt === false) {
                throw new Exception("Error al eliminar materia");
            }
            
            sqlsrv_commit($this->conn);
            return true;
        } catch (Exception $e) {
            sqlsrv_rollback($this->conn);
            return false;
        }
    }

    /**
     * Obtiene materias indexadas por ID
     * @return array Materias indexadas por ID
     */
    public function obtenerIndexadasPorId() {
        $materias = $this->obtenerTodas();
        $materiasIndexadas = [];
        
        foreach ($materias as $materia) {
            $materiasIndexadas[$materia['ID_materia']] = $materia['nombre'];
        }
        
        return $materiasIndexadas;
    }
}
?>
