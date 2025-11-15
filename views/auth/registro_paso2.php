<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Paso 2</title>
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
        .btn-primary, .btn-secondary {
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #6c757d;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <!-- Indicador de pasos -->
        <div class="step-indicator">
            <div>
                <div class="step completed">✓</div>
                <div class="step-label">Datos Personales</div>
            </div>
            <div>
                <div class="step active">2</div>
                <div class="step-label active">Contacto</div>
            </div>
            <div>
                <div class="step">3</div>
                <div class="step-label">Cuenta</div>
            </div>
        </div>

        <h2>Crear Cuenta - Información de Contacto</h2>
        <p class="subtitle">Paso 2 de 3: ¿Cómo podemos contactarte?</p>

        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($mensajeError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?page=registro-paso2">
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono *</label>
                <input type="tel" 
                       class="form-control" 
                       id="telefono" 
                       name="telefono"
                       value="<?= isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '' ?>"
                       placeholder="10 dígitos"
                       maxlength="10"
                       minlength="10"
                       pattern="[0-9]{10}"
                       required>
                <small class="text-muted">Formato: 10 dígitos numéricos</small>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico *</label>
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email"
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                       placeholder="ejemplo@correo.com"
                       required>
                <small class="text-muted">Usaremos este correo para enviarte recordatorios y notificaciones</small>
            </div>

            <?php if (isset($_SESSION['registro_paso1']['RFC'])): ?>
            <div class="mb-3">
                <label for="RFC" class="form-label">RFC (opcional para personal administrativo)</label>
                <input type="text" 
                       class="form-control" 
                       id="RFC" 
                       name="RFC"
                       value="<?= isset($_POST['RFC']) ? htmlspecialchars($_POST['RFC']) : '' ?>"
                       placeholder="GAPL891204AB1"
                       maxlength="13">
            </div>
            <?php endif; ?>

            <div class="d-flex gap-2 mt-4">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= BASE_URL ?>?page=registrarse'">
                    ← Atrás
                </button>
                <button type="submit" class="btn btn-primary flex-grow-1">
                    Siguiente →
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validar solo números en teléfono
        document.getElementById('telefono').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>
