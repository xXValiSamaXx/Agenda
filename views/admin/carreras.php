<?php
$pageTitle = 'Gestión de Carreras';

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
        <a class="nav-link active" href="<?= BASE_URL ?>?page=carreras">
            <i class="bi bi-mortarboard"></i> Carreras
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>?page=materias">
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
            <h1><i class="bi bi-mortarboard"></i> Gestión de Carreras</h1>
            <p class="mb-0">Administra las carreras disponibles en el sistema</p>
        </div>
        <div class="mt-3 mt-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarreraModal">
                <i class="bi bi-plus-circle me-2"></i>
                Nueva Carrera
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

<!-- Tarjetas de Carreras -->
<div class="row g-4 animate-fade-in">
    <?php if (empty($carreras)): ?>
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle fs-1 d-block mb-2"></i>
            <h5>No hay carreras registradas</h5>
            <p class="mb-0">Haz clic en "Nueva Carrera" para agregar la primera carrera</p>
        </div>
    </div>
    <?php else: ?>
        <?php foreach($carreras as $carrera): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-mortarboard me-2"></i><?= htmlspecialchars($carrera['nombre']) ?></span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" 
                                   onclick="editCarrera(<?= $carrera['ID_carrera'] ?>, 
                                   '<?= htmlspecialchars($carrera['nombre']) ?>', 
                                   '<?= htmlspecialchars($carrera['perfil_carrera']) ?>', 
                                   '<?= htmlspecialchars($carrera['duracion']) ?>', 
                                   '<?= htmlspecialchars($carrera['descripcion']) ?>')">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger btn-delete-carrera" href="#" 
                                   onclick="deleteCarrera(<?= $carrera['ID_carrera'] ?>, '<?= htmlspecialchars($carrera['nombre']) ?>')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1"><i class="bi bi-award"></i> Perfil</small>
                        <span class="badge bg-primary"><?= htmlspecialchars($carrera['perfil_carrera']) ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1"><i class="bi bi-calendar3"></i> Duración</small>
                        <strong><?= htmlspecialchars($carrera['duracion']) ?> semestres</strong>
                    </div>
                    
                    <div>
                        <small class="text-muted d-block mb-1"><i class="bi bi-info-circle"></i> Descripción</small>
                        <p class="mb-0"><?= htmlspecialchars($carrera['descripcion']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal para añadir nueva carrera -->
<div class="modal fade" id="addCarreraModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle"></i> Nueva Carrera
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>?page=crear-carrera" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            <i class="bi bi-mortarboard"></i> Nombre de la Carrera
                        </label>
                        <input type="text" class="form-control" name="nombre" id="nombre" 
                               pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" 
                               placeholder="Ej: Ingeniería en Sistemas Computacionales" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="perfil_carrera" class="form-label">
                            <i class="bi bi-award"></i> Perfil de Carrera
                        </label>
                        <select class="form-select" name="perfil_carrera" id="perfil_carrera" required>
                            <option value="">Seleccionar perfil...</option>
                            <option value="Escolarizado">Escolarizado</option>
                            <option value="Mixto">Mixto</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="duracion" class="form-label">
                            <i class="bi bi-calendar3"></i> Duración (semestres)
                        </label>
                        <input type="number" class="form-control" name="duracion" id="duracion" 
                               min="7" max="18" placeholder="Ej: 9" required>
                        <small class="text-muted">Escolarizado: 7-12, Mixto: 12-18</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            <i class="bi bi-info-circle"></i> Descripción
                        </label>
                        <textarea class="form-control" name="descripcion" id="descripcion" 
                                  rows="3" placeholder="Describe la carrera..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>
                        Guardar Carrera
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar carrera -->
<div class="modal fade" id="editCarreraModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil"></i> Editar Carrera
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>?page=actualizar-carrera" method="post">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">
                            <i class="bi bi-mortarboard"></i> Nombre de la Carrera
                        </label>
                        <input type="text" class="form-control" name="nombre" id="edit_nombre" 
                               pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_perfil_carrera" class="form-label">
                            <i class="bi bi-award"></i> Perfil de Carrera
                        </label>
                        <select class="form-select" name="perfil_carrera" id="edit_perfil_carrera" required>
                            <option value="Escolarizado">Escolarizado</option>
                            <option value="Mixto">Mixto</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_duracion" class="form-label">
                            <i class="bi bi-calendar3"></i> Duración (semestres)
                        </label>
                        <input type="number" class="form-control" name="duracion" id="edit_duracion" 
                               min="7" max="18" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">
                            <i class="bi bi-info-circle"></i> Descripción
                        </label>
                        <textarea class="form-control" name="descripcion" id="edit_descripcion" 
                                  rows="3" required></textarea>
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
<form id="deleteForm" action="<?= BASE_URL ?>?page=eliminar-carrera" method="post" style="display:none;">
    <input type="hidden" name="id" id="delete_id">
    <input type="hidden" name="delete" value="1">
</form>

<script>
const baseUrl = '<?= BASE_URL ?>';

// Funciones globales para los onclick
window.editCarrera = function(id, nombre, perfil, duracion, descripcion) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_perfil_carrera').value = perfil;
    document.getElementById('edit_duracion').value = duracion;
    document.getElementById('edit_descripcion').value = descripcion;
    
    var modal = new bootstrap.Modal(document.getElementById('editCarreraModal'));
    modal.show();
};

window.deleteCarrera = function(id, nombre) {
    if (confirm('¿Estás seguro de que deseas eliminar la carrera "' + nombre + '"?\n\nEsta acción no se puede deshacer.')) {
        const formData = new FormData();
        formData.append('id', id);
        
        const btns = document.querySelectorAll('.btn-delete-carrera');
        btns.forEach(b => b.disabled = true);
        
        fetch(baseUrl + '?page=eliminar-carrera', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarMensaje(data.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                mostrarMensaje(data.message, 'danger');
                btns.forEach(b => b.disabled = false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('Error al eliminar la carrera', 'danger');
            btns.forEach(b => b.disabled = false);
        });
    }
};

function mostrarMensaje(mensaje, tipo) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 3000);
}

// Event listeners para formularios
document.addEventListener('DOMContentLoaded', function() {
    // Crear Carrera
    document.getElementById('addCarreraForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        const btnText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creando...';
        
        fetch(baseUrl + '?page=crear-carrera', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('addCarreraModal')).hide();
                mostrarMensaje(data.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                mostrarMensaje(data.message, 'danger');
                btn.disabled = false;
                btn.innerHTML = btnText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('Error al crear la carrera', 'danger');
            btn.disabled = false;
            btn.innerHTML = btnText;
        });
    });

    // Editar Carrera
    document.getElementById('editCarreraForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        const btnText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';
        
        fetch(baseUrl + '?page=actualizar-carrera', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editCarreraModal')).hide();
                mostrarMensaje(data.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                mostrarMensaje(data.message, 'danger');
                btn.disabled = false;
                btn.innerHTML = btnText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('Error al actualizar la carrera', 'danger');
            btn.disabled = false;
            btn.innerHTML = btnText;
        });
    });

    // Limpiar formularios al cerrar modales
    document.getElementById('addCarreraModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('addCarreraForm').reset();
    });

    document.getElementById('editCarreraModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('editCarreraForm').reset();
    });
});
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/dashboard.php';
?>
