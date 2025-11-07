<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>js/session-guard.js"></script>
    <title>Actividades Académicas</title>
</head>
<body>

<!-- Botones actividades -->
<div class="container mt-4">
    <div class="mb-3">
        <button type="button" class="btn btn-primary" id="btnTareas" data-id="1">Tareas</button>
        <button type="button" class="btn btn-primary" id="btnProyectos" data-id="2">Proyectos</button>
        <button type="button" class="btn btn-primary" id="btnExamenes" data-id="3">Exámenes</button>
        <script>
            function confirmLogout() {
                var response = confirm("¿Estás seguro de que deseas cerrar sesión?");
                if (response) {
                    window.location.href = '<?= BASE_URL ?>?page=logout';
                }
            }
        </script>
        <a href="javascript:void(0);" onclick="confirmLogout()" class="btn btn-secondary">Cerrar Sesión</a>
    </div>

<!-- Tabla de Actividades Académicas -->
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Para esta semana</th>
                <th>Para la próxima semana</th>
                <th>Para el próximo mes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $index => $resultado): ?>
                <?php if (isset($resultado['actividades']) && count($resultado['actividades']) > 0): ?>
                    <?php foreach ($resultado['actividades'] as $actividad): ?>
                        <tr class="actividad-row" data-tipoactividad="<?= htmlspecialchars($tiposActividades[$actividad['tiposactividadesID']]) ?>">
                            <td><?= count($resultado['actividades']) ?></td>
                            <?php foreach (['Para esta semana', 'Para la próxima semana', 'Para el próximo mes'] as $periodo): ?>
                                <td>
                                    <?php if ($periodo === $index): ?>
                                        <ul>
                                            <li><strong>Materia:</strong> <?= htmlspecialchars($materias[$actividad['materiaID']]) ?></li>
                                            <li><strong>Descripción:</strong> <?= htmlspecialchars($actividad['descripcion']) ?></li>
                                            <li><strong>Fecha:</strong> <?= htmlspecialchars($actividad['fecha']->format('Y-m-d')) ?></li>
                                            <li><strong>Tipo actividad:</strong> <?= htmlspecialchars($tiposActividades[$actividad['tiposactividadesID']]) ?></li>
                                        </ul>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary edit-button">...</button>
                                <div class="action-buttons d-none">
                                    <form action="<?= BASE_URL ?>?page=eliminar-actividad" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_actividad" value="<?= $actividad['ID_actividadesacademicas'] ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro que desea eliminar esta actividad?');">Eliminar</button>
                                    </form>
                                    <form action="<?= BASE_URL ?>?page=editar-actividad" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_actividad" value="<?= $actividad['ID_actividadesacademicas'] ?>">
                                        <button type="submit" name="update" class="btn btn-sm btn-success">Editar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal para actividades -->
<div class="modal fade" id="modalActividad" tabindex="-1" role="dialog" aria-labelledby="modalActividadLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActividadLabel">Nueva Actividad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formModalActividad">
                    <input type="hidden" id="tiposactividadesIDInput" name="tiposactividadesID">
                    <div class="form-group">
                        <label for="materiaIDInput">Materia</label>
                        <select class="form-control" id="materiaIDInput" name="materiaID" required>
                            <?php
                                if (!empty($carrera_materias)) {
                                    foreach ($carrera_materias as $idMateria => $nombreMateria) {
                                        echo "<option value='$idMateria'>$nombreMateria</option>";
                                    }
                                } else {
                                    echo "<option>No hay materias disponibles para tu carrera</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descripcionInput">Descripción</label>
                        <input type="text" class="form-control" id="descripcionInput" name="descripcion" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaInput">Fecha</label>
                        <input type="date" class="form-control" id="fechaInput" name="fecha" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.edit-button').click(function() {
        $(this).closest('tr').find('input, select').removeAttr('disabled');
        $(this).hide();
        $(this).closest('tr').find('.action-buttons').removeClass('d-none');
    });

    function abrirModalParaNuevaActividad(tipoActividadTexto, idTipoActividad) {
        $('#modalActividadLabel').text(`Nueva ${tipoActividadTexto}`);
        $('#tiposactividadesIDInput').val(idTipoActividad);
        $('#modalActividad').modal('show');
    }

    function filterTableByTipoActividad(tipoActividad) {
        var tipoActividadNombre = {
            1: "Tareas",
            2: "Proyectos",
            3: "Exámenes"
        };

        var tipoActividadSeleccionada = tipoActividadNombre[tipoActividad];

        $('.actividad-row').each(function() {
            var filaTipoActividad = $(this).data('tipoactividad');
            if (filaTipoActividad === tipoActividadSeleccionada) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('#btnTareas').click(function() {
        filterTableByTipoActividad(1);
        abrirModalParaNuevaActividad('Tarea', 1);
    });
    
    $('#btnProyectos').click(function() {
        filterTableByTipoActividad(2);
        abrirModalParaNuevaActividad('Proyecto', 2);
    });
    
    $('#btnExamenes').click(function() {
        filterTableByTipoActividad(3);
        abrirModalParaNuevaActividad('Examen', 3);
    });

    $('#formModalActividad').submit(function (event) {
        event.preventDefault();

        var tipoActividad = $('#tiposactividadesIDInput').val();
        var materiaID = $('#materiaIDInput').val();
        var descripcion = $('#descripcionInput').val();
        var fecha = $('#fechaInput').val();

        $.ajax({
            type: 'POST',
            url: '<?= BASE_URL ?>?page=academicas',
            data: {
                materiaID: materiaID,
                tiposactividadesID: tipoActividad,
                usuariosID: userId, 
                tipoActividad: tipoActividad,
                descripcion: descripcion,
                fecha: fecha
            },
            success: function (response) {
                $('#modalActividad').modal('hide');
                alert("Actividad guardada exitosamente");
                location.reload(true);
            },
            error: function (error) {
                console.error(error);
                alert("Hubo un error al guardar la actividad");
            }
        });
    });
});
</script>

</body>
</html>
