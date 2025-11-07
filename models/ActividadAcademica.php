<?php
/**
 * Modelo ActividadAcademica
 * Maneja todas las operaciones relacionadas con actividades académicas
 */
class ActividadAcademica {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Crea una nueva actividad académica
     * @param array $datos Datos de la actividad
     * @return bool True si se creó correctamente
     */
    public function crear($datos) {
        $query = "INSERT INTO ActividadesAcademicas (usuariosID, materiaID, tiposactividadesID, descripcion, fecha) 
                  VALUES (?, ?, ?, ?, ?)";
        $params = array(
            $datos['usuariosID'],
            $datos['materiaID'],
            $datos['tiposactividadesID'],
            $datos['descripcion'],
            $datos['fecha']
        );
        
        $stmt = sqlsrv_prepare($this->conn, $query, $params);
        
        if ($stmt === false) {
            return false;
        }
        
        return sqlsrv_execute($stmt);
    }

    /**
     * Obtiene actividades de un usuario
     * @param int $userId ID del usuario
     * @return array Lista de actividades
     */
    public function obtenerPorUsuario($userId) {
        $query = "SELECT * FROM ActividadesAcademicas WHERE usuariosID = ?";
        $params = array($userId);
        $stmt = sqlsrv_prepare($this->conn, $query, $params);
        
        if ($stmt === false) {
            return [];
        }
        
        sqlsrv_execute($stmt);
        $actividades = [];
        
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $actividades[] = $row;
        }
        
        return $actividades;
    }

    /**
     * Obtiene una actividad por su ID
     * @param int $id ID de la actividad
     * @return array|false Datos de la actividad
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM ActividadesAcademicas WHERE ID_actividadesacademicas = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($id));
        
        if ($stmt !== false) {
            return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Actualiza una actividad
     * @param int $id ID de la actividad
     * @param array $datos Nuevos datos
     * @return bool True si se actualizó correctamente
     */
    public function actualizar($id, $datos) {
        $query = "UPDATE ActividadesAcademicas 
                  SET materiaID = ?, tiposactividadesID = ?, descripcion = ?, fecha = ? 
                  WHERE ID_actividadesacademicas = ?";
        $params = array(
            $datos['materiaID'],
            $datos['tiposactividadesID'],
            $datos['descripcion'],
            $datos['fecha'],
            $id
        );
        
        $stmt = sqlsrv_query($this->conn, $query, $params);
        return $stmt !== false;
    }

    /**
     * Elimina una actividad
     * @param int $id ID de la actividad
     * @return bool True si se eliminó correctamente
     */
    public function eliminar($id) {
        $query = "DELETE FROM ActividadesAcademicas WHERE ID_actividadesacademicas = ?";
        $stmt = sqlsrv_query($this->conn, $query, array($id));
        return $stmt !== false;
    }

    /**
     * Agrupa actividades por periodo de tiempo
     * @param array $actividades Lista de actividades
     * @param int $inicio Timestamp de inicio
     * @param int $fin Timestamp de fin
     * @return array Contador y actividades filtradas
     */
    public function agruparPorPeriodo($actividades, $inicio, $fin) {
        $actividadesFiltradas = array_filter($actividades, function($actividad) use ($inicio, $fin) {
            $fecha = strtotime($actividad['fecha']->format('Y-m-d H:i:s'));
            return $fecha >= $inicio && $fecha <= $fin;
        });

        return [
            'contador' => count($actividadesFiltradas),
            'actividades' => $actividadesFiltradas
        ];
    }

    /**
     * Obtiene actividades agrupadas por periodos
     * @param int $userId ID del usuario
     * @return array Actividades agrupadas
     */
    public function obtenerPorPeriodos($userId) {
        $actividades = $this->obtenerPorUsuario($userId);
        
        $inicioEstaSemana = strtotime('monday this week');
        $finEstaSemana = strtotime('sunday this week') + 86399;
        $actividadesEstaSemana = $this->agruparPorPeriodo($actividades, $inicioEstaSemana, $finEstaSemana);

        $inicioProximaSemana = strtotime('monday next week');
        $finProximaSemana = strtotime('sunday next week') + 86399;
        $actividadesProximaSemana = $this->agruparPorPeriodo($actividades, $inicioProximaSemana, $finProximaSemana);

        $inicioProximoMes = strtotime('first day of next month');
        $finProximoMes = strtotime('last day of next month') + 86399;
        $actividadesProximoMes = $this->agruparPorPeriodo($actividades, $inicioProximoMes, $finProximoMes);

        return [
            'Para esta semana' => $actividadesEstaSemana,
            'Para la próxima semana' => $actividadesProximaSemana,
            'Para el próximo mes' => $actividadesProximoMes
        ];
    }
}
?>
