<?php
// Cargar configuración y PHPMailer correctamente
require_once __DIR__ . '/config/config.php';

require_once MODELS_PATH . 'PHPMailer/src/PHPMailer.php';
require_once MODELS_PATH . 'PHPMailer/src/SMTP.php';
require_once MODELS_PATH . 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoDesdeFormulario() {
    // Comprobar si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recoger datos del formulario (sanitizar)
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $nombre = htmlspecialchars($_POST['name'] ?? '');
        $telefono = htmlspecialchars($_POST['phone'] ?? '');
        $mensaje = htmlspecialchars($_POST['message'] ?? '');

        if (!$email) {
            // Email inválido
            echo "<div class='alert alert-danger'>Correo electrónico inválido.</div>";
            return;
        }

        try {
            // Instanciar el objeto PHPMailer
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';

            // DEBUG (temporal para desarrollo)
            $mail->SMTPDebug = 2; // Cambiar a 0 en producción
            $mail->Debugoutput = 'html';

            // Configurar el servidor SMTP
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD; // Debe ser contraseña de aplicación si usas Gmail
            $mail->SMTPSecure = MAIL_SMTP_SECURE;
            $mail->Port = MAIL_PORT;

            // Configurar correo electrónico
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress(MAIL_TO);
            $mail->Subject = 'Nuevo mensaje del formulario de contacto';
            $mail->Body = "Correo electrónico: {$email} <br>" .
                          "Nombre: {$nombre} <br>" .
                          "Teléfono: {$telefono} <br>" .
                          "Mensaje: {$mensaje}";
            $mail->isHTML(true);

            // Enviar correo
            // Registrar fecha que se usará en la cabecera Date (RFC 822)
            $mail->MessageDate = \PHPMailer\PHPMailer\PHPMailer::rfcDate();

            // Crear carpeta de logs si no existe
            $logsDir = BASE_PATH . '/logs';
            if (!is_dir($logsDir)) {
                @mkdir($logsDir, 0777, true);
            }
            $logFile = $logsDir . '/mail.log';

            // Enviar correo y registrar resultado con la marca de tiempo local
            $attemptTime = date('Y-m-d H:i:s');
            if ($mail->send()) {
                echo "<div class='alert alert-success'>Correo enviado correctamente.</div>";
                $entry = "[{$attemptTime}] ENVIADO - DateHeader={$mail->MessageDate} - To=" . MAIL_TO . "\n";
                @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
            } else {
                $err = $mail->ErrorInfo;
                echo "<div class='alert alert-danger'>Error al enviar correo: " . htmlspecialchars($err) . "</div>";
                $entry = "[{$attemptTime}] ERROR - DateHeader={$mail->MessageDate} - Error=" . str_replace("\n", ' ', $err) . "\n";
                @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
            }
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Excepción al intentar enviar: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}

// Llamar a la función para enviar el correo si se envió el formulario
enviarCorreoDesdeFormulario();
?>
