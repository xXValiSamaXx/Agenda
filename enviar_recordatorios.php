<?php
/**
 * Script para enviar recordatorios de actividades académicas
 * Este script debe ejecutarse diariamente (mediante cron o tarea programada)
 * 
 * Envía correos a los usuarios que tienen actividades próximas a vencer
 * (mañana o pasado mañana)
 */

// Cargar configuración
require_once __DIR__ . '/config/config.php';

// Cargar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/models/PHPMailer/src/Exception.php';
require __DIR__ . '/models/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/models/PHPMailer/src/SMTP.php';

// Conectar a la base de datos
$db = new Database();
$conn = $db->getConnection();

// Configurar las fechas para buscar actividades
$hoy = date('Y-m-d');
$manana = date('Y-m-d', strtotime('+1 day'));
$pasadoManana = date('Y-m-d', strtotime('+2 days'));

echo "=== SISTEMA DE RECORDATORIOS DE ACTIVIDADES ===\n";
echo "Fecha de ejecución: " . date('Y-m-d H:i:s') . "\n";
echo "Buscando actividades para: $manana y $pasadoManana\n\n";

/**
 * Consulta SQL para obtener actividades próximas a vencer
 * Busca actividades que vencen mañana o pasado mañana
 * Y que aún no han sido calificadas
 */
$query = "
    SELECT 
        aa.ID_actividadesacademicas,
        aa.usuariosID,
        aa.tiposactividadesID,
        aa.materiaID,
        aa.descripcion,
        aa.fecha,
        aa.calificacion,
        u.nombre AS usuario_nombre,
        u.email AS usuario_email,
        ta.tipo AS tipo_actividad,
        m.nombre AS materia_nombre
    FROM ActividadesAcademicas aa
    INNER JOIN Usuarios u ON aa.usuariosID = u.ID_usuarios
    LEFT JOIN TiposActividades ta ON aa.tiposactividadesID = ta.ID_tiposactividades
    LEFT JOIN Materias m ON aa.materiaID = m.ID_materias
    WHERE aa.fecha IN (?, ?)
    AND aa.calificacion IS NULL
    ORDER BY aa.fecha ASC, u.nombre ASC
";

$params = array($manana, $pasadoManana);
$stmt = sqlsrv_prepare($conn, $query, $params);

if ($stmt === false) {
    echo "❌ Error al preparar la consulta: " . print_r(sqlsrv_errors(), true) . "\n";
    exit(1);
}

if (!sqlsrv_execute($stmt)) {
    echo "❌ Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true) . "\n";
    exit(1);
}

// Agrupar actividades por usuario
$actividadesPorUsuario = [];

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $userId = $row['usuariosID'];
    
    if (!isset($actividadesPorUsuario[$userId])) {
        $actividadesPorUsuario[$userId] = [
            'nombre' => $row['usuario_nombre'],
            'email' => $row['usuario_email'],
            'actividades' => []
        ];
    }
    
    // Convertir fecha a formato legible
    if ($row['fecha'] instanceof DateTime) {
        $fechaFormateada = $row['fecha']->format('d/m/Y');
        $fechaCompleta = $row['fecha']->format('Y-m-d');
    } else {
        $fechaFormateada = date('d/m/Y', strtotime($row['fecha']));
        $fechaCompleta = $row['fecha'];
    }
    
    // Calcular días restantes
    $diasRestantes = (strtotime($fechaCompleta) - strtotime($hoy)) / (60 * 60 * 24);
    
    $actividadesPorUsuario[$userId]['actividades'][] = [
        'id' => $row['ID_actividadesacademicas'],
        'tipo' => $row['tipo_actividad'] ?? 'Actividad',
        'materia' => $row['materia_nombre'] ?? 'Sin materia',
        'descripcion' => $row['descripcion'],
        'fecha' => $fechaFormateada,
        'dias_restantes' => round($diasRestantes)
    ];
}

echo "Total de usuarios con actividades próximas: " . count($actividadesPorUsuario) . "\n\n";

// Enviar correos
$exitosos = 0;
$fallidos = 0;

foreach ($actividadesPorUsuario as $userId => $datosUsuario) {
    echo "📧 Enviando recordatorio a: {$datosUsuario['nombre']} ({$datosUsuario['email']})\n";
    echo "   Actividades a recordar: " . count($datosUsuario['actividades']) . "\n";
    
    // Verificar si el usuario tiene email
    if (empty($datosUsuario['email']) || !filter_var($datosUsuario['email'], FILTER_VALIDATE_EMAIL)) {
        echo "   ⚠️  Email inválido o no configurado\n\n";
        $fallidos++;
        continue;
    }
    
    // Crear instancia de PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_SMTP_SECURE;
        $mail->Port       = MAIL_PORT;
        $mail->CharSet    = 'UTF-8';
        
        // Remitente
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        
        // Destinatario
        $mail->addAddress($datosUsuario['email'], $datosUsuario['nombre']);
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = '🔔 Recordatorio de Actividades - Mi Agenda';
        
        // Construir el cuerpo del correo
        $htmlBody = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .activity-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .activity-card.urgent {
            border-left-color: #dc3545;
            background: #fff5f5;
        }
        .activity-title {
            font-weight: bold;
            color: #667eea;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .activity-card.urgent .activity-title {
            color: #dc3545;
        }
        .activity-detail {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }
        .activity-date {
            font-weight: bold;
            color: #333;
        }
        .activity-card.urgent .activity-date {
            color: #dc3545;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
            font-weight: bold;
        }
        .icon {
            font-size: 40px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">📚</div>
            <h1>Recordatorio de Actividades</h1>
        </div>
        <div class="content">
            <p class="greeting">¡Hola, ' . htmlspecialchars($datosUsuario['nombre']) . '! 👋</p>
            <p>Te enviamos este recordatorio sobre tus actividades académicas próximas a vencer:</p>
            ';
        
        // Agregar cada actividad
        foreach ($datosUsuario['actividades'] as $actividad) {
            $esUrgente = $actividad['dias_restantes'] <= 1;
            $urgenciaClass = $esUrgente ? 'urgent' : '';
            $iconoUrgencia = $esUrgente ? '🔴' : '⏰';
            $textoUrgencia = $esUrgente ? 'MAÑANA' : 'En ' . $actividad['dias_restantes'] . ' días';
            
            $htmlBody .= '
            <div class="activity-card ' . $urgenciaClass . '">
                <div class="activity-title">' . $iconoUrgencia . ' ' . htmlspecialchars($actividad['tipo']) . '</div>
                <div class="activity-detail">📖 <strong>Materia:</strong> ' . htmlspecialchars($actividad['materia']) . '</div>
                <div class="activity-detail">📝 <strong>Descripción:</strong> ' . htmlspecialchars($actividad['descripcion']) . '</div>
                <div class="activity-detail activity-date">📅 <strong>Fecha de entrega:</strong> ' . $actividad['fecha'] . ' (' . $textoUrgencia . ')</div>
            </div>
            ';
        }
        
        $htmlBody .= '
            <p style="margin-top: 30px;">No olvides completar tus actividades a tiempo para mantener tu rendimiento académico. 💪</p>
            
            <center>
                <a href="' . FULL_URL . '?page=actividades" class="btn">Ver Mis Actividades</a>
            </center>
        </div>
        <div class="footer">
            <p>Este es un correo automático enviado por <strong>Mi Agenda</strong></p>
            <p>Si tienes alguna pregunta, contacta con soporte.</p>
            <p style="margin-top: 10px;">© ' . date('Y') . ' Mi Agenda. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
        ';
        
        $mail->Body = $htmlBody;
        
        // Versión en texto plano
        $textBody = "Hola, {$datosUsuario['nombre']}!\n\n";
        $textBody .= "Te enviamos este recordatorio sobre tus actividades académicas próximas a vencer:\n\n";
        
        foreach ($datosUsuario['actividades'] as $actividad) {
            $textoUrgencia = $actividad['dias_restantes'] <= 1 ? 'MAÑANA' : 'En ' . $actividad['dias_restantes'] . ' días';
            $textBody .= "• {$actividad['tipo']} - {$actividad['materia']}\n";
            $textBody .= "  Descripción: {$actividad['descripcion']}\n";
            $textBody .= "  Fecha: {$actividad['fecha']} ({$textoUrgencia})\n\n";
        }
        
        $textBody .= "No olvides completar tus actividades a tiempo.\n\n";
        $textBody .= "Accede a tu agenda: " . FULL_URL . "?page=actividades\n\n";
        $textBody .= "---\n";
        $textBody .= "Este es un correo automático de Mi Agenda";
        
        $mail->AltBody = $textBody;
        
        // Enviar correo
        $mail->send();
        echo "   ✅ Correo enviado exitosamente\n\n";
        $exitosos++;
        
    } catch (Exception $e) {
        echo "   ❌ Error al enviar correo: {$mail->ErrorInfo}\n\n";
        $fallidos++;
    }
    
    // Pequeña pausa para no saturar el servidor de correo
    usleep(500000); // 0.5 segundos
}

// Resumen final
echo "\n=== RESUMEN DE EJECUCIÓN ===\n";
echo "✅ Correos enviados exitosamente: $exitosos\n";
echo "❌ Correos fallidos: $fallidos\n";
echo "📊 Total procesado: " . ($exitosos + $fallidos) . "\n";
echo "🕐 Hora de finalización: " . date('Y-m-d H:i:s') . "\n";

// Cerrar conexión
sqlsrv_close($conn);

// Código de salida
exit($fallidos > 0 ? 1 : 0);
?>
