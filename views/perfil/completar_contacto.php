<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Perfil - Información de Contacto</title>
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
            <h5 class="mb-0">📍 Completa tu perfil</h5>
        </div>

        <h2>Información de Contacto</h2>
        <p class="subtitle">Ingresa tu dirección y datos de contacto</p>

        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($mensajeError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?page=guardar-perfil-contacto">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="codigo_postal" class="form-label">Código Postal *</label>
                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                           maxlength="5" pattern="[0-9]{5}" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="estado" class="form-label">Estado *</label>
                    <input type="text" class="form-control" id="estado" name="estado" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="municipio" class="form-label">Municipio *</label>
                    <input type="text" class="form-control" id="municipio" name="municipio" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ciudad" class="form-label">Ciudad *</label>
                    <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="colonia" class="form-label">Colonia *</label>
                <input type="text" class="form-control" id="colonia" name="colonia" required>
            </div>

            <div class="mb-3">
                <label for="calle_principal" class="form-label">Calle Principal *</label>
                <input type="text" class="form-control" id="calle_principal" name="calle_principal" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="numero_exterior" class="form-label">Número Exterior *</label>
                    <input type="text" class="form-control" id="numero_exterior" name="numero_exterior" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="numero_interior" class="form-label">Número Interior</label>
                    <input type="text" class="form-control" id="numero_interior" name="numero_interior">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="primer_cruzamiento" class="form-label">Primer Cruzamiento</label>
                    <input type="text" class="form-control" id="primer_cruzamiento" name="primer_cruzamiento">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="segundo_cruzamiento" class="form-label">Segundo Cruzamiento</label>
                    <input type="text" class="form-control" id="segundo_cruzamiento" name="segundo_cruzamiento">
                </div>
            </div>

            <div class="mb-3">
                <label for="referencias" class="form-label">Referencias</label>
                <textarea class="form-control" id="referencias" name="referencias" rows="2"></textarea>
                <small class="text-muted">Puntos de referencia para encontrar tu domicilio</small>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Finalizar
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('codigo_postal').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>
