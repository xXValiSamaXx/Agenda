<!DOCTYPE html>
<html>
<head>
    <title>Registrarse</title>
    <link href="<?= BASE_URL ?>Estilo2.css" rel="stylesheet"/>
</head>
<body>
    <form action="<?= BASE_URL ?>?page=registrarse" method="post">
        <h2>Registrarse</h2>

        <?php if (!empty($mensajeError)): ?>
            <div class='alert alert-danger'><?= $mensajeError ?></div>
        <?php endif; ?>

        <label for="nombre"><p>Nombre Usuario:</p></label>
        <input type="text" name="nombre" class="form-control" required>

        <label for="contrasena"><p>Contraseña:</p></label>
        <input type="password" name="contrasena" class="form-control" required>

        <input type="submit" value="Registrarse" class="btn btn-1">
    </form>
</body>
</html>
