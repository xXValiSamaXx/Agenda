<?php
$pageTitle = 'Editar Actividad';

// Navegación (puede reutilizarse desde el index)
ob_start();
?>
<ul class="navbar-nav ms-auto align-items-center">
    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>?page=actividades">
            <i class="bi bi-calendar-check"></i> Mis Actividades
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
<div class="page-header animate-fade-in">
    <h1><i class="bi bi-pencil-square"></i> Editar Actividad</h1>
    <p class="mb-0">Modifica los datos de tu actividad</p>
</div>

<div class="card animate-fade-in">
    <div class="card-body">
        <?php if (!empty($actividad)): ?>
        <form action="<?= BASE_URL ?>?page=actualizar-actividad" method="POST">
            <input type="hidden" name="id_actividad" value="<?= htmlspecialchars($actividad['ID_actividadesacademicas']) ?>">

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag"></i> Tipo de Actividad</label>
                <select name="tiposactividadesID" class="form-select" required>
                    <?php foreach ($tiposActividades as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= ($actividad['tiposactividadesID'] == $id) ? 'selected' : '' ?>><?= htmlspecialchars($nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-book"></i> Materia</label>
                <select name="materiaID" class="form-select" required>
                    <?php foreach ($materias as $id => $nombre): ?>
                        <option value="<?= $id ?>" <?= ($actividad['materiaID'] == $id) ? 'selected' : '' ?>><?= htmlspecialchars($nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-text-paragraph"></i> Descripción</label>
                <textarea name="descripcion" class="form-control" rows="4" required><?= htmlspecialchars($actividad['descripcion']) ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label"><i class="bi bi-calendar3"></i> Fecha</label>
                <?php
                    // Asegurar formato Y-m-d para input date
                    $fechaVal = '';
                    if (!empty($actividad['fecha'])) {
                        if ($actividad['fecha'] instanceof DateTime) {
                            $fechaVal = $actividad['fecha']->format('Y-m-d');
                        } else {
                            // Si viene como string, intentar normalizar
                            $fechaVal = date('Y-m-d', strtotime($actividad['fecha']));
                        }
                    }
                ?>
                <input type="date" name="fecha" class="form-control" value="<?= $fechaVal ?>" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Guardar cambios</button>
                <a href="<?= BASE_URL ?>?page=academicas" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
        <?php else: ?>
            <div class="alert alert-warning">Actividad no encontrada.</div>
            <a href="<?= BASE_URL ?>?page=academicas" class="btn btn-secondary">Volver</a>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/dashboard.php';
?>
