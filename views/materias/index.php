<?php
$pageTitle = 'Gestión de Materias';

// Navegación
ob_start();
?>
<ul class="navbar-nav ms-auto align-items-center">
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>?page=admin">
            <i class="bi bi-speedometer2"></i> Panel Admin
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>?page=usuarios">
            <i class="bi bi-people"></i> Usuarios
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>?page=carreras">
            <i class="bi bi-mortarboard"></i> Carreras
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="<?= BASE_URL ?>?page=materias">
            <i class="bi bi-book"></i> Materias
        </a>
    </li>
    <li class="nav-item">
        <a class="btn btn-logout" href="<?= BASE_URL ?>?page=logout">
            <i class="bi bi-box-arrow-right me-1"></i>
            Cerrar Sesión
        </a>
    </li>
</ul>
<?php
$navContent = ob_get_clean();

// Contenido principal
ob_start();
?>

<!-- Header de la página -->
<div class="page-header animate-fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1><i class="bi bi-book"></i> Gestión de Materias</h1>
            <p class="mb-0">Administra las materias por carrera</p>
        </div>
        <div class="mt-3 mt-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMateriaModal">
                <i class="bi bi-plus-circle me-2"></i>
                Nueva Materia
            </button>
        </div>
    </div>
</div>

<!-- Mensajes de notificación -->
<?php
if (isset($_SESSION['mensaje'])) {
    $tipo = isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : 'success';
    $icono = ($tipo == 'success') ? 'check-circle' : 'exclamation-triangle';
    $clase = ($tipo == 'success') ? 'alert-success' : 'alert-danger';
    echo "<div class='alert {$clase} alert-dismissible fade show animate-fade-in' role='alert'>
            <i class='bi bi-{$icono}-fill me-2'></i>
            {$_SESSION['mensaje']}
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
          </div>";
    unset($_SESSION['mensaje']);
    unset($_SESSION['tipo_mensaje']);
}
?>

<!-- Filtro por carrera -->
<div class="card mb-4 animate-fade-in">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-auto">
                <label class="fw-bold mb-2 mb-md-0">
                    <i class="bi bi-funnel"></i> Filtrar por carrera:
                </label>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="carreraFilter">
                    <option value="">Todas las carreras</option>
                    <?php foreach ($carreras as $carrera): ?>
                        <option value="<?= $carrera['ID_carrera'] ?>"><?= htmlspecialchars($carrera['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Materias -->
<div class="card animate-fade-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Materia</th>
                        <th>Período</th>
                        <th>Carrera</th>
                        <th width="120" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($materias)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted mb-0">No hay materias registradas</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($materias as $materia): ?>
                        <?php
                            $esReticula = "generica";
                            foreach ($carrera_materia as $cm) {
                                if ($cm['materiaid'] == $materia['ID_materia']) {
                                    $esReticula = $cm['es_reticula'] ? "reticula" : "generica";
                                    break;
                                }
                            }
                        ?>
                        <tr data-carrera="<?= $materia['carreraid'] ?>" data-es-reticula="<?= $esReticula ?>">
                            <td><?= $materia['ID_materia'] ?></td>
                            <td>
                                <i class="bi bi-journal-text text-primary me-2"></i>
                                <strong><?= htmlspecialchars($materia['nombre']) ?></strong>
                            </td>
                            <td>
                                <?php 
                                    $periodoNombre = '';
                                    foreach($periodos as $periodo) {
                                        if($periodo['ID_periodo'] == $materia['periodoid']) {
                                            $periodoNombre = $periodo['nombre'];
                                            break;
                                        }
                                    }
                                ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($periodoNombre) ?></span>
                            </td>
                            <td>
                                <?php 
                                    $carreraNombre = '';
                                    foreach($carreras as $carrera) {
                                        if($carrera['ID_carrera'] == $materia['carreraid']) {
                                            $carreraNombre = $carrera['nombre'];
                                            break;
                                        }
                                    }
                                ?>
                                <span class="badge bg-info"><?= htmlspecialchars($carreraNombre) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="editMateria(<?= $materia['ID_materia'] ?>, '<?= htmlspecialchars($materia['nombre']) ?>', <?= $materia['periodoid'] ?>, <?= $materia['carreraid'] ?>)"
                                            title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="deleteMateria(<?= $materia['ID_materia'] ?>, '<?= htmlspecialchars($materia['nombre']) ?>')"
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para añadir nueva materia -->
<div class="modal fade" id="addMateriaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle"></i> Nueva Materia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>?page=materias" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            <i class="bi bi-journal-text"></i> Nombre de la Materia
                        </label>
                        <input type="text" class="form-control" name="nombre" id="nombre" 
                               pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+"
                               placeholder="Ej: Cálculo Diferencial" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="periodoId" class="form-label">
                            <i class="bi bi-calendar-range"></i> Período
                        </label>
                        <select class="form-select" name="periodoId" id="periodoId" required>
                            <option value="">Seleccionar período...</option>
                            <?php foreach ($periodos as $periodo): ?>
                                <option value="<?= $periodo['ID_periodo'] ?>">
                                    <?= htmlspecialchars($periodo['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="carreraId" class="form-label">
                            <i class="bi bi-mortarboard"></i> Carrera
                        </label>
                        <select class="form-select" name="carreraId" id="carreraId" required>
                            <option value="">Seleccionar carrera...</option>
                            <?php foreach ($carreras as $carrera): ?>
                                <option value="<?= $carrera['ID_carrera'] ?>">
                                    <?= htmlspecialchars($carrera['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="add" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        Guardar Materia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar materia -->
<div class="modal fade" id="editMateriaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil"></i> Editar Materia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>?page=materias" method="post">
                <input type="hidden" name="ID_materia" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">
                            <i class="bi bi-journal-text"></i> Nombre de la Materia
                        </label>
                        <input type="text" class="form-control" name="nombre" id="edit_nombre" 
                               pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_periodoId" class="form-label">
                            <i class="bi bi-calendar-range"></i> Período
                        </label>
                        <select class="form-select" name="periodoId" id="edit_periodoId" required>
                            <?php foreach ($periodos as $periodo): ?>
                                <option value="<?= $periodo['ID_periodo'] ?>">
                                    <?= htmlspecialchars($periodo['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_carreraId" class="form-label">
                            <i class="bi bi-mortarboard"></i> Carrera
                        </label>
                        <select class="form-select" name="carreraId" id="edit_carreraId" required>
                            <?php foreach ($carreras as $carrera): ?>
                                <option value="<?= $carrera['ID_carrera'] ?>">
                                    <?= htmlspecialchars($carrera['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" name="update" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Formulario oculto para eliminar -->
<form id="deleteForm" action="<?= BASE_URL ?>?page=materias" method="post" style="display:none;">
    <input type="hidden" name="ID_materia" id="delete_id">
    <input type="hidden" name="delete" value="1">
</form>

<?php
$additionalJS = <<<'JS'
<script>
// Filtro por carrera
document.getElementById('carreraFilter').addEventListener('change', function() {
    const carreraId = this.value;
    const rows = document.querySelectorAll('tbody tr[data-carrera]');
    
    rows.forEach(function(row) {
        if (carreraId === '' || row.getAttribute('data-carrera') === carreraId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function editMateria(id, nombre, periodoId, carreraId) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_periodoId').value = periodoId;
    document.getElementById('edit_carreraId').value = carreraId;
    
    var modal = new bootstrap.Modal(document.getElementById('editMateriaModal'));
    modal.show();
}

function deleteMateria(id, nombre) {
    if (confirm('¿Estás seguro de que deseas eliminar la materia "' + nombre + '"?\n\nEsta acción no se puede deshacer.')) {
        document.getElementById('delete_id').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
JS;

$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/dashboard.php';
?>
