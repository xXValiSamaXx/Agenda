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

<!-- Filtros -->
<div class="card mb-3 animate-fade-in">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-4">
                <label class="form-label mb-1"><i class="bi bi-funnel"></i> Filtrar por tipo:</label>
                <select class="form-select" id="filtroTipo">
                    <option value="">Todos los usuarios</option>
                    <option value="Admin">👑 Administradores</option>
                    <option value="Maestro">👨‍🏫 Maestros</option>
                    <option value="Administrativo">💼 Administrativos</option>
                    <option value="Alumno">🎓 Alumnos</option>
                    <option value="User">👤 Usuarios (legacy)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label mb-1"><i class="bi bi-search"></i> Buscar:</label>
                <input type="text" class="form-control" id="buscarUsuario" placeholder="Nombre o email...">
            </div>
            <div class="col-md-4">
                <label class="form-label mb-1">&nbsp;</label>
                <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()">
                    <i class="bi bi-x-circle"></i> Limpiar filtros
                </button>
            </div>
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
                        <th>Email</th>
                        <th>Tipo de Usuario</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaUsuariosBody">
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr data-tipo="<?= htmlspecialchars($usuario['tipo_usuario']) ?>" data-nombre="<?= htmlspecialchars(strtolower($usuario['nombre'])) ?>" data-email="<?= htmlspecialchars(strtolower($usuario['email'] ?? '')) ?>">
                                <td><?= htmlspecialchars($usuario['ID_usuarios']) ?></td>
                                <td>
                                    <i class="bi bi-person-circle me-2"></i>
                                    <?= htmlspecialchars($usuario['nombre']) ?>
                                </td>
                                <td>
                                    <?php if (!empty($usuario['email'])): ?>
                                        <i class="bi bi-envelope me-1"></i>
                                        <?= htmlspecialchars($usuario['email']) ?>
                                    <?php else: ?>
                                        <span class="text-muted"><i>Sin email</i></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $usuario['tipo_usuario'] == 'Admin' ? 'danger' : 'primary' ?>">
                                        <i class="bi bi-<?= $usuario['tipo_usuario'] == 'Admin' ? 'shield-fill-check' : 'person' ?>"></i>
                                        <?= htmlspecialchars($usuario['tipo_usuario']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="editarUsuario(<?= $usuario['ID_usuarios'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>', '<?= htmlspecialchars($usuario['email'] ?? '') ?>', <?= $usuario['tiposusuariosid'] ?>)"
                                            title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php if ($usuario['tipo_usuario'] != 'Admin' || $usuario['ID_usuarios'] != $_SESSION['user_id']): ?>
                                        <button class="btn btn-sm btn-outline-warning" 
                                                onclick="resetearContrasena(<?= $usuario['ID_usuarios'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>')"
                                                title="Resetear contraseña">
                                            <i class="bi bi-key"></i>
                                        </button>
                                    <?php endif; ?>
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
                        <tr id="noUsuarios">
                            <td colspan="5" class="text-center text-muted py-4">
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
            <form id="formCrearUsuario" action="<?= BASE_URL ?>?page=crear-usuario" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nuevo_nombre" class="form-label">
                            <i class="bi bi-person"></i> Nombre de Usuario
                        </label>
                        <input type="text" class="form-control" id="nuevo_nombre" name="nombre" required minlength="3">
                    </div>
                    <div class="mb-3">
                        <label for="nuevo_email" class="form-label">
                            <i class="bi bi-envelope"></i> Correo Electrónico
                        </label>
                        <input type="email" class="form-control" id="nuevo_email" name="email" placeholder="usuario@ejemplo.com">
                        <small class="text-muted">Opcional - Para recibir recordatorios de actividades</small>
                    </div>
                    <div class="mb-3">
                        <label for="nuevo_contrasena" class="form-label">
                            <i class="bi bi-lock"></i> Contraseña
                        </label>
                        <input type="password" class="form-control" id="nuevo_contrasena" name="contrasena" required minlength="6">
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
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required minlength="3">
                    </div>
                    <div class="mb-3">
                        <label for="editar_email" class="form-label">
                            <i class="bi bi-envelope"></i> Correo Electrónico
                        </label>
                        <input type="email" class="form-control" id="editar_email" name="email" placeholder="usuario@ejemplo.com">
                        <small class="text-muted">Opcional - Para recibir recordatorios de actividades</small>
                    </div>
                    <div class="mb-3">
                        <label for="editar_contrasena" class="form-label">
                            <i class="bi bi-lock"></i> Nueva Contraseña
                        </label>
                        <input type="password" class="form-control" id="editar_contrasena" name="contrasena" placeholder="Dejar en blanco para mantener la actual" minlength="6">
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

<!-- Modal Resetear Contraseña -->
<div class="modal fade" id="modalResetearPassword" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-key"></i> Resetear Contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formResetPassword" method="POST">
                <input type="hidden" id="reset_id" name="id">
                <div class="modal-body">
                    <p class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        Vas a resetear la contraseña del usuario: <strong id="reset_nombre_display"></strong>
                    </p>
                    <div class="mb-3">
                        <label for="reset_nueva_contrasena" class="form-label">
                            <i class="bi bi-lock"></i> Nueva Contraseña
                        </label>
                        <input type="password" class="form-control" id="reset_nueva_contrasena" name="contrasena" required minlength="6" placeholder="Mínimo 6 caracteres">
                    </div>
                    <div class="mb-3">
                        <label for="reset_confirmar_contrasena" class="form-label">
                            <i class="bi bi-lock-fill"></i> Confirmar Contraseña
                        </label>
                        <input type="password" class="form-control" id="reset_confirmar_contrasena" required minlength="6" placeholder="Confirmar nueva contraseña">
                    </div>
                    <div id="password-match-message"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key me-2"></i>Resetear Contraseña
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
window.editarUsuario = function(id, nombre, email, tiposusuarioid) {
    document.getElementById('editar_id').value = id;
    document.getElementById('editar_nombre').value = nombre;
    document.getElementById('editar_email').value = email || '';
    document.getElementById('editar_tipo').value = tiposusuarioid;
    document.getElementById('editar_contrasena').value = '';
    
    var modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
    modal.show();
};

window.resetearContrasena = function(id, nombre) {
    document.getElementById('reset_id').value = id;
    document.getElementById('reset_nombre_display').textContent = nombre;
    document.getElementById('reset_nueva_contrasena').value = '';
    document.getElementById('reset_confirmar_contrasena').value = '';
    document.getElementById('password-match-message').innerHTML = '';
    
    var modal = new bootstrap.Modal(document.getElementById('modalResetearPassword'));
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

// Función para filtrar usuarios
function filtrarUsuarios() {
    const filtroTipo = document.getElementById('filtroTipo').value.toLowerCase();
    const busqueda = document.getElementById('buscarUsuario').value.toLowerCase();
    const filas = document.querySelectorAll('#tablaUsuariosBody tr[data-tipo]');
    
    let visibles = 0;
    filas.forEach(fila => {
        const tipo = fila.getAttribute('data-tipo').toLowerCase();
        const nombre = fila.getAttribute('data-nombre');
        const email = fila.getAttribute('data-email');
        
        const coincideTipo = !filtroTipo || tipo === filtroTipo;
        const coincideBusqueda = !busqueda || nombre.includes(busqueda) || email.includes(busqueda);
        
        if (coincideTipo && coincideBusqueda) {
            fila.style.display = '';
            visibles++;
        } else {
            fila.style.display = 'none';
        }
    });
    
    // Mostrar mensaje si no hay resultados
    const noResultados = document.getElementById('noUsuarios');
    if (visibles === 0 && !noResultados) {
        const tbody = document.getElementById('tablaUsuariosBody');
        const tr = document.createElement('tr');
        tr.id = 'noResultados';
        tr.innerHTML = '<td colspan="5" class="text-center text-muted py-4"><i class="bi bi-search"></i><p class="mt-2">No se encontraron usuarios con esos criterios</p></td>';
        tbody.appendChild(tr);
    } else if (visibles > 0) {
        const noResultados = document.getElementById('noResultados');
        if (noResultados) noResultados.remove();
    }
}

// Función para limpiar filtros
window.limpiarFiltros = function() {
    document.getElementById('filtroTipo').value = '';
    document.getElementById('buscarUsuario').value = '';
    filtrarUsuarios();
};

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Crear Usuario
    const formCrear = document.getElementById('formCrearUsuario');
    if (formCrear) {
        formCrear.addEventListener('submit', function(e) {
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
                    bootstrap.Modal.getInstance(document.getElementById('modalNuevoUsuario')).hide();
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
    }

    // Editar Usuario
    const formEditar = document.getElementById('modalEditarUsuario')?.querySelector('form');
    if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
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
    }

    // Limpiar formularios al cerrar modales
    const modalNuevo = document.getElementById('modalNuevoUsuario');
    if (modalNuevo) {
        modalNuevo.addEventListener('hidden.bs.modal', function() {
            document.getElementById('formCrearUsuario').reset();
        });
    }

    const modalEditar = document.getElementById('modalEditarUsuario');
    if (modalEditar) {
        modalEditar.addEventListener('hidden.bs.modal', function() {
            this.querySelector('form').reset();
        });
    }
    
    // Filtros
    const filtroTipo = document.getElementById('filtroTipo');
    const buscarUsuario = document.getElementById('buscarUsuario');
    
    if (filtroTipo) {
        filtroTipo.addEventListener('change', filtrarUsuarios);
    }
    
    if (buscarUsuario) {
        buscarUsuario.addEventListener('input', filtrarUsuarios);
    }
    
    // Resetear contraseña
    const formResetPassword = document.getElementById('formResetPassword');
    if (formResetPassword) {
        // Validar que las contraseñas coincidan
        const nuevaPassword = document.getElementById('reset_nueva_contrasena');
        const confirmarPassword = document.getElementById('reset_confirmar_contrasena');
        const mensaje = document.getElementById('password-match-message');
        
        confirmarPassword.addEventListener('input', function() {
            if (nuevaPassword.value && confirmarPassword.value) {
                if (nuevaPassword.value === confirmarPassword.value) {
                    mensaje.innerHTML = '<small class="text-success"><i class="bi bi-check-circle"></i> Las contraseñas coinciden</small>';
                    confirmarPassword.setCustomValidity('');
                } else {
                    mensaje.innerHTML = '<small class="text-danger"><i class="bi bi-x-circle"></i> Las contraseñas no coinciden</small>';
                    confirmarPassword.setCustomValidity('Las contraseñas no coinciden');
                }
            }
        });
        
        formResetPassword.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (nuevaPassword.value !== confirmarPassword.value) {
                mostrarMensaje('Las contraseñas no coinciden', 'danger');
                return;
            }
            
            const formData = new FormData();
            formData.append('id', document.getElementById('reset_id').value);
            formData.append('contrasena', nuevaPassword.value);
            
            const btn = this.querySelector('button[type="submit"]');
            const btnText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Reseteando...';
            
            fetch(baseUrl + '?page=resetear-password', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalResetearPassword')).hide();
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
                mostrarMensaje('Error al resetear la contraseña', 'danger');
                btn.disabled = false;
                btn.innerHTML = btnText;
            });
        });
    }
});
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/dashboard.php';
?>
