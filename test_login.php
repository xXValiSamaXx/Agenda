<?php
/**
 * Script de diagnóstico para probar la conexión y el login
 */

// Cargar configuración
require_once __DIR__ . '/config/config.php';

echo "<h2>Diagnóstico del Sistema de Login</h2>";
echo "<hr>";

// 1. Verificar extensión sqlsrv
echo "<h3>1. Verificando extensión SQL Server</h3>";
if (extension_loaded('sqlsrv')) {
    echo "✓ Extensión sqlsrv está cargada<br>";
} else {
    echo "✗ ERROR: Extensión sqlsrv NO está cargada. Debes habilitarla en php.ini<br>";
    echo "Busca y descomenta: extension=php_sqlsrv.dll<br>";
}

// 2. Verificar conexión a la base de datos
echo "<h3>2. Verificando conexión a la base de datos</h3>";
try {
    $db = new Database();
    $conn = $db->getConnection();
    if ($conn) {
        echo "✓ Conexión a la base de datos exitosa<br>";
        
        // 3. Verificar tabla de usuarios
        echo "<h3>3. Verificando tabla de usuarios</h3>";
        $query = "SELECT COUNT(*) as total FROM dbo.Usuarios";
        $stmt = sqlsrv_query($conn, $query);
        
        if ($stmt) {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            echo "✓ Total de usuarios en la base de datos: " . $row['total'] . "<br>";
            
            // 4. Verificar estructura de contraseñas
            echo "<h3>4. Verificando formato de contraseñas</h3>";
            $query2 = "SELECT TOP 5 nombre, contrasenas FROM dbo.Usuarios";
            $stmt2 = sqlsrv_query($conn, $query2);
            
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Usuario</th><th>Formato de Contraseña</th><th>Es Hash?</th></tr>";
            
            while ($user = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                $isHash = (strlen($user['contrasenas']) == 60 && substr($user['contrasenas'], 0, 4) == '$2y$');
                $formato = $isHash ? "Hash bcrypt (correcto)" : "Texto plano o hash incorrecto";
                $icono = $isHash ? "✓" : "✗";
                
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['nombre']) . "</td>";
                echo "<td>" . substr($user['contrasenas'], 0, 30) . "...</td>";
                echo "<td>{$icono} {$formato}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // 5. Prueba de login
            echo "<h3>5. Prueba de Login</h3>";
            echo "<form method='POST'>";
            echo "Usuario: <input type='text' name='test_user' required><br><br>";
            echo "Contraseña: <input type='password' name='test_pass' required><br><br>";
            echo "<input type='submit' name='test_login' value='Probar Login'>";
            echo "</form>";
            
            if (isset($_POST['test_login'])) {
                echo "<h4>Resultado de la prueba:</h4>";
                $testUser = $_POST['test_user'];
                $testPass = $_POST['test_pass'];
                
                $usuario = new Usuario();
                $result = $usuario->login($testUser, $testPass);
                
                if ($result) {
                    echo "<div style='background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; color: #155724;'>";
                    echo "✓ LOGIN EXITOSO<br>";
                    echo "ID: " . $result['ID_usuarios'] . "<br>";
                    echo "Nombre: " . $result['nombre'] . "<br>";
                    echo "Tipo: " . $result['tiposusuariosid'] . "<br>";
                    echo "</div>";
                } else {
                    echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; color: #721c24;'>";
                    echo "✗ LOGIN FALLIDO<br>";
                    echo "El usuario o contraseña son incorrectos, o la contraseña no está hasheada correctamente.";
                    echo "</div>";
                }
            }
            
        } else {
            echo "✗ ERROR al consultar la tabla de usuarios<br>";
            echo "Error: " . print_r(sqlsrv_errors(), true);
        }
        
    } else {
        echo "✗ ERROR: No se pudo conectar a la base de datos<br>";
    }
} catch (Exception $e) {
    echo "✗ EXCEPCIÓN: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<p><a href='index.php?page=login'>Volver al login</a></p>";
?>
