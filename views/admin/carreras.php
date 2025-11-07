<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta tags requeridas por Bootstrap -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <title>Gestión de Carreras</title>
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Gestión de Carreras</h2>
    
    <?php
    // Mostrar mensaje de sesión
    if (isset($_SESSION['mensaje'])) {
        $tipo = isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : 'success';
        $clase = ($tipo == 'success') ? 'alert-success' : 'alert-danger';
        echo "<div class='alert {$clase}'>{$_SESSION['mensaje']}</div>";
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
    }
    ?>
    
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCarreraModal">Agregar Carrera</button>
        <a href="<?= BASE_URL ?>?page=admin" class="btn btn-secondary">Volver a Administración</a>
    </div>

    <!-- Tabla de Carreras -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Perfil de Carrera</th>
                    <th>Duración</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($carreras as $carrera): ?>
                <form action="<?= BASE_URL ?>?page=actualizar-carrera" method="post">
                    <tr>
                        <td>
                            <input type="hidden" name="id" value="<?= $carrera['ID_carrera'] ?>">
                            <?= $carrera['ID_carrera']?>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($carrera['nombre']) ?>" pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="perfil_carrera" value="<?= htmlspecialchars($carrera['perfil_carrera']) ?>" pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="duracion" value="<?= htmlspecialchars($carrera['duracion']) ?>" pattern="^(?:1[0-8]|[7-9])$" required disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="descripcion" value="<?= htmlspecialchars($carrera['descripcion']) ?>" pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required disabled>
                        </td>
                        <td>
                            <!-- Botón Editar -->
                            <button type="button" class="btn btn-sm btn-primary edit-button">Editar</button>

                            <!-- Botones de acción, inicialmente ocultos -->
                            <div class="action-buttons d-none" style="min-width: 200px"> 
                                <button type="submit" name="update" class="btn btn-sm btn-success">Guardar</button>
                                <button type="button" class="btn btn-sm btn-danger delete-button" data-id="<?= $carrera['ID_carrera'] ?>" data-nombre="<?= htmlspecialchars($carrera['nombre']) ?>">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                </form>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para añadir nueva carrera -->
    <div class="modal fade" id="addCarreraModal" tabindex="-1" role="dialog" aria-labelledby="addCarreraModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCarreraModalLabel">Añadir Nueva Carrera</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= BASE_URL ?>?page=crear-carrera" method="POST">
                    <div class="modal-body">
                        <!-- Contenedor de mensajes -->
                        <div id="messageContainer" style="display: none;" class="alert"></div>

                        <div class="form-group">
                            <label for="nombreCarrera">Nombre de la Carrera</label>
                            <input type="text" class="form-control" id="nombreCarrera" name="nombre" pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required>
                            <small>Solo se permiten caracteres alfabéticos y espacios.</small>
                        </div>

                        <div class="form-group">
                            <label for="perfilCarrera">Perfil de Carrera</label>
                            <input type="text" class="form-control" id="perfilCarrera" name="perfil_carrera" pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required>
                            <small>Solo se permiten caracteres alfabéticos y espacios.</small>
                        </div>

                        <div class="form-group">
                            <label for="tipoCarrera">Tipo de Carrera</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="escolarizada" name="tipoCarrera" value="escolarizada" onclick="mostrarDuracion()">
                                <label class="form-check-label" for="escolarizada">Escolarizada</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="mixta" name="tipoCarrera" value="mixta" onclick="mostrarDuracion()">
                                <label class="form-check-label" for="mixta">Mixta</label>
                            </div>
                        </div>

                        <div class="form-group" id="seccionDuracion" style="display:none;">
                            <label for="duracionCarrera">Duración (semestres)</label>
                            <select class="form-control" id="duracionCarrera" name="duracion" required>
                                <?php
                                    // Generar opciones numéricas del 7 al 12 (por defecto para escolarizada)
                                    for ($i = 7; $i <= 12; $i++) {
                                        $selected = ($i == 9) ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="descripcionCarrera">Descripción</label>
                            <input type="text" class="form-control" id="descripcionCarrera" name="descripcion" pattern="[A-Za-zÁÉÍÓÚáéíóúüÜñÑ\s]+" required>
                            <small>Solo se permiten caracteres alfabéticos y espacios.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="add" class="btn btn-primary">Añadir Carrera</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal para confirmar eliminación -->
    <div class="modal fade" id="deleteCarreraModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="<?= BASE_URL ?>?page=eliminar-carrera" method="POST">
                    <div class="modal-body">
                        <p>¿Está seguro de que desea eliminar la carrera <strong id="deleteNombreCarrera"></strong>?</p>
                        <p class="text-danger">Esta acción no se puede deshacer. Los estudiantes asociados a esta carrera tendrán su campo de carrera establecido en NULL.</p>
                        <input type="hidden" name="id" id="deleteCarreraId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Vista de agregar -->
<script>
    function mostrarDuracion() {
        var seccionDuracion = document.getElementById("seccionDuracion");
        var escolarizadaRadio = document.getElementById("escolarizada");
        var mixtaRadio = document.getElementById("mixta");
        var duracionCarrera = document.getElementById("duracionCarrera");

        if (escolarizadaRadio.checked) {
            duracionCarrera.innerHTML = "";
            for (var i = 7; i <= 12; i++) {
                var selected = (i == 9) ? 'selected' : '';
                duracionCarrera.innerHTML += "<option value='" + i + "' " + selected + ">" + i + "</option>";
            }
        } else if (mixtaRadio.checked) {
            duracionCarrera.innerHTML = "";
            for (var i = 12; i <= 18; i++) {
                var selected = (i == 14) ? 'selected' : '';
                duracionCarrera.innerHTML += "<option value='" + i + "' " + selected + ">" + i + "</option>";
            }
        }

        seccionDuracion.style.display = "block";
    }
</script>

<!-- Botones Tabla -->
<script>
    $(document).ready(function() {
        // Editar carrera
        $('.edit-button').click(function() {
            // Habilitar los campos de entrada en la fila
            $(this).closest('tr').find('input, select').removeAttr('disabled');

            // Ocultar el botón de editar
            $(this).hide();

            // Mostrar los botones de acción
            $(this).closest('tr').find('.action-buttons').removeClass('d-none');
        });
        
        // Eliminar carrera
        $('.delete-button').click(function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            
            $('#deleteCarreraId').val(id);
            $('#deleteNombreCarrera').text(nombre);
            $('#deleteCarreraModal').modal('show');
        });
    });
</script>

</body>
</html>
