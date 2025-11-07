<?php
$pageTitle = 'Registrarse - Mi Agenda';
ob_start();
?>

<div class="auth-container">
    <div class="auth-header">
        <i class="bi bi-person-plus-fill" style="font-size: 48px;"></i>
        <h2>Crear Cuenta</h2>
        <p>Regístrate para comenzar a organizar tus actividades</p>
    </div>
    
    <div class="auth-body">
        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= $mensajeError ?>
            </div>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>?page=registrarse" method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">
                    <i class="bi bi-person"></i> Nombre de Usuario
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Elige un nombre de usuario" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="contrasena" class="form-label">
                    <i class="bi bi-lock"></i> Contraseña
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Crea una contraseña segura" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-person-check me-2"></i>
                Crear Cuenta
            </button>
        </form>
        
        <div class="divider">
            <span>o</span>
        </div>
        
        <a href="<?= BASE_URL ?>?page=login" class="btn btn-outline-primary w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            Ya Tengo Cuenta
        </a>
    </div>
    
    <div class="auth-footer">
        <a href="<?= BASE_URL ?>?page=home">
            <i class="bi bi-house-door me-1"></i>
            Volver a la página principal
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/auth.php';
?>
