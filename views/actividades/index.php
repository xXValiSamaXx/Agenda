<?php
$pageTitle = 'Mis Actividades Académicas';

// Navegación
ob_start();
?>
<ul class="navbar-nav ms-auto align-items-center">
    <li class="nav-item">
        <a class="nav-link active" href="<?= BASE_URL ?>?page=academicas">
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

<!-- Header de la página -->
<div class="page-header animate-fade-in">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="bi bi-calendar-event"></i> Mis Actividades Académicas</h1>
            <p class="mb-0">Gestiona tus tareas, proyectos y exámenes</p>
        </div>
        <div>
            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalActividad" type="button">
                <i class="bi bi-plus-circle"></i> Nueva Actividad
            </button>
        </div>
    </div>
</div>

<!-- Filtros de actividades -->
<div class="card animate-fade-in">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-auto">
                <h5 class="mb-3"><i class="bi bi-funnel"></i> Filtrar por tipo:</h5>
            </div>
            <div class="col-md">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="btnTodas">
                        <i class="bi bi-grid-3x3"></i> Todas
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnTareas" data-id="1">
                        <i class="bi bi-list-check"></i> Tareas
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnProyectos" data-id="2">
                        <i class="bi bi-folder"></i> Proyectos
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnExamenes" data-id="3">
                        <i class="bi bi-pencil-square"></i> Exámenes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pestañas de períodos -->
<ul class="nav nav-pills mb-4 animate-fade-in" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="semana-tab" data-bs-toggle="pill" data-bs-target="#semana" type="button" role="tab">
            <i class="bi bi-calendar-week"></i> Esta Semana
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="proxima-tab" data-bs-toggle="pill" data-bs-target="#proxima" type="button" role="tab">
            <i class="bi bi-calendar2-week"></i> Próxima Semana
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="mes-tab" data-bs-toggle="pill" data-bs-target="#mes" type="button" role="tab">
            <i class="bi bi-calendar-month"></i> Próximo Mes
        </button>
    </li>
</ul>

<!-- Contenido de las pestañas -->
<div class="tab-content">
    <?php 
    $periodos = [
        'semana' => 'Para esta semana',
        'proxima' => 'Para la próxima semana',
        'mes' => 'Para el próximo mes'
    ];
    $isFirst = true;
    foreach ($periodos as $key => $periodo): 
    ?>
    <div class="tab-pane fade <?= $isFirst ? 'show active' : '' ?>" id="<?= $key ?>" role="tabpanel">
        <div class="row">
            <?php 
            $hasActivities = false;
            foreach ($resultados as $index => $resultado): 
                if ($index === $periodo && isset($resultado['actividades']) && count($resultado['actividades']) > 0):
                    $hasActivities = true;
                    foreach ($resultado['actividades'] as $actividad): 
                        $tipoActividad = $tiposActividades[$actividad['tiposactividadesID']];
                        $iconos = [
                            'Tareas' => 'list-check',
                            'Proyectos' => 'folder',
                            'Exámenes' => 'pencil-square'
                        ];
                        $colores = [
                            'Tareas' => 'primary',
                            'Proyectos' => 'success',
                            'Exámenes' => 'danger'
                        ];
                        $icono = $iconos[$tipoActividad] ?? 'file-text';
                        $color = $colores[$tipoActividad] ?? 'secondary';
            ?>
            <div class="col-md-6 col-lg-4 mb-4 actividad-card" data-tipoactividad="<?= htmlspecialchars($tipoActividad) ?>">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-<?= $color ?> rounded-pill">
                                <i class="bi bi-<?= $icono ?>"></i> <?= htmlspecialchars($tipoActividad) ?>
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form action="<?= BASE_URL ?>?page=editar-actividad" method="POST" class="d-inline">
                                            <input type="hidden" name="id_actividad" value="<?= $actividad['ID_actividadesacademicas'] ?>">
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="<?= BASE_URL ?>?page=eliminar-actividad" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro que desea eliminar esta actividad?');">
                                            <input type="hidden" name="id_actividad" value="<?= $actividad['ID_actividadesacademicas'] ?>">
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <h5 class="card-title"><?= htmlspecialchars($materias[$actividad['materiaID']]) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($actividad['descripcion']) ?></p>
                        
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-calendar3 me-2"></i>
                            <small><?= htmlspecialchars($actividad['fecha']->format('d/m/Y')) ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                    endforeach;
                endif;
            endforeach;
            
            if (!$hasActivities): 
            ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle fs-1 d-block mb-2"></i>
                    <h5>No hay actividades para este período</h5>
                    <p class="mb-0">Haz clic en los botones de arriba para agregar nuevas actividades</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php 
        $isFirst = false;
    endforeach; 
    ?>
</div>

<!-- Modal para nueva actividad -->
<div class="modal fade" id="modalActividad" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActividadLabel">
                    <i class="bi bi-plus-circle"></i> Nueva Actividad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formModalActividad">
                    <!-- Selector de tipo de actividad -->
                    <div class="mb-3">
                        <label for="tiposactividadesIDInput" class="form-label">
                            <i class="bi bi-tag"></i> Tipo de Actividad
                        </label>
                        <select class="form-select" id="tiposactividadesIDInput" name="tiposactividadesID" required>
                            <option value="">Seleccione un tipo...</option>
                            <option value="1">📝 Tarea</option>
                            <option value="2">📁 Proyecto</option>
                            <option value="3">📋 Examen</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="materiaIDInput" class="form-label">
                            <i class="bi bi-book"></i> Materia
                        </label>
                        <select class="form-select" id="materiaIDInput" name="materiaID" required>
                            <?php
                            if (!empty($carrera_materias)) {
                                foreach ($carrera_materias as $idMateria => $nombreMateria) {
                                    echo "<option value='$idMateria'>$nombreMateria</option>";
                                }
                            } else {
                                echo "<option value=''>No hay materias disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcionInput" class="form-label">
                            <i class="bi bi-text-paragraph"></i> Descripción
                        </label>
                        <textarea class="form-control" id="descripcionInput" name="descripcion" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="fechaInput" class="form-label">
                            <i class="bi bi-calendar3"></i> Fecha de Entrega
                        </label>
                        <input type="date" class="form-control" id="fechaInput" name="fecha" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Guardar Actividad
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$baseUrl = BASE_URL;
$userIdJson = json_encode($userId);

$additionalJS = <<<JAVASCRIPT
<script>
var userId = {$userIdJson};

\$(document).ready(function() {
    console.log('Document ready - jQuery loaded');
    console.log('userId:', userId);
    
    var today = new Date().toISOString().split('T')[0];
    \$('#fechaInput').attr('min', today);
    
    \$('#modalActividad').on('show.bs.modal', function() {
        \$('#formModalActividad')[0].reset();
        \$('#modalActividadLabel').html('<i class="bi bi-plus-circle"></i> Nueva Actividad');
    });
    
    \$('#tiposactividadesIDInput').change(function() {
        var tipoId = \$(this).val();
        var tipos = {
            '1': { nombre: 'Tarea', icono: 'list-check' },
            '2': { nombre: 'Proyecto', icono: 'folder' },
            '3': { nombre: 'Examen', icono: 'pencil-square' }
        };
        
        if (tipoId && tipos[tipoId]) {
            \$('#modalActividadLabel').html('<i class="bi bi-' + tipos[tipoId].icono + '"></i> Nueva ' + tipos[tipoId].nombre);
        } else {
            \$('#modalActividadLabel').html('<i class="bi bi-plus-circle"></i> Nueva Actividad');
        }
    });

    function filterTableByTipoActividad(tipoActividad) {
        var tipoActividadNombre = {
            1: "Tareas",
            2: "Proyectos",
            3: "Exámenes"
        };

        var tipoActividadSeleccionada = tipoActividadNombre[tipoActividad];

        if (!tipoActividad) {
            \$('.actividad-card').fadeIn();
            \$('.btn-group .btn').removeClass('active');
            \$('#btnTodas').addClass('active');
            return;
        }

        \$('.btn-group .btn').removeClass('active');
        \$('[data-id="' + tipoActividad + '"]').addClass('active');

        \$('.actividad-card').each(function() {
            var filaTipoActividad = \$(this).data('tipoactividad');
            if (filaTipoActividad === tipoActividadSeleccionada) {
                \$(this).fadeIn();
            } else {
                \$(this).fadeOut();
            }
        });
    }

    \$('#btnTodas').click(function() {
        filterTableByTipoActividad(null);
    });

    \$('#btnTareas').click(function() {
        filterTableByTipoActividad(1);
    });
    
    \$('#btnProyectos').click(function() {
        filterTableByTipoActividad(2);
    });
    
    \$('#btnExamenes').click(function() {
        filterTableByTipoActividad(3);
    });

    \$('#formModalActividad').on('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();
        
        console.log('Form submit intercepted');

        var tipoActividad = \$('#tiposactividadesIDInput').val();
        var materiaID = \$('#materiaIDInput').val();
        var descripcion = \$('#descripcionInput').val();
        var fecha = \$('#fechaInput').val();
        
        console.log('Tipo:', tipoActividad, 'Materia:', materiaID);

        if (!tipoActividad) {
            alert("Por favor selecciona el tipo de actividad");
            \$('#tiposactividadesIDInput').focus();
            return false;
        }

        if (!materiaID) {
            alert("No hay materias disponibles para tu carrera");
            return false;
        }

        \$.ajax({
            type: 'POST',
            url: '{$baseUrl}?page=academicas',
            data: {
                materiaID: materiaID,
                tiposactividadesID: tipoActividad,
                usuariosID: userId, 
                tipoActividad: tipoActividad,
                descripcion: descripcion,
                fecha: fecha
            },
            success: function (response) {
                \$('#modalActividad').modal('hide');
                
                var alert = \$('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                    '<i class="bi bi-check-circle me-2"></i>Actividad guardada exitosamente' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>');
                
                \$('.main-content .container').prepend(alert);
                
                setTimeout(function() {
                    location.reload(true);
                }, 1500);
            },
            error: function (error) {
                console.error(error);
                alert("Hubo un error al guardar la actividad");
            }
        });
        
        return false;
    });
});
</script>
JAVASCRIPT;

$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/dashboard.php';
?>
