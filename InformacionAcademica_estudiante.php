<?php
session_start();
include 'Conexion.php';

$mensaje = ""; // Inicializar la variable $mensaje

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodoId = isset($_POST["periodoId"]) ? $_POST["periodoId"] : '';
    $carreraId = isset($_POST["carreraId"]) ? $_POST["carreraId"] : '';
    $numcontrol = isset($_POST["numcontrol"]) ? $_POST["numcontrol"] : '';
    $semestre = isset($_POST["semestre"]) ? $_POST["semestre"] : '';
    $promedio = isset($_POST["promedio"]) ? $_POST["promedio"] : '';

    if (empty($periodoId) || empty($carreraId) || empty($numcontrol) || empty($semestre) || empty($promedio)) {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
    } elseif (!is_numeric($semestre) || $semestre < 1 || $semestre > 12) {
        $mensaje = "El semestre debe ser un número válido entre 1 y 12.";
    } elseif (!preg_match('/^\d{2}\.\d$/', $promedio)) {
        $mensaje = "El promedio debe tener el formato correcto, por ejemplo, 90.0.";
    } elseif (!preg_match('/^\d{8}$/', $numcontrol)) {
        $mensaje = "El número de control debe tener 8 dígitos.";
    } else {
        // Preparar y almacenar toda la información del usuario en la base de datos
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        if($conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Insertar en la tabla Usuarios
        $usuario = $_SESSION['registro_usuario'];
        $queryUsuario = "INSERT INTO dbo.Usuarios (nombre, contrasenas, tiposusuariosid) VALUES (?, ?, ?)";
        $paramsUsuario = array($usuario['nombre'], $usuario['contrasena'], $usuario['tiposusuarioid']);
        sqlsrv_query($conn, $queryUsuario, $paramsUsuario);

        // Obtener el ID del último usuario registrado
        $lastUserIdQuery = "SELECT TOP 1 ID_usuarios FROM dbo.Usuarios ORDER BY ID_usuarios DESC";
        $lastUserIdStmt = sqlsrv_query($conn, $lastUserIdQuery);
        $lastUser = sqlsrv_fetch_array($lastUserIdStmt, SQLSRV_FETCH_ASSOC);
        $usuariosid = $lastUser['ID_usuarios'];

        // Insertar en la tabla InformacionPersonal
        $personal = $_SESSION['informacion_personal'];
        $queryPersonal = "INSERT INTO dbo.InformacionPersonal (usuariosid, nombres, primerapellido, segundoapellido, fecha_nacimiento, telefono, email, RFC) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $paramsPersonal = array($usuariosid, $personal['nombres'], $personal['primerapellido'], $personal['segundoapellido'], $personal['fecha_nacimiento'], $personal['telefono'], $personal['email'], $personal['RFC']);
        sqlsrv_query($conn, $queryPersonal, $paramsPersonal);

        // Insertar en la tabla InformacionContacto
        $contacto = $_SESSION['informacion_contacto'];
        $queryContacto = "INSERT INTO dbo.InformacionContacto (usuariosid, codigo_postal, municipio, estado, ciudad, colonia, calle_principal, primer_cruzamiento, segundo_cruzamiento, referencias, numero_exterior, numero_interior) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $paramsContacto = array($usuariosid, $contacto['codigo_postal'], $contacto['municipio'], $contacto['estado'], $contacto['ciudad'], $contacto['colonia'], $contacto['calle_principal'], $contacto['primer_cruzamiento'], $contacto['segundo_cruzamiento'], $contacto['referencias'], $contacto['numero_exterior'], $contacto['numero_interior']);
        sqlsrv_query($conn, $queryContacto, $paramsContacto);

        // Insertar en la tabla InformacionAcademica_estudiante
        $queryAcademica = "INSERT INTO dbo.InformacionAcademica_estudiante (usuariosid, periodoid, carreraId, numcontrol, semestre, promedio) VALUES (?, ?, ?, ?, ?, ?)";
        $paramsAcademica = array($usuariosid, $periodoId, $carreraId, $numcontrol, $semestre, $promedio);
        sqlsrv_query($conn, $queryAcademica, $paramsAcademica);

        // Redirigir al usuario a la página de inicio de sesión
        header("Location: Login.php");
        exit();
    }
}

// Obtener las opciones para los desplegables
$query = "SELECT * FROM Periodo";
$stmt = sqlsrv_query($conn, $query);
$periodos = [];
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $periodos[] = $row;
}

$query = "SELECT * FROM Carrera";
$stmt = sqlsrv_query($conn, $query);
$carreras = [];
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $carreras[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Académica</title>
    <link href="Estilo2.css" rel="stylesheet"/> <!-- Verifica que la ruta al CSS sea correcta -->
    <script>
        window.addEventListener('popstate', function (event) {
            if (event.state && event.state.isBackNavigation) {
                if (confirm("Si regresa, se perderán todos los cambios.")) {
                    history.back();  // Navegar hacia atrás si el usuario confirma
                } else {
                    history.pushState({isBackNavigation: true}, "");  // Mantenerse en la misma página si el usuario cancela
                }
            }
        });

        history.pushState({isBackNavigation: true}, "");  // Agregar el estado actual al historial

        // JavaScript para restringir el input del número de control
        document.addEventListener('DOMContentLoaded', function() {
            var numcontrolInput = document.getElementById('numcontrol');
            numcontrolInput.addEventListener('input', function() {
                var value = this.value.replace(/\D/g, '');
                this.value = value.substring(0, 8);  // Limitar a 8 caracteres
            });
        });
    </script>
</head>

<body>
<div class="container" style="display: flex; align-items: center; justify-content: center; height: 100vh; transform: scale(1.1);">
    <form action="InformacionAcademica_estudiante.php" method="post" style="display: flex; flex-direction: column; width: 400px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); border-radius: 15px; background-color: #EEEEEE; padding: 35px;">
        <h2>Información Académica</h2>
        <?php
        if (!empty($mensaje)) {
            echo "<div class='alert'>" . htmlspecialchars($mensaje) . "</div>"; // Considera definir estilos para 'alert'
        }
        ?>

        <div class="form-group">
            <label for="periodoId"><p>Periodo:</p></label>
            <select name="periodoId" id="periodoId" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;">
                <?php foreach ($periodos as $periodo): ?>
                    <option value="<?= htmlspecialchars($periodo['ID_periodo']) ?>"><?= htmlspecialchars($periodo['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="carreraId"><p>Carrera:</p></label>
            <select name="carreraId" id="carreraId" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;">
                <?php foreach ($carreras as $carrera): ?>
                    <option value="<?= htmlspecialchars($carrera['ID_carrera']) ?>"><?= htmlspecialchars($carrera['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="numcontrol"><p>Número de Control:</p></label>
            <input type="text" name="numcontrol" id="numcontrol" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;" pattern="\d{8}" title="Debe tener exactamente 8 dígitos" required>
        </div>

        <div class="form-group">
            <label for="semestre"><p>Semestre:</p></label>
            <select name="semestre" id="semestre" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="promedio"><p>Promedio:</p></label>
            <input type="text" name="promedio" id="promedio" style="padding: 8px; border-radius: 4px; margin-bottom: 15px;" pattern="^\d{2}\.\d$" title="2 dígitos y 1 decimal, por ejemplo, 90.0" required>
            <small class="form-text"><p>Ejemplo válido: 90.0</p></small>
        </div>

        <button type="submit" class="button-custom">Guardar</button>
    </form>
</div>

</body>
</html>
