<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>js/session-guard.js"></script>
    <title>Gestión de Materias</title>
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Gestión de Materias</h2>
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMateriaModal">Agregar Materia</button>
        <a href="<?= BASE_URL ?>?page=admin" class="btn btn-secondary">Volver a Administración</a>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="input-group">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar materia...">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" id="filterButton" type="button">
                    <img src="<?= BASE_URL ?>Imagenes/filtro.png" alt="Buscar" style="width: 20px; height: 20px;"> 
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de Materias -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Período</th>
                    <th>Carrera</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($materias as $materia): ?>
                <?php
                    $esReticula = "generica";
                    foreach ($carrera_materia as $cm) {
                        if ($cm['materiaid'] == $materia['ID_materia']) {
                            $esReticula = $cm['es_reticula'] ? "reticula" : "generica";
                            break;
                        }
                    }
                ?>
                <form action="<?= BASE_URL ?>?page=materias" method="post">
                    <tr data-es-reticula="<?= $esReticula ?>">
                        <td><input type="hidden" name="ID_materia" value="<?= $materia['ID_materia'] ?>"><?= $materia['ID_materia'] ?></td>
                        <td>
                            <input type="text" class="form-control" name="nombre" value="<?= $materia['nombre'] ?>" pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required disabled>
                        </td>
                        <td>
                            <select class="form-control" name="periodoId" disabled>
                                <?php foreach($periodos as $periodo): ?>
                                    <option value="<?= $periodo['ID_periodo'] ?>" <?= $periodo['ID_periodo'] == $materia['periodoid'] ? 'selected' : '' ?>><?= $periodo['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="carreraId" disabled>
                                <?php foreach($carreras as $carrera): ?>
                                    <option value="<?= $carrera['ID_carrera'] ?>" <?= $carrera['ID_carrera'] == $materia['carreraid'] ? 'selected' : '' ?>><?= $carrera['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary edit-button">Editar</button>
                            <div class="action-buttons d-none">
                                <button type="submit" name="update" class="btn btn-sm btn-success">Guardar</button>
                                <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro que desea eliminar esta materia?');">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                </form>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para añadir nueva materia -->
    <div class="modal fade" id="addMateriaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Nueva Materia</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="<?= BASE_URL ?>?page=materias" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombreMateria">Nombre de la Materia</label>
                            <input type="text" class="form-control" id="nombreMateria" name="nombre" pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required>
                        </div>
                        <div class="form-group">
                            <label for="periodoId">Período</label>
                            <select name="periodoId" id="periodoId" class="form-control" required>
                                <?php foreach($periodos as $periodo): ?>
                                    <option value="<?= $periodo['ID_periodo'] ?>"><?= $periodo['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="carreraId">Carrera</label>
                            <select class="form-control" id="carreraId" name="carreraId" required>
                                <?php foreach($carreras as $carrera): ?>
                                    <option value="<?= $carrera['ID_carrera'] ?>"><?= $carrera['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tipo de Materia</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="es_reticula" id="reticula" value="1" required>
                                    <label class="form-check-label" for="reticula">Reticula</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="es_reticula" id="generica" value="0" required>
                                    <label class="form-check-label" for="generica">Generica</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="add" class="btn btn-primary">Añadir Materia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de filtros -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filtros de Búsqueda</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="filterType">Tipo de Filtro:</label>
                        <select class="form-control" id="filterType">
                            <option value="all">Todos</option>
                            <option value="generica">Genérica</option>
                            <option value="reticula">Reticula</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="aplicarFiltro()">Aplicar Filtros</button>
                </div>
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

    $('#filterButton').click(function() {
        $('#filterModal').modal('show');
    });
});

document.getElementById('searchInput').addEventListener('keyup', function(event) {
    var searchQuery = event.target.value.toLowerCase();
    var allRows = document.querySelectorAll('.table tbody tr');
    allRows.forEach(function(row) {
        var nombreMateria = row.querySelector('td:nth-child(2) input').value.toLowerCase();
        row.style.display = nombreMateria.includes(searchQuery) ? '' : 'none';
    });
});

function aplicarFiltro() {
    var selectedOption = document.getElementById("filterType").value;
    var searchQuery = document.getElementById('searchInput').value.toLowerCase();
    var tableRows = document.querySelectorAll('.table tbody tr');

    tableRows.forEach(function(row) {
        var nombreMateria = row.querySelector('td:nth-child(2) input').value.toLowerCase();
        var esReticula = row.getAttribute("data-es-reticula");
        var showRow = (selectedOption === "all" || selectedOption === esReticula) && nombreMateria.includes(searchQuery);
        row.style.display = showRow ? "" : "none";
    });

    $('#filterModal').modal('hide');
}

document.getElementById('searchInput').addEventListener('keyup', aplicarFiltro);
</script>

</body>
</html>
