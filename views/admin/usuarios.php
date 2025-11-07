<?php
$pageTitle = 'Gestión de Usuarios';

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
        <a class="nav-link active" href="<?= BASE_URL ?>?page=usuarios">
            <i class="bi bi-people"></i> Usuarios
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>?page=carreras">
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
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-people"></i> Gestión de Usuarios</h1>
            <p class="mb-0">Administra los usuarios del sistema</p>
        </div>
        <div>
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                <i class="bi bi-person-plus"></i> Nuevo Usuario
            </button>
        </div>
    </div>
</div>

<!-- Mensajes -->
<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-<?= $_SESSION['tipo_mensaje'] == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $_SESSION['tipo_mensaje'] == 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
        <?= $_SESSION['mensaje'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php 
    unset($_SESSION['mensaje']);
    unset($_SESSION['tipo_mensaje']);
    ?>
<?php endif; ?>

<!-- Tabla de usuarios -->
<div class="card animate-fade-in">
    <div class="card-header">
        <i class="bi bi-table me-2"></i> Lista de Usuarios
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Usuario</th>
                        <th>Tipo de Usuario</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['ID_usuarios']) ?></td>
                                <td>
                                    <i class="bi bi-person-circle me-2"></i>
                                    <?= htmlspecialchars($usuario['nombre']) ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $usuario['tipo_usuario'] == 'Admin' ? 'danger' : 'primary' ?>">
                                        <i class="bi bi-<?= $usuario['tipo_usuario'] == 'Admin' ? 'shield-fill-check' : 'person' ?>"></i>
                                        <?= htmlspecialchars($usuario['tipo_usuario']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="editarUsuario(<?= $usuario['ID_usuarios'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>', <?= $usuario['tiposusuariosid'] ?>)"
                                            title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php if ($usuario['ID_usuarios'] != $_SESSION['user_id']): ?>
                                        <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                                onclick="eliminarUsuario(<?= $usuario['ID_usuarios'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>')"
                                                title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">No hay usuarios registrados</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus"></i> Crear Nuevo Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>?page=crear-usuario" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nuevo_nombre" class="form-label">
                            <i class="bi bi-person"></i> Nombre de Usuario
                        </label>
                        <input type="text" class="form-control" id="nuevo_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevo_contrasena" class="form-label">
                            <i class="bi bi-lock"></i> Contraseña
                        </label>
                        <input type="password" class="form-control" id="nuevo_contrasena" name="contrasena" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevo_tipo" class="form-label">
                            <i class="bi bi-shield-check"></i> Tipo de Usuario
                        </label>
                        <select class="form-select" id="nuevo_tipo" name="tiposusuarioid" required>
                            <option value="">Seleccione un tipo...</option>
                            <?php foreach ($tiposUsuario as $tipo): ?>
                                <option value="<?= $tipo['ID_tiposusuarios'] ?>">
                                    <?= htmlspecialchars($tipo['tipo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil"></i> Editar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>?page=actualizar-usuario" method="POST">
                <input type="hidden" id="editar_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editar_nombre" class="form-label">
                            <i class="bi bi-person"></i> Nombre de Usuario
                        </label>
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_contrasena" class="form-label">
                            <i class="bi bi-lock"></i> Nueva Contraseña
                        </label>
                        <input type="password" class="form-control" id="editar_contrasena" name="contrasena" placeholder="Dejar en blanco para mantener la actual">
                        <small class="text-muted">Solo llena este campo si deseas cambiar la contraseña</small>
                    </div>
                    <div class="mb-3">
                        <label for="editar_tipo" class="form-label">
                            <i class="bi bi-shield-check"></i> Tipo de Usuario
                        </label>
                        <select class="form-select" id="editar_tipo" name="tiposusuarioid" required>
                            <?php foreach ($tiposUsuario as $tipo): ?>
                                <option value="<?= $tipo['ID_tiposusuarios'] ?>">
                                    <?= htmlspecialchars($tipo['tipo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form oculto para eliminar -->
<form id="formEliminarUsuario" action="<?= BASE_URL ?>?page=eliminar-usuario" method="POST" style="display: none;">
    <input type="hidden" id="eliminar_id" name="id">
</form>

<script>
const baseUrl = '<?= BASE_URL ?>';

// Funciones globales para los onclick
window.editarUsuario = function(id, nombre, tiposusuarioid) {
    document.getElementById('editar_id').value = id;
    document.getElementById('editar_nombre').value = nombre;
    document.getElementById('editar_tipo').value = tiposusuarioid;
    document.getElementById('editar_contrasena').value = '';
    
    var modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
    modal.show();
};

window.eliminarUsuario = function(id, nombre) {
    if (confirm('¿Estás seguro de que deseas eliminar al usuario "' + nombre + '"?\n\nEsta acción no se puede deshacer.')) {
        const formData = new FormData();
        formData.append('id', id);
        
        const btns = document.querySelectorAll('.btn-eliminar');
        btns.forEach(b => b.disabled = true);
        
        fetch(baseUrl + '?page=eliminar-usuario', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
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
            mostrarMensaje('Error al eliminar el usuario', 'danger');
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

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Crear Usuario
    document.getElementById('formCrearUsuario').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        const btnText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creando...';
        
        fetch(baseUrl + '?page=crear-usuario', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalCrearUsuario')).hide();
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
            mostrarMensaje('Error al crear el usuario', 'danger');
            btn.disabled = false;
            btn.innerHTML = btnText;
        });
    });

    // Editar Usuario
    document.getElementById('modalEditarUsuario').querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const btn = this.querySelector('button[type="submit"]');
        const btnText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...';
        
        fetch(baseUrl + '?page=actualizar-usuario', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario')).hide();
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
            mostrarMensaje('Error al actualizar el usuario', 'danger');
            btn.disabled = false;
            btn.innerHTML = btnText;
        });
    });

    // Limpiar formularios al cerrar modales
    document.getElementById('modalCrearUsuario').addEventListener('hidden.bs.modal', function() {
        document.getElementById('formCrearUsuario').reset();
    });

    document.getElementById('modalEditarUsuario').addEventListener('hidden.bs.modal', function() {
        this.querySelector('form').reset();
    });
});
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/dashboard.php';
?>
