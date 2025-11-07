<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mi Agenda</title>
    <link href="<?= BASE_URL ?>Estilo2.css" rel="stylesheet">
    <script>
        // Script de diagnóstico
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.querySelector('input[type="submit"]');
            
            console.log('Formulario encontrado:', form);
            console.log('Botón submit encontrado:', submitBtn);
            console.log('Action del formulario:', form ? form.action : 'No encontrado');
            console.log('Method del formulario:', form ? form.method : 'No encontrado');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Formulario enviándose...');
                    console.log('Usuario:', document.querySelector('input[name="nombre"]').value);
                    console.log('Contraseña presente:', document.querySelector('input[name="contrasenas"]').value ? 'Sí' : 'No');
                    
                    // NO prevenir el envío, solo loguear
                    // e.preventDefault();
                });
            }
            
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    console.log('Click en botón submit detectado');
                });
            }
        });
    </script>
</head>
<body>
    <form action="<?= BASE_URL ?>?page=login" method="post" id="loginForm">
        <h2>Iniciar sesión</h2>

        <?php if (!empty($mensajeError)): ?>
            <div class='alert alert-danger' style='background: #f8d7da; padding: 10px; margin-bottom: 15px; border-radius: 5px; color: #721c24;'>
                <?= $mensajeError ?>
            </div>
        <?php endif; ?>

        <label for="nombre"><p>Usuario:</p></label>
        <input type="text" name="nombre" id="nombre" required>           
       
        <label for="contrasenas"><p>Contraseña:</p></label>
        <input type="password" name="contrasenas" id="contrasenas" required>
        
        <input type="submit" value="Iniciar sesión" class="btn btn-1">
     
        <div class="text-center">
            <label for="registrarse"><p>No tienes cuenta?</p></label>
            <a href="<?= BASE_URL ?>?page=registrarse" class="btn btn-link">Registrate</a>
        </div>
    </form>
    
    <!-- Información de debug -->
    <div style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; font-size: 12px; max-width: 300px;">
        <strong>Debug Info:</strong><br>
        BASE_URL: <?= BASE_URL ?><br>
        Action: <?= BASE_URL ?>?page=login<br>
        Method: POST<br>
        <em>Abre la consola del navegador (F12) para más información</em>
    </div>
</body>
</html>
