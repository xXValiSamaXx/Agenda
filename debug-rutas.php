<?php
/**
 * Archivo de debug para verificar rutas
 * Accede a este archivo desde: http://tu-servidor/Agenda/debug-rutas.php
 * O en producción: http://18.215.175.210/Agenda/debug-rutas.php
 */

require_once __DIR__ . '/config/config.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - Rutas de la Aplicación</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            line-height: 1.6;
        }
        h1 {
            color: #569cd6;
            border-bottom: 2px solid #569cd6;
            padding-bottom: 10px;
        }
        h2 {
            color: #4ec9b0;
            margin-top: 30px;
        }
        .info {
            background: #2d2d30;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 3px solid #007acc;
        }
        .variable {
            color: #9cdcfe;
            font-weight: bold;
        }
        .value {
            color: #ce9178;
        }
        .success {
            color: #4ec9b0;
        }
        .warning {
            color: #dcdcaa;
        }
        .error {
            color: #f48771;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #3e3e42;
        }
        th {
            background: #007acc;
            color: white;
        }
        .test-images {
            margin-top: 30px;
        }
        img {
            max-width: 200px;
            margin: 10px;
            border: 2px solid #007acc;
        }
    </style>
</head>
<body>
    <h1>🔍 Debug - Configuración de Rutas</h1>
    
    <div class="info">
        <p><strong>Fecha/Hora:</strong> <?= date('Y-m-d H:i:s') ?></p>
        <p><strong>Servidor:</strong> <?= $_SERVER['HTTP_HOST'] ?></p>
        <p><strong>Protocolo:</strong> <?= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'HTTPS' : 'HTTP' ?></p>
    </div>

    <h2>📂 Rutas de la Aplicación</h2>
    <div class="info">
        <p><span class="variable">BASE_URL:</span> <span class="value"><?= BASE_URL ?></span></p>
        <p><span class="variable">FULL_URL:</span> <span class="value"><?= FULL_URL ?></span></p>
        <p><span class="variable">BASE_PATH:</span> <span class="value"><?= BASE_PATH ?></span></p>
    </div>

    <h2>🌐 Variables del Servidor</h2>
    <table>
        <tr>
            <th>Variable</th>
            <th>Valor</th>
        </tr>
        <tr>
            <td class="variable">HTTP_HOST</td>
            <td class="value"><?= $_SERVER['HTTP_HOST'] ?></td>
        </tr>
        <tr>
            <td class="variable">SERVER_NAME</td>
            <td class="value"><?= $_SERVER['SERVER_NAME'] ?></td>
        </tr>
        <tr>
            <td class="variable">SCRIPT_NAME</td>
            <td class="value"><?= $_SERVER['SCRIPT_NAME'] ?></td>
        </tr>
        <tr>
            <td class="variable">REQUEST_URI</td>
            <td class="value"><?= $_SERVER['REQUEST_URI'] ?></td>
        </tr>
        <tr>
            <td class="variable">DOCUMENT_ROOT</td>
            <td class="value"><?= $_SERVER['DOCUMENT_ROOT'] ?></td>
        </tr>
    </table>

    <h2>🔗 URLs Generadas</h2>
    <div class="info">
        <p><strong>Página de Login:</strong> <a href="<?= BASE_URL ?>?page=login" class="success"><?= BASE_URL ?>?page=login</a></p>
        <p><strong>Página Home:</strong> <a href="<?= BASE_URL ?>?page=home" class="success"><?= BASE_URL ?>?page=home</a></p>
        <p><strong>Logout:</strong> <a href="<?= BASE_URL ?>?page=logout" class="warning"><?= BASE_URL ?>?page=logout</a></p>
    </div>

    <h2>🖼️ Test de Carga de Imágenes</h2>
    <div class="info">
        <p>Intentando cargar imagen de fondo:</p>
        <p><span class="variable">Ruta:</span> <span class="value"><?= BASE_URL ?>Imagenes/bg.jpg</span></p>
        
        <div class="test-images">
            <p><strong>Imagen con BASE_URL:</strong></p>
            <img src="<?= BASE_URL ?>Imagenes/bg.jpg" alt="Test BG" onerror="this.style.border='2px solid red'; this.alt='❌ Error al cargar';">
        </div>
    </div>

    <h2>✅ Verificación</h2>
    <div class="info">
        <?php if (file_exists(BASE_PATH . '/Imagenes/bg.jpg')): ?>
            <p class="success">✅ El archivo bg.jpg existe en el servidor</p>
            <p><span class="variable">Ruta física:</span> <span class="value"><?= BASE_PATH . '/Imagenes/bg.jpg' ?></span></p>
        <?php else: ?>
            <p class="error">❌ El archivo bg.jpg NO existe en el servidor</p>
            <p><span class="variable">Buscado en:</span> <span class="value"><?= BASE_PATH . '/Imagenes/bg.jpg' ?></span></p>
        <?php endif; ?>

        <?php if (is_dir(BASE_PATH . '/Imagenes')): ?>
            <p class="success">✅ La carpeta Imagenes existe</p>
            <p><strong>Archivos en Imagenes/:</strong></p>
            <ul>
                <?php
                $imagenes = scandir(BASE_PATH . '/Imagenes');
                foreach ($imagenes as $archivo) {
                    if ($archivo != '.' && $archivo != '..') {
                        echo "<li class='value'>$archivo</li>";
                    }
                }
                ?>
            </ul>
        <?php else: ?>
            <p class="error">❌ La carpeta Imagenes NO existe</p>
        <?php endif; ?>
    </div>

    <h2>💡 Recomendaciones</h2>
    <div class="info">
        <p><strong>Si estás viendo esto en producción:</strong></p>
        <ul>
            <li>BASE_URL debe ser: <span class="success"><?= BASE_URL ?></span></li>
            <li>Todas las imágenes deben cargarse desde: <span class="success"><?= BASE_URL ?>Imagenes/nombre.jpg</span></li>
            <li>Los estilos CSS deben cargarse desde: <span class="success"><?= BASE_URL ?>css/archivo.css</span></li>
        </ul>
        
        <p class="warning"><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo (debug-rutas.php) en producción por seguridad.</p>
    </div>

</body>
</html>
