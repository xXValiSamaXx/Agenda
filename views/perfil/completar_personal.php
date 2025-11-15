<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Perfil - Información Personal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('<?= BASE_URL ?>Imagenes/bg.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(102, 126, 234, 0.3);
            backdrop-filter: blur(5px);
            z-index: -1;
        }
        .perfil-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 700px;
            width: 100%;
        }
        h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .welcome-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="perfil-container">
        <div class="welcome-badge">
            <h5 class="mb-0">👤 Completa tu perfil</h5>
        </div>

        <h2>Información Personal</h2>
        <p class="subtitle">Por favor, completa tus datos personales para continuar</p>

        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($mensajeError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?page=guardar-perfil-personal">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="nombres" class="form-label">Nombre(s) *</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="primerapellido" class="form-label">Primer Apellido *</label>
                    <input type="text" class="form-control" id="primerapellido" name="primerapellido" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="segundoapellido" class="form-label">Segundo Apellido</label>
                    <input type="text" class="form-control" id="segundoapellido" name="segundoapellido">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento *</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Teléfono *</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                           maxlength="10" minlength="10" pattern="[0-9]{10}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico *</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="RFC" class="form-label">RFC</label>
                <input type="text" class="form-control" id="RFC" name="RFC" maxlength="13">
                <small class="text-muted">Opcional</small>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Continuar →
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('telefono').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>
