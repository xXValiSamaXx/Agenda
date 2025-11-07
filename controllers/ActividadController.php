<?php
/**
 * Controlador de Actividades Académicas
 * Maneja todas las operaciones de actividades académicas
 */
class ActividadController {
    private $actividadModel;
    private $materiaModel;
    private $tipoActividadModel;
    private $carreraModel;

    public function __construct() {
        $this->actividadModel = new ActividadAcademica();
        $this->materiaModel = new Materia();
        $this->tipoActividadModel = new TipoActividad();
        $this->carreraModel = new Carrera();
    }

    /**
     * Muestra las actividades académicas del usuario
     */
    public function index() {
        AuthController::verificarAutenticacion();
        $userId = $_SESSION['user_id'];

        // Procesar acciones POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->crear();
            return;
        }

        // Obtener datos necesarios
        $materias = $this->materiaModel->obtenerIndexadasPorId();
        $tiposActividades = $this->tipoActividadModel->obtenerIndexadosPorId();

        // Obtener materias de la carrera del estudiante
        $carreraId = $this->carreraModel->obtenerCarreraEstudiante($userId);
        $carrera_materias = [];
        if ($carreraId) {
            $carrera_materias = $this->materiaModel->obtenerPorCarrera($carreraId);
        }

        // Obtener actividades agrupadas por periodos
        $resultados = $this->actividadModel->obtenerPorPeriodos($userId);
        
        // Hacer disponible el userId para JavaScript
        echo "<script>var userId = " . json_encode($userId) . ";</script>";

        require_once VIEWS_PATH . 'actividades/index.php';
    }

    /**
     * Crea una nueva actividad
     */
    private function crear() {
        $userId = $_SESSION['user_id'];
        
        $datos = [
            'usuariosID' => $userId,
            'materiaID' => $_POST['materiaID'],
            'tiposactividadesID' => $_POST['tiposactividadesID'],
            'descripcion' => $_POST['descripcion'],
            'fecha' => $_POST['fecha']
        ];

        if ($this->actividadModel->crear($datos)) {
            header("Location: " . BASE_URL . "?page=academicas&success=1");
        } else {
            header("Location: " . BASE_URL . "?page=academicas&error=1");
        }
        exit();
    }

    /**
     * Muestra el formulario de edición de actividad
     */
    public function editar() {
        AuthController::verificarAutenticacion();
        
        if (isset($_POST['id_actividad'])) {
            $id = $_POST['id_actividad'];
            $actividad = $this->actividadModel->obtenerPorId($id);
            
            if ($actividad) {
                $materias = $this->materiaModel->obtenerTodas();
                $tiposActividades = $this->tipoActividadModel->obtenerTodos();
                require_once VIEWS_PATH . 'actividades/editar.php';
                return;
            }
        }
        
        header("Location: " . BASE_URL . "?page=academicas");
        exit();
    }

    /**
     * Actualiza una actividad
     */
    public function actualizar() {
        AuthController::verificarAutenticacion();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_actividad'])) {
            $id = $_POST['id_actividad'];
            $datos = [
                'materiaID' => $_POST['materiaID'],
                'tiposactividadesID' => $_POST['tiposactividadesID'],
                'descripcion' => $_POST['descripcion'],
                'fecha' => $_POST['fecha']
            ];

            if ($this->actividadModel->actualizar($id, $datos)) {
                header("Location: " . BASE_URL . "?page=academicas&success=update");
            } else {
                header("Location: " . BASE_URL . "?page=academicas&error=update");
            }
            exit();
        }
        
        header("Location: " . BASE_URL . "?page=academicas");
        exit();
    }

    /**
     * Elimina una actividad
     */
    public function eliminar() {
        AuthController::verificarAutenticacion();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_actividad'])) {
            $id = $_POST['id_actividad'];
            
            if ($this->actividadModel->eliminar($id)) {
                header("Location: " . BASE_URL . "?page=academicas&success=delete");
            } else {
                header("Location: " . BASE_URL . "?page=academicas&error=delete");
            }
            exit();
        }
        
        header("Location: " . BASE_URL . "?page=academicas");
        exit();
    }
}
?>
