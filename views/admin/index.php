<?php
$pageTitle = 'Panel de Administración';

// Navegación
ob_start();
?>
<ul class="navbar-nav ms-auto align-items-center">
    <li class="nav-item">
        <a class="nav-link active" href="<?= BASE_URL ?>?page=admin">
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
    <h1><i class="bi bi-speedometer2"></i> Panel de Administración</h1>
    <p class="mb-0">Gestiona el sistema de agenda académica</p>
</div>

<!-- Dashboard Cards -->
<div class="row g-4 animate-fade-in">
    <!-- Tarjeta de Usuarios -->
    <div class="col-md-6 col-lg-4">
        <div class="card text-center">
            <div class="card-body p-4">
                <div class="mb-3">
                    <i class="bi bi-people text-info" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title">Gestión de Usuarios</h5>
                <p class="card-text text-muted">Administra los usuarios del sistema</p>
                <a href="<?= BASE_URL ?>?page=usuarios" class="btn btn-primary">
                    <i class="bi bi-arrow-right-circle me-2"></i>
                    Gestionar Usuarios
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Carreras -->
    <div class="col-md-6 col-lg-4">
        <div class="card text-center">
            <div class="card-body p-4">
                <div class="mb-3">
                    <i class="bi bi-mortarboard text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title">Gestión de Carreras</h5>
                <p class="card-text text-muted">Administra las carreras disponibles en el sistema</p>
                <a href="<?= BASE_URL ?>?page=carreras" class="btn btn-primary">
                    <i class="bi bi-arrow-right-circle me-2"></i>
                    Gestionar Carreras
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Materias -->
    <div class="col-md-6 col-lg-4">
        <div class="card text-center">
            <div class="card-body p-4">
                <div class="mb-3">
                    <i class="bi bi-book text-success" style="font-size: 3rem;"></i>
                </div>
                <h5 class="card-title">Gestión de Materias</h5>
                <p class="card-text text-muted">Administra las materias por carrera</p>
                <a href="<?= BASE_URL ?>?page=materias" class="btn btn-primary">
                    <i class="bi bi-arrow-right-circle me-2"></i>
                    Gestionar Materias
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/dashboard.php';
?>
