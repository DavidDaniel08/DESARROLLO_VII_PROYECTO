<?php
session_start();
include 'conexion.php'; 

$error = '';

// Verifica si se ha enviado el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar el usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Las credenciales son correctas, verifica si es admin
        $_SESSION['usuario'] = $user['username']; // Guardar el nombre de usuario en la sesión
        $_SESSION['user_id'] = $user['id'];

        if ($user['is_admin'] == 1) {
            // Si el usuario es administrador, redirige al AdminDashboard.php
            header("Location: AdminDashboard.php");
            exit();
        } else {
            // Si no es admin, redirige a la página principal del usuario normal
            header("Location: principal.php");
            exit();
        }
    } else {
        // Credenciales incorrectas
        $error = "Nombre de usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="estilo/styles.css">

    <script>
        // Función para ocultar el mensaje de error después de 3 segundos
        function ocultarError() {
            setTimeout(function() {
                var errorElement = document.getElementById('error-message');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }, 3000);
        }

        window.onload = function() {
            // Llamar a la función ocultarError solo si hay un mensaje de error
            <?php if ($error): ?>
                ocultarError();
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        
        <?php if ($error): ?>
            <p class="error" id="error-message"><?= $error ?></p>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <label>Nombre de Usuario:</label>
            <input type="text" name="username" required>
            <label>Contraseña:</label>
            <input type="password" name="password" required>
            <button type="submit">Iniciar Sesión</button>
        </form>

        <h2>¿No tienes una cuenta?</h2>
        <a href="registro.php">Crear cuenta</a>

        <h2>¿Olvidaste tu contraseña?</h2>
        <a href="recuperar_contraseña.php">Recuperar contraseña</a>
    </div>
</body>
</html>
