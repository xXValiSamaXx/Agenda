<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Paso 1</title>
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
        .registro-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e0e0e0;
            z-index: 0;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #999;
            position: relative;
            z-index: 1;
        }
        .step.active {
            background: #667eea;
            color: white;
        }
        .step.completed {
            background: #28a745;
            color: white;
        }
        .step-label {
            text-align: center;
            font-size: 12px;
            margin-top: 8px;
            color: #666;
        }
        .step-label.active {
            color: #667eea;
            font-weight: bold;
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
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <!-- Indicador de pasos -->
        <div class="step-indicator">
            <div>
                <div class="step active">1</div>
                <div class="step-label active">Datos Personales</div>
            </div>
            <div>
                <div class="step">2</div>
                <div class="step-label">Contacto</div>
            </div>
            <div>
                <div class="step">3</div>
                <div class="step-label">Cuenta</div>
            </div>
        </div>

        <h2>Crear Cuenta - Datos Personales</h2>
        <p class="subtitle">Paso 1 de 3: Ingresa tu información básica</p>

        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($mensajeError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?page=registro-paso1">
            <div class="mb-3">
                <label for="nombres" class="form-label">Nombre(s) *</label>
                <input type="text" 
                       class="form-control" 
                       id="nombres" 
                       name="nombres" 
                       value="<?= isset($_POST['nombres']) ? htmlspecialchars($_POST['nombres']) : '' ?>"
                       placeholder="Ej: Juan Carlos"
                       required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="primerapellido" class="form-label">Primer Apellido *</label>
                    <input type="text" 
                           class="form-control" 
                           id="primerapellido" 
                           name="primerapellido"
                           value="<?= isset($_POST['primerapellido']) ? htmlspecialchars($_POST['primerapellido']) : '' ?>"
                           placeholder="Ej: García"
                           required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="segundoapellido" class="form-label">Segundo Apellido</label>
                    <input type="text" 
                           class="form-control" 
                           id="segundoapellido" 
                           name="segundoapellido"
                           value="<?= isset($_POST['segundoapellido']) ? htmlspecialchars($_POST['segundoapellido']) : '' ?>"
                           placeholder="Ej: López">
                </div>
            </div>

            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento *</label>
                <input type="date" 
                       class="form-control" 
                       id="fecha_nacimiento" 
                       name="fecha_nacimiento"
                       value="<?= isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '' ?>"
                       max="<?= date('Y-m-d', strtotime('-18 years')) ?>"
                       required>
                <small class="text-muted">Debes tener al menos 18 años</small>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Siguiente →
            </button>
        </form>

        <div class="back-link">
            <a href="<?= BASE_URL ?>?page=login">← Ya tengo cuenta, iniciar sesión</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
