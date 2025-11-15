<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Académica</title>
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
        .registro-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 600px;
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
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
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
    <div class="registro-container">
        <div class="welcome-badge">
            <h5 class="mb-0">🎓 ¡Último paso!</h5>
        </div>

        <h2>Información Académica</h2>
        <p class="subtitle">Completa tu perfil estudiantil</p>

        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($mensajeError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?page=guardar-informacion-academica">
            <!-- Debug temporal -->
            <?php if (defined('DEBUG') || true): ?>
                <div class="alert alert-info" style="font-size: 12px;">
                    <strong>Debug:</strong> Total carreras: <?= count($carreras ?? []) ?>
                    <?php if (empty($carreras)): ?>
                        <br>⚠️ Array de carreras está vacío
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="mb-3">
                <label for="carrera" class="form-label">Carrera *</label>
                <select class="form-select" id="carrera" name="carrera" required>
                    <option value="">Selecciona tu carrera</option>
                    <?php foreach ($carreras as $carrera): ?>
                        <option value="<?= $carrera['ID_carrera'] ?>">
                            <?= htmlspecialchars($carrera['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="semestre" class="form-label">Semestre *</label>
                    <select class="form-select" id="semestre" name="semestre" required>
                        <option value="">Seleccionar</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?>° Semestre</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="grupo" class="form-label">Grupo *</label>
                    <input type="text" 
                           class="form-control" 
                           id="grupo" 
                           name="grupo"
                           placeholder="Ej: A, B, C"
                           maxlength="5"
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label for="turno" class="form-label">Turno *</label>
                <select class="form-select" id="turno" name="turno" required>
                    <option value="">Seleccionar turno</option>
                    <option value="Matutino">Matutino</option>
                    <option value="Vespertino">Vespertino</option>
                    <option value="Nocturno">Nocturno</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                Finalizar Registro
            </button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Al completar el registro, podrás acceder a todas las funcionalidades</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
