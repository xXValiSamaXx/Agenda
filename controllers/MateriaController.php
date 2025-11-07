<?php
/**
 * Controlador de Materias
 * Maneja todas las operaciones CRUD de materias
 */
class MateriaController {
    private $materiaModel;
    private $periodoModel;
    private $carreraModel;

    public function __construct() {
        $this->materiaModel = new Materia();
        $this->periodoModel = new Periodo();
        $this->carreraModel = new Carrera();
    }

    /**
     * Muestra la lista de materias con funcionalidad CRUD
     */
    public function index() {
        AuthController::verificarAdmin();

        // Procesar acciones POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['add'])) {
                $this->agregar();
                return;
            } elseif (isset($_POST['update'])) {
                $this->actualizar();
                return;
            } elseif (isset($_POST['delete'])) {
                $this->eliminar();
                return;
            }
        }

        // Obtener datos para la vista
        $materias = $this->materiaModel->obtenerTodas();
        $periodos = $this->periodoModel->obtenerTodos();
        $carreras = $this->carreraModel->obtenerTodas();
        
        // Obtener relación carrera_materia
        $db = new Database();
        $conn = $db->getConnection();
        $query = "SELECT * FROM carrera_materia";
        $stmt = sqlsrv_query($conn, $query);
        $carrera_materia = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $carrera_materia[] = $row;
        }

        require_once VIEWS_PATH . 'materias/index.php';
    }

    /**
     * Agrega una nueva materia
     */
    private function agregar() {
        $datos = [
            'nombre' => $_POST['nombre'],
            'periodoId' => isset($_POST["periodoId"]) && $_POST["periodoId"] != '' ? $_POST["periodoId"] : null,
            'carreraId' => isset($_POST["carreraId"]) && $_POST["carreraId"] != '' ? $_POST["carreraId"] : null,
            'es_reticula' => isset($_POST["es_reticula"]) ? $_POST["es_reticula"] : null
        ];

        if ($this->materiaModel->crear($datos)) {
            header("Location: " . BASE_URL . "?page=materias&success=add");
        } else {
            header("Location: " . BASE_URL . "?page=materias&error=add");
        }
        exit();
    }

    /**
     * Actualiza una materia existente
     */
    private function actualizar() {
        $id = $_POST['ID_materia'];
        $datos = [
            'periodoId' => $_POST["periodoId"],
            'carreraId' => $_POST["carreraId"],
            'nombre' => $_POST['nombre']
        ];

        if ($this->materiaModel->actualizar($id, $datos)) {
            header("Location: " . BASE_URL . "?page=materias&success=update");
        } else {
            header("Location: " . BASE_URL . "?page=materias&error=update");
        }
        exit();
    }

    /**
     * Elimina una materia
     */
    private function eliminar() {
        $id = $_POST['ID_materia'];

        if ($this->materiaModel->eliminar($id)) {
            header("Location: " . BASE_URL . "?page=materias&success=delete");
        } else {
            header("Location: " . BASE_URL . "?page=materias&error=delete");
        }
        exit();
    }
}
?>
