<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mi Agenda</title>
    <link href="<?= BASE_URL ?>Estilo2.css" rel="stylesheet">
</head>
<body>
    <form action="<?= BASE_URL ?>?page=login" method="post">
        <h2>Iniciar sesión</h2>

        <?php if (!empty($mensajeError)): ?>
            <div class='alert alert-danger'><?= $mensajeError ?></div>
        <?php endif; ?>

        <label for="nombre"><p>Usuario:</p></label>
        <input type="text" name="nombre" required>           
       
        <label for="contrasenas"><p>Contraseña:</p></label>
        <input type="password" name="contrasenas" required>
        
        <input type="submit" value="Iniciar sesión" class="btn btn-1">
     
        <div class="text-center">
            <label for="registrarse"><p>No tienes cuenta?</p></label>
            <a href="<?= BASE_URL ?>?page=registrarse" class="btn btn-link">Registrate</a>
        </div>
    </form>
</body>
</html>
