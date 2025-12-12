<?php
// Conexión a la base de datos
$pdo = new PDO("mysql:host=localhost;dbname=galeria;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mensaje = "";

// Si envían el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validaciones básicas
    if (empty($username) || empty($password)) {
        $mensaje = "Por favor completa todos los campos";
    } elseif ($password !== $confirm_password) {
        $mensaje = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 4) {
        $mensaje = "La contraseña debe tener al menos 4 caracteres";
    } else {
        try {
            // Verificar si el usuario ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->fetch()) {
                $mensaje = "El nombre de usuario ya existe";
            } else {
                // Insertar nuevo usuario
                $stmt = $pdo->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $password]);
                
                $mensaje = "Registro exitoso. Redirigiendo a login...";
                header("refresh:2;url=login.php");
            }
        } catch (PDOException $e) {
            $mensaje = "Error al registrar usuario: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Galería de Imágenes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>Crear Cuenta</h1>
            
            <?php if ($mensaje): ?>
                <div class="mensaje <?php echo strpos($mensaje, 'exitoso') !== false ? 'exito' : 'error'; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form">
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn-primary">Registrarse</button>
            </form>

            <p class="auth-link">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
            </p>
        </div>
    </div>
</body>
</html>
