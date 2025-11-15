<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Paso 3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 43px;
            cursor: pointer;
            color: #999;
        }
        .password-toggle:hover {
            color: #667eea;
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
                <div class="step completed">✓</div>
                <div class="step-label">Contacto</div>
            </div>
            <div>
                <div class="step active">3</div>
                <div class="step-label active">Cuenta</div>
            </div>
        </div>

        <h2>Crear Cuenta - Credenciales</h2>
        <p class="subtitle">Paso 3 de 3: Elige tu nombre de usuario y contraseña</p>

        <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($mensajeError) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?page=registro-paso3" id="formRegistro">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de Usuario *</label>
                <input type="text" 
                       class="form-control" 
                       id="nombre" 
                       name="nombre"
                       value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>"
                       placeholder="Mínimo 3 caracteres"
                       minlength="3"
                       required>
                <small class="text-muted">Este será tu nombre para iniciar sesión</small>
            </div>

            <div class="mb-3 position-relative">
                <label for="contrasena" class="form-label">Contraseña *</label>
                <input type="password" 
                       class="form-control" 
                       id="contrasena" 
                       name="contrasena"
                       placeholder="Mínimo 6 caracteres"
                       minlength="6"
                       required>
                <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                <div class="password-strength" id="passwordStrength"></div>
                <small class="text-muted">Usa al menos 6 caracteres con letras y números</small>
            </div>

            <div class="mb-3 position-relative">
                <label for="confirmar_contrasena" class="form-label">Confirmar Contraseña *</label>
                <input type="password" 
                       class="form-control" 
                       id="confirmar_contrasena" 
                       name="confirmar_contrasena"
                       placeholder="Repite tu contraseña"
                       minlength="6"
                       required>
                <i class="bi bi-eye-slash password-toggle" id="togglePassword2" style="top: 43px;"></i>
                <small class="text-muted" id="passwordMatch"></small>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= BASE_URL ?>?page=registro-paso2'">
                    ← Atrás
                </button>
                <button type="submit" class="btn btn-primary flex-grow-1" id="btnSubmit">
                    Crear Cuenta
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('contrasena');
            const icon = this;
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });

        document.getElementById('togglePassword2').addEventListener('click', function() {
            const password = document.getElementById('confirmar_contrasena');
            const icon = this;
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });

        // Password strength indicator
        document.getElementById('contrasena').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            let strength = 0;

            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            const colors = ['#dc3545', '#fd7e14', '#ffc107', '#28a745', '#198754'];
            const widths = ['20%', '40%', '60%', '80%', '100%'];
            
            strengthBar.style.backgroundColor = colors[strength - 1] || '#e0e0e0';
            strengthBar.style.width = widths[strength - 1] || '0%';
        });

        // Password match validator
        document.getElementById('confirmar_contrasena').addEventListener('input', function() {
            const password = document.getElementById('contrasena').value;
            const confirm = this.value;
            const matchText = document.getElementById('passwordMatch');
            const submitBtn = document.getElementById('btnSubmit');

            if (confirm === '') {
                matchText.textContent = '';
                matchText.className = 'text-muted';
                submitBtn.disabled = false;
            } else if (password === confirm) {
                matchText.textContent = '✓ Las contraseñas coinciden';
                matchText.className = 'text-success';
                submitBtn.disabled = false;
            } else {
                matchText.textContent = '✗ Las contraseñas no coinciden';
                matchText.className = 'text-danger';
                submitBtn.disabled = true;
            }
        });

        // Form validation
        document.getElementById('formRegistro').addEventListener('submit', function(e) {
            const password = document.getElementById('contrasena').value;
            const confirm = document.getElementById('confirmar_contrasena').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
            }
        });
    </script>
</body>
</html>
